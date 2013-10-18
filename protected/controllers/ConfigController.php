<?php

class ConfigController extends Controller {

    public function actionEmails() {
        $model = new AdminEmails;
        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'config-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

// collect user input data
        if (isset($_POST['AdminEmails'])) {
            $model->attributes = $_POST['AdminEmails'];
            try {
                $model->save();
                $model = new AdminEmails;
                $this->refresh();
            } catch (CDbException $e) {
                $model = new AdminEmails;
                $model->addError(null, "This email already exists.");
            }
        }

        $this->render('emails', array('model' => $model));
    }

    public function actionDelete() {
        $id=CHttpRequest::getParam('id');
        try{
        $post = AdminEmails::model()->findByPk($id); // assuming there is a post whose ID is 10
        $post->delete(); // delete the row from the database table
        }  catch (ErrorException $e){
            
        }
    }

    public function actionUpdate() {
        echo (CHttpRequest::getParam('name'));
    }

    public function actionView() {
        echo (CHttpRequest::getParam('id'));
    }

}

?>