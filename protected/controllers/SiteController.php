<?php

class SiteController extends Controller {

    private $login_error = "Please login to continue";

    /**
     * 
     */
    public function actionFlashMsg() {
        if (Yii::app()->user->hasFlash('time_update')) {
            echo '<div class = "alert alert-error">
                        <a class = "close" data-dismiss = "alert">&#215;</a>
                        <div id = "flash_error">' . Yii::app()->user->getFlash("time_update") . '</div>
                    </div>';
        } else if (Yii::app()->user->hasFlash('error')) {
            echo '<div class = "alert alert-error">
                        <a class = "close" data-dismiss = "alert">&#215;</a>
                        <div id = "flash_error">' . Yii::app()->user->getFlash("error") . '</div>
                    </div>';
        } else if (Yii::app()->user->hasFlash('holiday_update')) {
            echo '<div class = "alert alert-error">
                        <a class = "close" data-dismiss = "alert">&#215;</a>
                        <div id = "flash_error">' . Yii::app()->user->getFlash("holiday_update") . '</div>
                    </div>';
        }
    }

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
            Yii::app()->user->setFlash('error', $this->login_error);
            $this->redirect(array('login'));
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
            Yii::app()->user->setFlash('error', $this->login_error);
            $this->redirect(Yii::app()->user->returnUrl);
        }
        else
            $this->render('admin');
    }

    public function actionReporting() {
// renders the view file 'protected/views/site/index.php'
// using the default layout 'protected/views/layouts/main.php'
//if(!Yii::app()->user->checkAccess('authenticated'))
        if (!Yii::app()->user->checkAccess('admin')) {
            Yii::app()->user->setFlash('error', $this->login_error);
            $this->redirect(Yii::app()->user->returnUrl);
        }
        else
            $this->render('reporting');
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
//get arguments.

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
            if (isset($_GET[md5('error')])) {
                if ($_GET[md5('error')] == md5('denied') && Yii::app()->user->isGuest) {
                    Yii::app()->user->setFlash('error', 'You cannot access this site from your current location');
                }
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
     * @param type $model
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * test mail function...
     */
    public function actionMailTest() {
        if (Yii::app()->request->isPostRequest) {
            $to = 'mscapero@gwu.edu';
            $subject = 'Test email using PHP';
            $message = 'This is a test email message';
            $headers = 'From: webmaster@example.com' . "\r\n" .
                    'Reply-To: webmaster@example.com' . "\r\n" .
                    'X-Mailer: PHP/' . phpversion();
            mail($to, $subject, $message, $headers, '-fwebmaster@example.com');
        }
    }

}