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
    private function __IsShopHoliday($location) {
        $currDay = date('M d');

        foreach ($this->__getHolidaysData() as $holiday) {
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
     * usage: $return = __getHolidaysData()
     *        $return["hol_id"]
     * @return type $model("hol_id","loc_id","hol_date")
     */
    private function __getHolidaysData() {
        //returns holidays
        return Yii::app()->db->createCommand()
                        ->select('sh.hol_id, loc_id, hol_date')
                        ->from('shop_holidays sh, Holidays h')
                        ->where('sh.hol_id = h.hol_id')
                        ->queryAll();
    }

    private function __getLocationsData() {
        return Locations::model()->findAll();
    }

    /**
     * returns json array with "open" or "closed" for each location
     * @return {"shopname":"open"|"closed",....}
     */
    public function actionShopstatus() {
        // Check if id was submitted via GET
        $this->__checkUserAuth();
        $JSON_array = array();
        $locations_query = $this->__getLocationsData();
        foreach ($locations_query as $curr_location) {
            $location = $curr_location['loc_name'];
            $isopen = Locations::model()->findByAttributes(array('loc_name' => $location), 'loc_status');

            $isholiday = $this->__IsShopHoliday($location);

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

    public function actionChangeshopstatus() {
        if ($param = CHttpRequest::getParam('changeto')) {
            switch ($param) {
                case 'open':
                    $JSON_array[] = $this->__OpenShop();
                    break;
                case 'close':
                    $JSON_array[] = $this->__CloseShop();
                    break;
                default:
                    throw new CHttpException(404,"The API for '../api/changeshopstatus/$param' cannot be found.");
                    
            }
        }
        else throw new CHttpException(404,"The page you are looking for does not exist.");

        $this->_sendResponse(200, CJSON::encode($JSON_array));
    }

    /*     * *********************************************
     * CHANGE FUNCTIONS
     * ********************************************** */

    private function __CloseShop($location = NULL) {

        $this->__checkUserAuth();
        //administrative emails
        $admin_emails = Yii::app()->params['admin_emails'];
//full username
        $username = Yii::app()->user->getState('username');
//open or closed
        $action = 'close';
//get timestamp
        $timestamp = date('Y-m-d H:i:s');

//is user ontime?
        $on_time = 0;

        $message = NULL;

        $location = $this->location;



//get current day of the week
        $dayofweek = strtolower(date('D'));
        $table_col = 'loc_' . $dayofweek . '_closed_hrs';
//check closed hours
        $check_query = Yii::app()->db->createCommand()
                ->select(array($table_col, 'loc_status'))
                ->from('locations l')
                ->where('l.loc_name=:name', array(':name' => $location))
                ->queryRow();
        if (!$check_query['loc_status']) {
            $message = 'Shop is already closed';
            return $message;
        }
//translate databse closed hours into php time()
        $openHrsTime = strtotime($check_query[$table_col]);

//get current time
        $currtime = date('d-m-Y H:i:s');

//get upper deviation of time. +- 10 minutes from db shop closed time
        $open_upper_bound = date('d-m-Y H:i:s', $openHrsTime + 600);
        $open_lower_bound = date('d-m-Y H:i:s', $openHrsTime - 600);

//if current time is later than the open + 10 minutes
//shop was opened late
        if ($currtime > $open_upper_bound) {
            $currtime = new DateTime($currtime);
            $openDateTime = new DateTime($open_upper_bound);
            $difference = $currtime->diff($openDateTime, True);

            $message = $username . " closed the $location shop late. Latest closing time is " . date('H:ia', $openHrsTime + 600) . '. Late by: ' . $difference->h . ' hours ' . $difference->i . ' minutes and ' . $difference->s . ' seconds';
            Yii::app()->user->setFlash('error', "The shop has been closed late by " . $difference->h . ' hours ' . $difference->i . ' minutes and ' . $difference->s . ' seconds');
        }

//if current time is less than the open - 10 minutes
//shop was opened early
        else if ($currtime < $open_lower_bound) {
            $currtime = new DateTime($currtime);
            $openDateTime = new DateTime($open_lower_bound);
            $difference = $currtime->diff($openDateTime, True);


            $message = $username . " closed the $location shop early by " . $difference->h . ' hours ' . $difference->i . ' minutes and ' . $difference->s . ' seconds';
            Yii::app()->user->setFlash('error', "The shop has been closed early by " . $difference->h . ' hours ' . $difference->i . ' minutes and ' . $difference->s . ' seconds');
        } else {
            $on_time = 1;
            $message = NULL;
        }


        if ($message != NULL && !Yii::app()->user->checkAccess('admin')) {

            $to = $admin_emails;
            $subject = 'Test email using PHP';
            $headers = 'From: acadtech@gwu.edu' . "\r\n" .
                    'Reply-To: webmaster@example.com' . "\r\n" .
                    'X-Mailer: PHP/' . phpversion();

            mail($to, $subject, $message, $headers, '-facadtech.gwu.edu');

            $lct = new LocationChangeTracking();
            $lct->lct_location = $location;
            $lct->lct_user = $username;
            $lct->lct_action = $action;
            $lct->lct_on_time = $on_time;
            $lct->lct_message = $message;
            $lct->lct_timestamp = $timestamp;
            $lct->insert();
        }
//update status table
        $loc_id = $this->loc_id;
        $new_status = 0;
        $location_model = Locations::model()->findByPk($loc_id);
        $location_model->loc_status = $new_status;
        $location_model->save(); // save the change to database
//update tracking table


        return $message;
    }

    /**
     * 
     * @param type $location
     */
    private function __OpenShop($location = NULL) {
        //administrative emails
        $admin_emails = Yii::app()->params['admin_emails'];
//full username
        $username = Yii::app()->user->getState('username');
//open or closed
        $action = 'open';
//get timestamp
        $timestamp = date('Y-m-d H:i:s');

//is user ontime?
        $on_time = 0;

//whether or not to send an email defaults to no
        $message = NULL;

//get curr location
        $location = $this->location;
//get current day of the week
        $dayofweek = strtolower(date('D'));
        $table_col = 'loc_' . $dayofweek . '_open_hrs';
//check open hours
        $check_query = Yii::app()->db->createCommand()
                ->select(array($table_col,'loc_status'))
                ->from('locations l')
                ->where('l.loc_name=:name', array(':name' => $location))
                ->queryRow();

        if ($check_query['loc_status']) {
            $message = 'Shop is already open';
            return $message;
        }
        
//translate databse open hours into php time()
        $openHrsTime = strtotime($check_query[$table_col]);

//get current time
        $currtime = date('d-m-Y H:i:s');

//get upper deviation of time. +- 10 minutes from db shop open time
        $open_upper_bound = date('d-m-Y H:i:s', $openHrsTime + 600);
        $open_lower_bound = date('d-m-Y H:i:s', $openHrsTime - 600);

//if current time is later than the open + 10 minutes
//shop was opened late
        if ($currtime > $open_upper_bound) {
            $currtime = new DateTime($currtime);
            $openDateTime = new DateTime($open_upper_bound);
            $difference = $currtime->diff($openDateTime, True);

            $message = $username . " opened the $location shop late. Latest opening time is " . date('H:ia', $openHrsTime + 600) . '. Late by: ' . $difference->h . ' hours ' . $difference->i . ' minutes and ' . $difference->s . ' seconds';
            Yii::app()->user->setFlash('error', "The shop has been opened late by " . $difference->h . ' hours ' . $difference->i . ' minutes and ' . $difference->s . ' seconds');
        }

//if current time is less than the open - 10 minutes
//shop was opened early
        else if ($currtime < $open_lower_bound) {
            $currtime = new DateTime($currtime);
            $openDateTime = new DateTime($open_lower_bound);
            $difference = $currtime->diff($openDateTime, True);


            $message = $username . " opened the. $location shop early by " . $difference->h . ' hours ' . $difference->i . ' minutes and ' . $difference->s . ' seconds';

            Yii::app()->user->setFlash('error', 'The shop has been opened early by ' . $difference->h . ' hours ' . $difference->i . ' minutes and ' . $difference->s . ' seconds');
        } else {
            $on_time = 1;
            $message = NULL;
        }

//if send messages is set to 1 then send the email. otherwise don't.
        if ($message != NULL && !Yii::app()->user->checkAccess('admin')) {


            $to = $admin_emails;
            $subject = 'Test email using PHP';
            $headers = 'From: acadtech@gwu.edu' . "\r\n" .
                    'Reply-To: scaperoth@gmail.com' . "\r\n" .
                    'X-Mailer: PHP/' . phpversion();

            mail($to, $subject, $message, $headers, '-facadtech.gwu.edu');

            $lct = new LocationChangeTracking();
            $lct->lct_location = $location;
            $lct->lct_user = $username;
            $lct->lct_action = $action;
            $lct->lct_on_time = $on_time;
            $lct->lct_message = $message;
            $lct->lct_timestamp = $timestamp;
            $lct->insert();
        }

        $loc_id = $this->loc_id;
        $new_status = 1;
        $location_model = Locations::model()->findByPk($loc_id);
        $location_model->loc_status = $new_status;
        $location_model->save(); // save the change to database


        return $message;
    }

    /**
     * 
     * @param type $status
     * @param string $body
     * @param type $content_type
     */
    private function _sendResponse($status = 200, $body = '', $content_type = 'text/html') {
        // set the status
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
            $this->redirect(Yii::app()->user->returnUrl);
        }
    }

}

