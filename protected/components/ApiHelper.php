<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class ApiHelper extends CHtml{
    
    // Members
    /**
     * Key which has to be in HTTP USERNAME and PASSWORD headers 
     */
    
    Const APPLICATION_ID = 'ASCCPE';

    /**
     * Default response format
     * either 'json' or 'xml'
     */
    private $format = 'json';
    Const SHOP_TIME_THRESHOLD = 900;
    const LOGIN_ERROR = "You have insufficient permissions to continue";
    
   
    /**
     * 
     * @param type $which_shop
     * @param type $status_to_change_to
     * @return mixed array
     * @throws CHttpException
     */
    public static function _ProcessShopStatusChange($which_shop, $status_to_change_to) {

        if ($status_to_change_to) {
            switch ($status_to_change_to) {
                case 'open':
                    $JSON_array[] = self::_CloseOrOpenShop('open', $which_shop);
                    break;
                case 'close':
                    $JSON_array[] = self::_CloseOrOpenShop('closed', $which_shop);
                    break;

                default:
                    throw new CHttpException(404, "The API for '../api/changeshopstatus/$which_shop/$status_to_change_to' cannot be found.");
            }
        }
        else
            throw new CHttpException(404, "The page you are looking for does not exist.");

        return $JSON_array;
    }

    /**
     * 
     * @param type $action
     * @param type $which_shop
     * @return string|null
     */
    public static function _CloseOrOpenShop($action, $which_shop = 'mylocation') {

        self::__checkUserAuth();
        //administrative emails
        $admin_emails = Yii::app()->controller->admin_emails;
        echo $admin_emails;
//full username
        $username = Yii::app()->user->getState('username');

//get timestamp
        $timestamp = date('Y-m-d H:i:s');

//is user ontime?
        $on_time = 0;

        $message = NULL;
        
        /*
         * if the user is changing the state of a shop other than their own
         * then check to make sure they are an admin. 
         */
        if($which_shop!='mylocation')
            self::__checkAdminAuth();
        else {
            $which_shop = substr(Yii::app()->controller->location, 0, 4);
            $loc_id = Yii::app()->controller->loc_id;
        }
        
        $which_shop = addcslashes(strtoupper($which_shop), '%_'); // escape LIKE's special characters
        $q = new CDbCriteria(array(
            'condition' => "UPPER(SUBSTRING(loc_name,1,4)) = :match", // no quotes around :match
            'params' => array(':match' => "$which_shop")  // Aha! Wildcards go here
        ));
        
        if (!$location_query = Locations::model()->find($q)) {
            return 'wrong location, bub!';
        }
        $location = ucfirst($location_query['loc_name']);
        $loc_id = $location_query['loc_id'];


        $isholiday = self::_IsShopHoliday($loc_id);

        //show flash message if state is being changed on a holiday
        if ($isholiday) {
            $closure_message = self::_isadmin()?"(See \"Closures\")":"";
            Yii::app()->user->setFlash('error', "The $location support center is set to be closed today. $closure_message");
            return $message;
        }
//get current day of the week
        $dayofweek = strtolower(date('D'));
        $table_col = 'loc_' . $dayofweek . '_' . $action . '_hrs';
//check closed hours
        $check_query = Yii::app()->db->createCommand()
                ->select(array($table_col, 'loc_status'))
                ->from('locations l')
                ->where('l.loc_name=:name', array(':name' => $location))
                ->queryRow();
        //exit if status is already achieved. using boolean loc_status
        if (!$check_query['loc_status'] && $action == 'closed') {
            $message = 'Shop is already ' . $action;
            return $message;
        } else if ($check_query['loc_status'] && $action == 'open') {
            $message = 'Shop is already ' . $action;
            return $message;
        }
//translate databse closed hours into php time()
        $openorcloseHrsTime = strtotime($check_query[$table_col]);



//get upper deviation of time. +- 10 minutes from db shop closed time
        $time_upper_bound = date('d-m-Y H:i:s', $openorcloseHrsTime + ApiHelper::SHOP_TIME_THRESHOLD);
        $time_lower_bound = date('d-m-Y H:i:s', $openorcloseHrsTime - ApiHelper::SHOP_TIME_THRESHOLD);


        //get current time
        $currtime = date('d-m-Y H:i:s');
        $currdatetime = new DateTime($currtime);
//if current time is later than the open + 10 minutes
//shop was opened late
        if ($currtime > $time_upper_bound) {

            $openDateTime = new DateTime($time_upper_bound);
            $difference = $currdatetime->diff($openDateTime, True);
            $early_or_late = 'late';
            $message = $username . " " . ($action == 'open' ? 'opened' : 'closed') . " the $location Support Center $early_or_late. Latest " . ($action == 'open' ? 'opening' : 'closing') . " time is " . date('H:ia', $openorcloseHrsTime + ApiHelper::SHOP_TIME_THRESHOLD) . ". " . ucfirst($early_or_late) . " by: " . $difference->h . " hours " . $difference->i . " minutes and " . $difference->s . " seconds";

            Yii::app()->user->setFlash('error', "The $location Support Center has been " . ($action == 'open' ? 'opened' : 'closed') . " " . $early_or_late . " by " . $difference->h . ' hours ' . $difference->i . ' minutes and ' . $difference->s . ' seconds');
        }

//if current time is less than the open - 10 minutes
//shop was opened early
        else if ($currtime < $time_lower_bound) {
            $openDateTime = new DateTime($time_lower_bound);
            $difference = $currdatetime->diff($openDateTime, True);

            $early_or_late = 'early';
            $message = $username . " " . ($action == 'open' ? 'opened' : 'closed') . " the $location Support Center $early_or_late. by " . $difference->h . ' hours ' . $difference->i . ' minutes and ' . $difference->s . ' seconds';

            Yii::app()->user->setFlash('error', "The $location Support Center has been " . ($action == 'open' ? 'opened' : 'closed') . " $early_or_late by " . $difference->h . ' hours ' . $difference->i . ' minutes and ' . $difference->s . ' seconds');
        } else {
            $on_time = 1;
            $early_or_late = '-';
            $message = '';
            Yii::app()->user->setFlash('success', "The $location Support Center has been " . ($action == 'open' ? 'opened' : 'closed'));
        }


        if ($message != NULL && !Yii::app()->user->checkAccess('admin')) {

            $to = $admin_emails;
            $subject = "Classroom Support Center - $location: " . ($action == 'open' ? 'Opened' : 'Closed') . " $early_or_late";
            $headers = "From: Support Center Control App <acadtech@gwu.edu>";

            mail($to, $subject, $message, $headers, '-facadtech.gwu.edu');
        }
        $lct = new LocationChangeTracking();
        $lct->lct_location = $location;
        $lct->lct_user = $username;
        $lct->lct_action = $action;
        $lct->lct_early_or_late = $early_or_late;
        $lct->lct_message = $message;
        $lct->lct_timestamp = $timestamp;
        $lct->insert();

//update status table
        $new_status = ($action == 'open' ? 1 : 0);
        $location_model = Locations::model()->findByPk($loc_id);
        $location_model->loc_status = $new_status;
        $location_model->save(); // save the change to database
//update tracking table


        return $message;
    }

    public static function _CronJob() {
        $admin_emails = Yii::app()->controller->admin_emails;
        //check if "action" == md5(go);

        $holidays_query = Yii::app()->db->createCommand()
                ->select('sh.hol_id, loc_id, hol_start_date, hol_end_date')
                ->from('shop_holidays sh, Holidays h')
                ->where('sh.hol_id = h.hol_id')
                ->queryAll();

        /*
         * which locations to send in the email
         */
        $send_locations = array();
        $message = NULL;
        $locations = Locations::model()->findAll();
        foreach ($locations as $curr_location) {

            $location_name = $curr_location['loc_name'];
            $status_query = Locations::model()->findByAttributes(array('loc_name' => $location_name), 'loc_status');
            $isopen = $status_query['loc_status'];
            $loc_id = $curr_location['loc_id'];
            $shouldbeopen = FALSE;
            $shouldbeclosed = FALSE;
            $isholiday = FALSE;
            /*
              echo '<h3>' . $location_name . '</h3>';
              if (!$isopen) {
              echo'<pre class="well">closed</pre>';
              } else {
              echo'<pre>open</pre>';
              }
             * 
             */

            $dayofweek = strtolower(date('D'));
            $table_col_open = 'loc_' . $dayofweek . '_open_hrs';
            $table_col_closed = 'loc_' . $dayofweek . '_closed_hrs';
//check open hours
            $check_query = Yii::app()->db->createCommand()
                    ->select($table_col_open . ',' . $table_col_closed)
                    ->from('locations l')
                    ->where('l.loc_name=:name', array(':name' => $location_name))
                    ->queryRow();
            $openHrsTime = strtotime($check_query[$table_col_open]);
            $closedHrsTime = strtotime($check_query[$table_col_closed]);
            if ($openHrsTime != $closedHrsTime) {
                $open_lower_bound = date('d-m-Y H:i:s', $openHrsTime - ApiHelper::SHOP_TIME_THRESHOLD);
                $open_upper_bound = date('d-m-Y H:i:s', $openHrsTime + ApiHelper::SHOP_TIME_THRESHOLD);
                $closed_lower_bound = date('d-m-Y H:i:s', $closedHrsTime - ApiHelper::SHOP_TIME_THRESHOLD);
                $closed_upper_bound = date('d-m-Y H:i:s', $closedHrsTime + ApiHelper::SHOP_TIME_THRESHOLD);

                $currtime = date('d-m-Y H:i:s');
                $currDay = date('M d');


                $isholiday = self::_IsShopHoliday($loc_id);

                if ($open_upper_bound < $currtime && $currtime < $closed_lower_bound) {
                    $shouldbeopen = TRUE;
                }

                if ($closed_upper_bound < $currtime || $currtime < $open_lower_bound) {
                    $shouldbeclosed = TRUE;
                }

                /*                 * EMAIL LOGIC FOR CRON JOB* */
                if (!$isholiday) {
                    //it"s not a holiday...
                    if (!$isopen) { //it is closed
                        if ($shouldbeopen) {
                            //it should be open
                            $message .= "$location_name should have been opened by $open_upper_bound. Not opened yet ('.$currtime.').\n";
                            $send_locations[] = $location_name;
                        } else {
                            //but that's ok
                        }
                    } else {
                        //it's open
                        if ($shouldbeclosed) {
                            //this means no one has closed it
                            $message .= "$location_name should have been closed by $closed_lower_bound. Not closed yet ('.$currtime.').\n";
                            $send_locations[] = $location_name;
                        } else {
                            //this should be ok
                        }
                    }
                }//end !isholiday
            }//end first check
        }//end foreach


        $to = $admin_emails;
        $subject = "Support Center Status Error(s): ";
        $i = 0;
        foreach ($send_locations as $loc) {
            $subject.=$loc;
            if (count($send_locations) > 1 && $i < count($send_locations)) {
                $subject.=", ";
            }
            $i++;
        }
        $headers = "From: Support Center Control App <acadtech@gwu.edu>";
        if ($message) {
            mail($to, $subject, $message, $headers, '-facadtech.gwu.edu');

            return 'Mail sent: ' . $message;
        }


        return 'No mail sent';
    }

    /**
     * 
     * @param type $status
     * @param string $body
     * @param type $content_type
     */
    public static function _sendResponse($status = 200, $body = '', $content_type = 'text/html') {
        // set the status
        header("Access-Control-Allow-Origin: *");
        $status_header = 'HTTP/1.1 ' . $status . ' ' . self::_getStatusCodeMessage($status);
        header($status_header);
        // and the content type
        header('Content-type: ' . $content_type);

        // pages with body are easy
        if ($body != '') {
            // send the body
            echo $body;
        }
        // we need to create the body if none is passed
        else {
            // create some body messages
            $message = '';

            // this is purely optional, but makes the pages a little nicer to read
            // for your users.  Since you won't likely send a lot of different status codes,
            // this also shouldn't be too ponderous to maintain
            switch ($status) {
                case 401:
                    $message = 'You must be authorized to view this page.';
                    break;
                case 404:
                    $message = 'The requested URL ' . $_SERVER['REQUEST_URI'] . ' was not found.';
                    break;
                case 500:
                    $message = 'The server encountered an error processing your request.';
                    break;
                case 501:
                    $message = 'The requested method is not implemented.';
                    break;
            }

            // servers don't always have a signature turned on 
            // (this is an apache directive "ServerSignature On")
            $signature = ($_SERVER['SERVER_SIGNATURE'] == '') ? $_SERVER['SERVER_SOFTWARE'] . ' Server at ' . $_SERVER['SERVER_NAME'] . ' Port ' . $_SERVER['SERVER_PORT'] : $_SERVER['SERVER_SIGNATURE'];

            // this should be templated in a real-world solution
            $body = '
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <title>' . $status . ' ' . self::_getStatusCodeMessage($status) . '</title>
</head>
<body>
    <h1>' . self::_getStatusCodeMessage($status) . '</h1>
    <p>' . $message . '</p>
    <hr />
    <address>' . $signature . '</address>
</body>
</html>';

            echo $body;
        }
        Yii::app()->end();
    }

    public static function _getStatusCodeMessage($status) {
        // these could be stored in a .ini file and loaded
        // via parse_ini_file()... however, this will suffice
        // for an example
        $codes = Array(
            200 => 'OK',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
        );
        return (isset($codes[$status])) ? $codes[$status] : '';
    }

    private $login_error = "Access Denied";

    /**
     * returns true or false if today is holiday for specified location
     * @param $location_id id of location to check for holiday today
     * @return boolean
     */
    public static function _IsShopHoliday($location_id) {
        $currDay = date('M d');

        foreach (self::_getHolidaysData() as $holiday) {
            if (self::_check_in_range($holiday['hol_start_date'], $holiday['hol_end_date'], $currDay)) {
                if ($holiday['loc_id'] == $location_id) {
                    return TRUE;
                }
            }
        }
        return FALSE;
    }
    
    /**
     * if the current location is on holiday then set the status to closed.
     * acts as a more specialized version of _IsShopHoliday
     * @param $location_id id of location to do check on 
     * @return boolean whether or not it is a holiday for the given location.
     */
    public static function _toggleLocationStatusifHoliday($location_id) {
        if(self::_IsShopHoliday($location_id)){
            $location_model = Locations::model()->findByPk($location_id);
            $location_model->loc_status = 0;
            $location_model->save();
            
            Yii::app()->controller->current_state = '0';
            
            return TRUE;
        }
        return FALSE;
    }

    /**
     * 
     * @param type $start_date
     * @param type $end_date
     * @param type $date_from_user
     * @return type
     */
    public static function _check_in_range($start_date, $end_date, $date_from_user) {
        // Convert to timestamp
        $return;

        $start_ts = strtotime($start_date);
        $end_ts = strtotime($end_date);
        $user_ts = strtotime($date_from_user);

        /* >= and <= to start and end respectively */
        $gte_lte_start_end = (($user_ts <= $start_ts) && ($user_ts >= $end_ts));
        /* > and < to end and start respectively */
        $gt_lt_end_start = (($user_ts < $start_ts) && ($user_ts > $end_ts));

        /* if the range wraps around the year */
        if ($end_ts < $start_ts) {
            $return = !$gt_lt_end_start; /* not end---today---start */
        }
        else
            $return = $gte_lte_start_end;
        // Check that user date is between start & end
        return ($return);
    }

    /**
     * returns $model of holiday data
     * usage: $return = _getHolidaysData()
     *        $return["hol_id"]
     * @return type $model("hol_id","loc_id","hol_start_date,hol_end_date")
     */
    public static function _getHolidaysData() {
        //returns holidays
        return Yii::app()->db->createCommand()
                        ->select('sh.hol_id, loc_id, hol_start_date,hol_end_date')
                        ->from('shop_holidays sh, Holidays h')
                        ->where('sh.hol_id = h.hol_id')
                        ->queryAll();
    }

    public static function _getLocationsData() {
        return Locations::model()->findAll();
    }

    public static function __checkUserAuth() {
        if (Yii::app()->user->isGuest) {
            Yii::app()->user->setFlash('error', self::LOGIN_ERROR);
            Yii::app()->controller->redirect(array('/'));
        }
    }

    public static function __checkAdminAuth() {
        $isadmin = self::_isadmin();
        if (!$isadmin) {
            Yii::app()->user->setFlash('error', self::LOGIN_ERROR);
            Yii::app()->controller->redirect(array(Yii::app()->getHomeUrl()));
        }
    }
    
    public static function _isadmin(){
        return Yii::app()->user->checkAccess('admin');
    }
    
}
?>
