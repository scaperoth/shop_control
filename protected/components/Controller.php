<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController {

    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout = '//layouts/column2';

    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu = array();

    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs = array();

    public function filters() {
        return array(
            'accessControl'           // required to enable accessRules
        );
    }

    public function accessRules() {

        //get all ip addresses from ips table
        $ips = Yii::app()->db->createCommand()
                ->select('ip_address')
                ->from('Ips ip')
                ->join('Locations l', 'l.loc_id=ip.ip_loc_id')
                ->where('l.loc_flag=:active', array(':active' => 1))
                ->queryAll();
        
        //add ips to array
        foreach ($ips as $ip) {
            $ip_filter[] = $ip['ip_address'];
        }
        return array(
            //only allow ips from database
            array('allow',
                'ips' => $ip_filter,
            ),
            //disallow ips on only index and admin actions so that error page can show.
            array('deny',
                'ips' => array('*'),
                'actions' => array('index', 'admin', 'openshop','closeshop'),
                'message' => "You cannot access this application from your current location."
            )
        );
    }

}