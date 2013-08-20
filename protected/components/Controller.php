<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column2';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
        
         public function filters()
        {
            return array(
                'accessControl'           // required to enable accessRules
            );
        }

        public function accessRules(){
            return array(
               
                array('allow',
                    'ips'=>array('127.0.0.1')
                ),
                array('deny',
                    'ips'=>array('*'),
                     'message'=>'<h1>You are not Allowed to access this site from this location.</h1><div hidden>'
                )
            );
        }
}