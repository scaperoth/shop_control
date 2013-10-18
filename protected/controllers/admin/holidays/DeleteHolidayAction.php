<?php

class DeleteHolidayAction extends CAction {

    public function run() {
        $vars = ($_POST['DeleteHoliday']);
        $hol_id = $vars['holidayselect'];
        $for_each_var = Holidays::model()->findAll();
        foreach ($for_each_var as $current) {
            if (md5($current['hol_id']) == $hol_id) {
                Holidays::model()->deleteByPk($current['hol_id']);
            }
        }
        Yii::app()->user->setFlash('success', 'Holiday deleted.');
    }

}

?>
