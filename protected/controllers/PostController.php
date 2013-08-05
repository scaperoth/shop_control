<?php
/**
 * 
 */
class UserController extends CController {
 
    public function filters()
    {
        return array(
            'accessControl'           // required to enable accessRules
        );
    }
 
    public function accessRules(){
        return array(
            array('allow', // allow anyone to register
                  'actions'=>array('create'), 
                  'users'=>array('*'), // all users
            ),
            array('allow', // allow authenticated users to update/view
                  'actions'=>array('update','view'), 
                  'roles'=>array('authenticated')
            ),
            array('allow', // allow admins only to delete
                  'actions'=>array('delete'), 
                  'roles'=>array('admin'),
            ),
            array('deny', // deny anything else
                  'users'=>array('*'),
            ),
        );
    }
}
