<?php

class AdminController extends Controller
{
	public function actionIndex()
	{
            
		$this->render('index');
	}
         public function filters()
        {
            return array(
                'accessControl'           // required to enable accessRules
            );
        }

        public function accessRules(){
            return array(
                array('allow', // allow anyone to register
                      'actions'=>array('Index'), 
                      'users'=>array('admin') // all users
                ),
                array('deny', // deny anything else
                      'users'=>array('*')
                )
            );
        }
	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}