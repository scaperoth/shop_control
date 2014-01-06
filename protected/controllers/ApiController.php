<?php

class ApiController extends Controller {


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
     * returns json array with "open" or "closed" for each location
     * @return {"shopname":"open"|"closed",....}
     */
    public function actionShopstatus() {
        // Check if id was submitted via GET
        $JSON_array = array();
        $locations_query = ApiHelper::_getLocationsData();
        foreach ($locations_query as $location) {
            $location_name = $location['loc_name'];
            $isopen = Locations::model()->findByAttributes(array('loc_name' => $location_name), 'loc_status');
            
            $isholiday = ApiHelper::_toggleLocationStatusifHoliday($location['loc_id']);
            
            if (!$isopen) {
                if ($isholiday) {
                    $JSON_array[$location_name] = 'closed (holiday)';
                }else $JSON_array[$location_name] = 'closed';
            } else {
                if ($isholiday) {
                    $JSON_array[$location_name] = 'closed (holiday)';
                }
                else
                    $JSON_array[$location_name] = 'open';
            }
        }
        ApiHelper::_sendResponse(200, CJSON::encode($JSON_array));
    }

    /**
     * 
     * @throws CHttpException
     */
    public function actionChangeshopstatus() {
        if ($whichshop = CHttpRequest::getParam('whichshop')) {

            $JSON_array = ApiHelper::_ProcessShopStatusChange($whichshop, CHttpRequest::getParam('changeto'));
        }
        else
            throw new CHttpException(404, "The page you are looking for does not exist.");

        ApiHelper::_sendResponse(200, CJSON::encode($JSON_array));
    }

    /**
     * 
     * @throws CHttpException
     */
    public function actionCron() {
        //api/cron/34d1f91fb2e514b8576fab1a75a89a6b
        if (CHttpRequest::getParam('action') == md5('go')) {

            $JSON_array[] = ApiHelper::_CronJob();
        }
        else
            throw new CHttpException(404, "The page you are looking for does not exist.");

        ApiHelper::_sendResponse(200, CJSON::encode($JSON_array));
    }

    /*     * *********************************************
     * CHANGE FUNCTIONS
     * ********************************************** */
    

}

