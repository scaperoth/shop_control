<?php

class AddLocationAction extends CAction {

    public function run() {
        if (Yii::app()->request->isPostRequest) {
            $vars = $_POST['AddLocation'];
            print_r($vars);
            $loc_name = $vars['locationname'];
            $ip = $vars['ipaddress'];
            $comp_name = $vars['computername'];

            $phys_location = new Locations();
            $phys_location->loc_name = $loc_name;
            $phys_location->save();

            $new_loc_id = Locations::model()->findByAttributes(array('loc_name' => $loc_name), 'loc_id');

            $ip_location = new Ips();
            $ip_location->ip_address = $ip;
            $ip_location->ip_compname = $comp_name;
            $ip_location->ip_loc_id = $new_loc_id['loc_id'];
            $ip_location->save();

            Yii::app()->user->setFlash('success', 'Location added.');
        } else {
            Yii::app()->user->setFlash('error', 'Access Denied.');
            $this->redirect(Yii::app()->user->returnUrl);
        }
    }

}

?>
