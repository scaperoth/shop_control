<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle = Yii::app()->name . ' - Login';
/*
  $this->breadcrumbs=array(
  'Login',
  ); */
?>
<style type="text/css">
    
    body {
        padding-bottom: 40px;
    }

    .form-signin {
        max-width: 300px;
        padding: 19px 29px 29px;
        margin: 0 auto 20px;
        background-color: #fff;
        border: 1px solid #e5e5e5;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        border-radius: 5px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
        -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
        box-shadow: 0 1px 2px rgba(0,0,0,.05);
    }
    .form-signin .form-signin-heading,
    .form-signin .checkbox {
        margin-bottom: 10px;
    }
    .form-signin input[type="text"],
    .form-signin input[type="password"] {
        font-size: 16px;
        height: auto;
        margin-bottom: 15px;
        padding: 7px 9px;
    }
    .form{
        width:500px;
        margin:0 auto;
    }
</style>


<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'login-form',
        'htmlOptions' => array(
            'class' => 'form-horizontal',
        ),
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
    ));
    ?>
    <legend>Sign In</legend>

    <div class="row">
        <div class="control-group">
            <label class="control-label" for="username">Username:</label>
            <div class="controls">
                <div class="input-prepend">
                    <span class="add-on"><i class="icon-user"></i></span>
                    <?php echo $form->textField($model, 'username', array('placeholder' => 'Enter NedID', 'class' => 'span3')); ?>
                </div>
                <?php echo $form->error($model, 'username'); ?>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="control-group">
            <label class="control-label" for="password">Password:</label>
            <div class="controls">
                <div class="input-prepend">
                    <span class="add-on"><i class="icon-lock"></i></span>
                    <?php echo $form->passwordField($model, 'password', array('placeholder' => $model->getAttributeLabel('password'), 'class' => 'span3')); ?>
                </div>
                <?php echo $form->error($model, 'password'); ?>

            </div>
        </div>
    </div>

    <!--<div class="row rememberMe">
    <?php // echo $form->checkBox($model, 'rememberMe'); ?> Remember Me
    <?php //echo $form->error($model, 'rememberMe'); ?>
    </div>-->

    <div class="buttons">
        <div class="control-group">
            <label class="control-label"></label>
        <?php echo CHtml::submitButton('Login', array('class' => 'btn btn-success center')); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>
</div><!-- form -->
