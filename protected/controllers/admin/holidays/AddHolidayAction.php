<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class AddHolidayAction extends CAction {

    public function run() {
        $vars = $_POST['AddHoliday'];
        //print_r($vars);
        $hol_name = $vars['holidayname'];
        $hol_date = $vars['holidaystartdate'];
        $hol_description = $vars['holidaydescription'];

        $new_holiday = new Holidays();
        $new_holiday->hol_name = $hol_name;
        $new_holiday->hol_start_date = $hol_start_date;
        $new_holiday->hol_description = $hol_description;
        $new_holiday->save();


        Yii::app()->user->setFlash('success', 'Holiday added.');
    }

    function check_in_range($start_date, $end_date, $date_from_user) {
        // Convert to timestamp
        $start_ts = strtotime($start_date);
        $end_ts = strtotime($end_date);
        $user_ts = strtotime($date_from_user);

        // Check that user date is between start & end
        return (($user_ts >= $start_ts) && ($user_ts <= $end_ts));
    }

}

?>
