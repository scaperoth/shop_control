<?php

$auth=Yii::app()->authManager;

$bizRule='return !Yii::app()->user->isGuest;';
$auth->createRole('authenticated', 'authenticated user', $bizRule);
 
$bizRule='return Yii::app()->user->isGuest;';
$auth->createRole('guest', 'guest user', $bizRule);

$role = $auth->createRole('admin', 'administrator');
$auth->assign('admin',1); // adding admin to first user created

$auth->save();