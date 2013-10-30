<?php

class ApiController extends Controller {
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

    /**
     * @return array action filters
     */
    public function filters() {
        return array();
    }

    public function actionIndex() {
        echo CJSON::encode(array(1, 2, 3));
    }

    /**
     * returns true or false if today is holiday for specified location
     * @param type String which location to check if today is holiday 
     * @return boolean
     */
    private function _IsShopHoliday($location) {
        $currDay = date('M d');

        foreach ($this->_getHolidaysData() as $holiday) {
            if ($holiday['hol_date'] == $currDay) {
                if ($holiday['loc_id'] == $location['loc_id']) {
                    return TRUE;
                }
            }
        }
        return FALSE;
    }

    /**
     * returns $model of holiday data
     * usage: $return = _getHolidaysData()
     *        $return["hol_id"]
     * @return type $model("hol_id","loc_id","hol_date")
     */
    private function _getHolidaysData() {
        //returns holidays
        return Yii::app()->db->createCommand()
                        ->select('sh.hol_id, loc_id, hol_date')
                        ->from('shop_holidays sh, Holidays h')
                        ->where('sh.hol_id = h.hol_id')
                        ->queryAll();
    }

    private function _getLocationsData() {
        return Locations::model()->findAll();
    }

    /**
     * returns json array with "open" or "closed" for each location
     * @return {"shopname":"open"|"closed",....}
     */
    public function actionShopstatus() {
        // Check if id was submitted via GET
        $JSON_array = array();
        $locations_query = $this->_getLocationsData();
        foreach ($locations_query as $curr_location) {
            $location = $curr_location['loc_name'];
            $isopen = Locations::model()->findByAttributes(array('loc_name' => $location), 'loc_status');

            $isholiday = $this->_IsShopHoliday($location);

            if (!$isopen) {
                $JSON_array[$location] = 'closed';
            } else {
                if ($isholiday) {
                    $JSON_array[$location] = 'closed';
                }
                else
                    $JSON_array[$location] = 'open';
            }
        }
        $this->_sendResponse(200, CJSON::encode($JSON_array));
    }

    /**
     * 
     * @throws CHttpException
     */
    public function actionChangeshopstatus() {
        if ($whichshop = CHttpRequest::getParam('whichshop')) {

            $JSON_array = $this->_ProcessShopStatusChange($whichshop, CHttpRequest::getParam('changeto'));
        }
        else
            throw new CHttpException(404, "The page you are looking for does not exist.");

        $this->_sendResponse(200, CJSON::encode($JSON_array));
    }

    /**
     * 
     * @throws CHttpException
     */
    public function actionCron() {
        //api/cron/34d1f91fb2e514b8576fab1a75a89a6b
        if (CHttpRequest::getParam('action') == md5('go')) {

            $JSON_array[] = $this->_CronJob();
        }
        else
            throw new CHttpException(404, "The page you are looking for does not exist.");

        $this->_sendResponse(200, CJSON::encode($JSON_array));
    }

    /*     * *********************************************
     * CHANGE FUNCTIONS
     * ********************************************** */

    /**
     * 
     * @param type $which_shop
     * @param type $status_to_change_to
     * @return mixed array
     * @throws CHttpException
     */
    private function _ProcessShopStatusChange($which_shop, $status_to_change_to) {
        
        if ($status_to_change_to) {
            switch ($status_to_change_to) {
                case 'open':
                    $JSON_array[] = $this->_CloseOrOpenShop('open', $which_shop);
                    break;
                case 'close':
                    $JSON_array[] = $this->_CloseOrOpenShop('closed', $which_shop);
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
    private function _CloseOrOpenShop($action, $which_shop = 'mylocation') {

        $this->__checkUserAuth();
        //administrative emails
        $admin_emails = $this->admin_emails;
        echo $admin_emails;
//full username
        $username = Yii::app()->user->getState('username');

//get timestamp
        $timestamp = date('Y-m-d H:i:s');

//is user ontime?
        $on_time = 0;

        $message = NULL;
        if ($which_shop == 'mylocation') {
            $location = $this->location;
            $loc_id = $this->loc_id;
        } else {
            $this->__checkAdminAuth();
            $which_shop = addcslashes(strtoupper($which_shop), '%_'); // escape LIKE's special characters
            $q = new CDbCriteria(array(
                'condition' => "UPPER(SUBSTRING(loc_name,1,4)) = :match", // no quotes around :match
                'params' => array(':match' => "$which_shop")  // Aha! Wildcards go here
            ));

            if (!$location_query = Locations::model()->find($q)) {
                return 'wrong location, bub';
            }
            $location = ucfirst($location_query['loc_name']);
            $loc_id = $location_query['loc_id'];
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
        $time_upper_bound = date('d-m-Y H:i:s', $openorcloseHrsTime + 600);
        $time_lower_bound = date('d-m-Y H:i:s', $openorcloseHrsTime - 600);
        
        
        //get current time
        $currtime = date('d-m-Y H:i:s');
        $currdatetime = new DateTime($currtime);
//if current time is later than the open + 10 minutes
//shop was opened late
        if ($currtime > $time_upper_bound) {
            
            $openDateTime = new DateTime($time_upper_bound);
            $difference = $currdatetime->diff($openDateTime, True);
            $early_or_late =  'late';
            $message = $username . " " . ($action == 'open' ? 'opened' : 'closed') . " the $location Support Center $early_or_late. Latest closing time is " . date('H:ia', $openorcloseHrsTime + 600) . ". " . ucfirst($early_or_late) . " by: " . $difference->h . " hours " . $difference->i . " minutes and " . $difference->s . " seconds";

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

    private function _CronJob() {
        $admin_emails = $this->admin_emails;
        //check if "action" == md5(go);

        $holidays_query = Yii::app()->db->createCommand()
                ->select('sh.hol_id, loc_id, hol_date')
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
            $location = $curr_location['loc_name'];
            $isopen = Locations::model()->findByAttributes(array('loc_name' => $location), 'loc_status');
            $shouldbeopen = FALSE;
            $isholiday = FALSE;
            /*
              echo '<h3>' . $location . '</h3>';
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
                    ->where('l.loc_name=:name', array(':name' => $location))
                    ->queryRow();
            $openHrsTime = strtotime($check_query[$table_col_open]);
            $closedHrsTime = strtotime($check_query[$table_col_closed]);

            $open_upper_bound = date('d-m-Y H:i:s', $openHrsTime + 600);
            $closed_lower_bound = date('d-m-Y H:i:s', $closedHrsTime - 600);

            $currtime = date('d-m-Y H:i:s');
            $currDay = date('M d');


            foreach ($holidays_query as $holiday) {
                if ($holiday['hol_date'] == $currDay) {
                    if ($holiday['loc_id'] == $curr_location['loc_id']) {
                        //echo "Don't worry! it's a holiday";
                        $isholiday = TRUE;
                    }
                }
            }

            if ($currtime < $closed_lower_bound && $currtime > $open_upper_bound) {
                $shouldbeopen = TRUE;
            }



            /*             * EMAIL LOGIC FOR CRON JOB* */
            if (!$isholiday) {
                //it"s not a holiday...
                if (!$isopen) {
                    //it's not open
                    if ($shouldbeopen) {
                        //it should be open
                        $message .= "$location should have been opened by $open_upper_bound. Not yet opened.\n";
                        $send_locations[] = $location;
                    } else {
                        //but that's ok
                    }
                } else {
                    //it's open
                    if ($shouldbeopen) {
                        //but it should be, and that's ok
                    } else {
                        //this means no one has closed it
                        $message .= "$location should have been closed by $closed_lower_bound. Not yet closed.\n";
                        $send_locations[] = $location;
                    }
                }
            }//end !isholiday
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

            return 'Mail sent';
        }
        return 'No mail sent';
    }

    /**
     * 
     * @param type $status
     * @param string $body
     * @param type $content_type
     */
    private function _sendResponse($status = 200, $body = '', $content_type = 'text/html') {
        // set the status
        header("Access-Control-Allow-Origin: *");
        $status_header = 'HTTP/1.1 ' . $status . ' ' . $this->_getStatusCodeMessage($status);
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
    <title>' . $status . ' ' . $this->_getStatusCodeMessage($status) . '</title>
</head>
<body>
    <h1>' . $this->_getStatusCodeMessage($status) . '</h1>
    <p>' . $message . '</p>
    <hr />
    <address>' . $signature . '</address>
</body>
</html>';

            echo $body;
        }
        Yii::app()->end();
    }

    private function _getStatusCodeMessage($status) {
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

    private function __checkUserAuth() {
        if (Yii::app()->user->isGuest) {
            Yii::app()->user->setFlash('error', $this->login_error);
            $this->redirect(array('/'));
        }
    }

    private function __checkAdminAuth() {
        if (!Yii::app()->user->checkAccess('admin')) {
            Yii::app()->user->setFlash('error', $this->login_error);
            $this->redirect(Yii::app()->homeUrl);
        }
    }

}

