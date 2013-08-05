<?php
return array (
  'authenticated' => 
  array (
    'type' => 2,
    'description' => 'authenticated user',
    'bizRule' => 'return !Yii::app()->user->isGuest;',
    'data' => NULL,
  ),
  'guest' => 
  array (
    'type' => 2,
    'description' => 'guest user',
    'bizRule' => 'return Yii::app()->user->isGuest;',
    'data' => NULL,
  ),
  'admin' => 
  array (
    'type' => 2,
    'description' => 'administrator',
    'bizRule' => NULL,
    'data' => NULL,
    'assignments' => 
    array (
      1 => 
      array (
        'bizRule' => NULL,
        'data' => NULL,
      ),
    ),
  ),
);
