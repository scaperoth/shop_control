<?php

class DeleteLocationAction extends CAction {

    public function run() {
            $vars = ($_POST['DeleteLocation']);
            $loc_id = $vars['locationname'];
            $locations = Locations::model()->findAll();
            foreach ($locations as $location) {
                if (md5($location['loc_id']) == $loc_id) {
                    Locations::model()->deleteByPk($location['loc_id']);
                }
            }
            Yii::app()->user->setFlash('success', 'Location deleted.');
        
    }

}

?>
