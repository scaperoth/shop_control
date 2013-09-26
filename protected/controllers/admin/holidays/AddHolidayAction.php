<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class AddHolidayAction extends CAction {

    public function run() {
        if (Yii::app()->request->isPostRequest) {
            $vars = $_POST['AddHoliday'];
            //print_r($vars);
            $hol_name = $vars['holidayname'];
            $hol_date = $vars['holidaydate'];
            $hol_description = $vars['holidaydescription'];

            $new_holiday = new Holidays();
            $new_holiday->hol_name = $hol_name;
            $new_holiday->hol_date = $hol_date;
            $new_holiday->hol_description = $hol_description;
            $new_holiday->save();


            Yii::app()->user->setFlash('success', 'Holiday added.');
        } else {
            Yii::app()->user->setFlash('error', 'Access Denied.');
            $this->redirect(Yii::app()->user->returnUrl);
        }
    }

}

?>
