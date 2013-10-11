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
        return array(
            array('application.filters.ApiVarsFilter'),
            );
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
    private function __getHolidaysData(){
        //returns holidays
        return Yii::app()->db->createCommand()
                ->select('sh.hol_id, loc_id, hol_date')
                ->from('shop_holidays sh, holidays h')
                ->where('sh.hol_id = h.hol_id')
                ->queryAll();
    }
    private function __getLocationsData(){
        return Locations::model()->findAll();
    }

    /**
     * returns json array with open/closed for each location
     * @return {"shopname":"status",....}
     */
    public function actionAllShopStatus() {
        // Check if id was submitted via GET
        switch ($_GET['resource']) {
            
            // Find respective model    
            case 'allshopstatus':
                $location_and_status;
                $locations_query = $this->__getLocationsData();
                foreach ($locations_query as $curr_location) {
                    $location = $curr_location['loc_name'];
                    $isopen = Locations::model()->findByAttributes(array('loc_name' => $location), 'loc_status');
                    
                    $isholiday = $this->__IsShopHoliday($location);
                    
                    if (!$isopen) {
                        $location_and_status[$location] ='closed';
                    } else {
                        if ($isholiday) {
                           $location_and_status[$location]='closed';
                        }
                        else
                           $location_and_status[$location]='open';
                    }
                }
                break;
            default:
                $this->_sendResponse(501, sprintf(
                                'API is not implemented for requested <b>%s</b>', $_GET['resource']));
                Yii::app()->end();
        }
            $this->_sendResponse(200, CJSON::encode($location_and_status));
    }


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

}

