<?php

class SiteController extends Controller {

    /**
     * Declares class-based actions.
     */
    public function actions() {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
        // renders the view file 'protected/views/site/index.php'
        // using the default layout 'protected/views/layouts/main.php'
        //if(!Yii::app()->user->checkAccess('authenticated'))
        if (Yii::app()->user->isGuest) {
            Yii::app()->user->setFlash('error', 'Access Denied.');
            $this->redirect(array('/site/login'));
        }
        else
            $this->render('index');
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionAdmin() {
        // renders the view file 'protected/views/site/index.php'
        // using the default layout 'protected/views/layouts/main.php'
        //if(!Yii::app()->user->checkAccess('authenticated'))
        if (!Yii::app()->user->checkAccess('admin')) {
            Yii::app()->user->setFlash('error', 'Access Denied.');
            $this->redirect(Yii::app()->user->returnUrl);
        }
        else
            $this->render('admin');
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Displays the contact page
     */
    public function actionContact() {
        $model = new ContactForm;
        if (isset($_POST['ContactForm'])) {
            $model->attributes = $_POST['ContactForm'];
            if ($model->validate()) {
                $name = '=?UTF-8?B?' . base64_encode($model->name) . '?=';
                $subject = '=?UTF-8?B?' . base64_encode($model->subject) . '?=';
                $headers = "From: $name <{$model->email}>\r\n" .
                        "Reply-To: {$model->email}\r\n" .
                        "MIME-Version: 1.0\r\n" .
                        "Content-type: text/plain; charset=UTF-8";

                mail(Yii::app()->params['adminEmail'], $subject, $model->body, $headers);
                Yii::app()->user->setFlash('contact', 'Thank you for contacting us. We will respond to you as soon as possible.');
                $this->refresh();
            }
        }
        $this->render('contact', array('model' => $model));
    }

    /**
     * Displays the login page
     */
    public function actionLogin() {
        if (!Yii::app()->user->isGuest) {
            $this->render('index');
        } else {
            $model = new LoginForm;

            // if it is ajax validation request
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }

            // collect user input data
            if (isset($_POST['LoginForm'])) {
                $model->attributes = $_POST['LoginForm'];
                // validate user input and redirect to the previous page if valid
                if ($model->validate() && $model->login())
                    $this->redirect(Yii::app()->user->returnUrl);
            }
            // display the login form
            $this->render('login', array('model' => $model));
        }
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

    /**
     * 
     */
    public function actionOpenShop() {

        if (Yii::app()->request->isPostRequest) {
            //full username
            $username = Yii::app()->user->getState('username');
            //open or closed
            $action = 'open';
            //get timestamp
            $timestamp = date('Y-m-d H:i:s');

            //is user ontime?
            $on_time = 0;

            //whether or not to send an email defaults to no
            $send_message = 0;

//get curr location
            $location = Yii::app()->user->getState('location');
//get current day of the week
            $dayofweek = strtolower(date('D'));
            $table_col = 'loc_' . $dayofweek . '_open_hrs';
//check open hours
            $check_query = Yii::app()->db->createCommand()
                    ->select($table_col)
                    ->from('Locations l')
                    ->where('l.loc_name=:name', array(':name' => $location))
                    ->queryRow();

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
                $send_message = 1;
                $currtime = new DateTime($currtime);
                $openDateTime = new DateTime($open_upper_bound);
                $difference = $currtime->diff($openDateTime, True);

                $message = $username . ' opened the shop late</br>Latest opening time is ' . date('H:ia', $openHrsTime + 600) . '.<br/>Late by: ' . $difference->h . ' hours ' . $difference->i . ' minutes and ' . $difference->s . ' seconds';
            }

//if current time is less than the open - 10 minutes
//shop was opened early
            else if ($currtime < $open_lower_bound) {
                $send_message = 1;
                $currtime = new DateTime($currtime);
                $openDateTime = new DateTime($open_lower_bound);
                $difference = $currtime->diff($openDateTime, True);


                $message = $username . ' opened the shop early by </br>' . $difference->h . ' hours ' . $difference->i . ' minutes and ' . $difference->s . ' seconds';
            } else {
                $on_time = 1;
                $message = 'opened on time';
            }

            //if send messages is set to 1 then send the email. otherwise don't.
            if ($send_message) {
                $headers = 'From: webmaster@example.com';
                mail('nobody@example.com', 'Test email using PHP', 'This is a test email message', $headers, '-fwebmaster@example.com');

                $to = 'scaperoth@gmail.com';
                $subject = 'Test email using PHP';
                $headers = 'From: scaperoth@gmail.com' . "\r\n" .
                        'Reply-To: scaperoth@gmail.com' . "\r\n" .
                        'X-Mailer: PHP/' . phpversion();

                mail($to, $subject, $message, $headers, '-scaperoth@gmail.com');
            }

            $loc_id = Yii::app()->user->getState('loc_id');
            $new_status = 1;
            $location_model = Locations::model()->findByPk($loc_id);
            $location_model->loc_status = $new_status;
            $location_model->save(); // save the change to database

            $lct = new LocationChangeTracking();
            $lct->lct_location = $location;
            $lct->lct_user = $username;
            $lct->lct_action = $action;
            $lct->lct_on_time = $on_time;
            $lct->lct_message = $message;
            $lct->lct_timestamp = $timestamp;
            $lct->insert();

            echo $message;
        } else {
            Yii::app()->user->setFlash('error', 'Access Denied.');
            $this->redirect(Yii::app()->user->returnUrl);
        }
    }

    /**
     * 
     */
    public function actionCloseShop() {
        if (Yii::app()->request->isPostRequest) {

            //full username
            $username = Yii::app()->user->getState('username');
            //open or closed
            $action = 'close';
            //get timestamp
            $timestamp = date('Y-m-d H:i:s');

            //is user ontime?
            $on_time = 0;
            $send_message = 1;

            //get curr location
            $location = Yii::app()->user->getState('location');
//get current day of the week
            $dayofweek = strtolower(date('D'));
            $table_col = 'loc_' . $dayofweek . '_closed_hrs';
//check open hours
            $check_query = Yii::app()->db->createCommand()
                    ->select($table_col)
                    ->from('Locations l')
                    ->where('l.loc_name=:name', array(':name' => $location))
                    ->queryRow();

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
                $send_message = 1;
                $currtime = new DateTime($currtime);
                $openDateTime = new DateTime($open_upper_bound);
                $difference = $currtime->diff($openDateTime, True);

                $message = $username . ' closed the shop late</br>Latest closing time is ' . date('H:ia', $openHrsTime + 600) . '.<br/>Late by: ' . $difference->h . ' hours ' . $difference->i . ' minutes and ' . $difference->s . ' seconds';
            }

//if current time is less than the open - 10 minutes
//shop was opened early
            else if ($currtime < $open_lower_bound) {
                $send_message = 1;
                $currtime = new DateTime($currtime);
                $openDateTime = new DateTime($open_lower_bound);
                $difference = $currtime->diff($openDateTime, True);


                $message = $username . 'closed the shop early by </br>' . $difference->h . ' hours ' . $difference->i . ' minutes and ' . $difference->s . ' seconds';
            } else {
                $on_time = 1;
                $message = 'closed on time';
            }


            if ($send_message) {
                $headers = 'From: webmaster@example.com';
                mail('nobody@example.com', 'Test email using PHP', 'This is a test email message', $headers, '-fwebmaster@example.com');

                $to = 'scaperoth@gmail.com';
                $subject = 'Test email using PHP';
                $headers = 'From: scaperoth@gmail.com' . "\r\n" .
                        'Reply-To: scaperoth@gmail.com' . "\r\n" .
                        'X-Mailer: PHP/' . phpversion();

                mail($to, $subject, $message, $headers, '-scaperoth@gmail.com');
            }
            //update status table
            $loc_id = Yii::app()->user->getState('loc_id');
            $new_status = 0;
            $location_model = Locations::model()->findByPk($loc_id);
            $location_model->loc_status = $new_status;
            $location_model->save(); // save the change to database
            //update tracking table
            $lct = new LocationChangeTracking();
            $lct->lct_location = $location;
            $lct->lct_user = $username;
            $lct->lct_action = $action;
            $lct->lct_on_time = $on_time;
            $lct->lct_message = $message;
            $lct->lct_timestamp = $timestamp;
            $lct->insert();

            echo $message;
        } else {
            Yii::app()->user->setFlash('error', 'Access Denied.');
            $this->redirect(Yii::app()->user->returnUrl);
        }
    }

    /**
     * 
     * @param type $model
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}