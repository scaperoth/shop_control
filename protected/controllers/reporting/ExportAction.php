<?php

class ExportAction extends CAction
{
    public function run(){
        if (Yii::app()->request->isPostRequest) {
            if (isset($_POST['employeeTrackingForm'])) {
                $query = LocationChangeTracking::model()->findAll();
                $attr = array('lct_id' => array('number'),
                    'lct_location' => array('text'),
                    'lct_user' => array('text'),
                    'lct_action' => array('text'),
                    'lct_on_time' => array('number'),
                    'lct_message' => array('text'),
                    'lct_timestamp' => array('time'),
                );
                $filename = 'ShopControlEmployeesTracking-upto--' . date('d-m-Y H-i') . ".csv";
            } elseif (isset($_POST['shopHoursForm'])) {
                $query = LocationHoursTracking::model()->findAll();
                $attr = array('lht_id' => array('number'),
                    'lht_username' => array('text'),
                    'lht_loc_name' => array('text'),
                    'lht_mon_open_hrs' => array('time'),
                    'lht_mon_closed_hrs' => array('time'),
                    'lht_tue_open_hrs' => array('time'),
                    'lht_tue_closed_hrs' => array('time'),
                    'lht_wed_open_hrs' => array('time'),
                    'lht_wed_closed_hrs' => array('time'),
                    'lht_thu_open_hrs' => array('time'),
                    'lht_thu_closed_hrs' => array('time'),
                    'lht_fri_open_hrs' => array('time'),
                    'lht_fri_closed_hrs' => array('time'),
                    'lht_sat_open_hrs' => array('time'),
                    'lht_sat_closed_hrs' => array('time'),
                    'lht_sun_open_hrs' => array('time'),
                    'lht_sun_closed_hrs' => array('time'),
                );
                $filename = 'ShopControlHoursTracking-upto--' . date('d-m-Y H-i') . ".csv";
            }
            CsvExport::export(
                    $query, // a CActiveRecord array OR any CModel array
                    $attr, true, // boolPrintRows
                    $filename
            );
        }
    }
}
?>
