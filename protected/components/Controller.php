<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController {

    public $location;
    public $loc_id;
    public $current_state;
    public $admin_emails;

    /**
     * set up globals for all actions
     * @param type $action
     * @return boolean
     */
    protected function beforeAction($action) {
        $admin_emails = '';
        $criteria = new CDbCriteria;
        $criteria->select = 'email';  // only select the 'title' column
        $admin_emails_query = AdminEmails::model()->findAll($criteria); // $params is not needed
        foreach ($admin_emails_query as $amail) {
            $admin_emails.=$amail['email'] . ';';
        }
        $this->admin_emails = "$admin_emails";
        //
        //query location using current ip address <--(found in protected/config/main.php under params)
        $location = Yii::app()->db->createCommand()
                ->select("loc_name, loc_status, loc_id")
                ->from('locations l')
                ->join('ips ip', 'l.loc_id=ip.ip_loc_id')
                ->where('ip.ip_address=:ip', array(':ip' => Yii::app()->params['ip']))
                ->queryRow();
        $this->current_state = $location['loc_status'];
        $this->location = $location['loc_name'];
        $this->loc_id = $location['loc_id'];
        return parent::beforeAction($action);
    }

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

    /**
     * 
     * @param type $filterChain

      public function filterAccessControl($filterChain) {
      $filter = new MyAccessControlFilter;  // CHANGED THIS
      $filter->setRules($this->accessRules());
      $filter->filter($filterChain);
      }
     */
    public function accessRules() {

        //get all ip addresses from ips table
        $ips = Yii::app()->db->createCommand()
                ->select('ip_address')
                ->from('ips ip')
                ->join('locations l', 'l.loc_id=ip.ip_loc_id')
                ->where('l.loc_flag=:active', array(':active' => 1))
                ->queryAll();

        /* add ips to array */
        foreach ($ips as $ip) {
            $ip_filter[] = $ip['ip_address'];
        }
        //used for testing denied ips
        //$ip_filter = array('127.0.0.2');
        return array(
            array('allow',
                'roles' => array('admin'),
            ),
            //only allow ips from database
            array('allow',
                'ips' => $ip_filter,
            ),
            array('allow',
                'actions' => array('login', 'cron'),
            ),
            //disallow ips on only index and admin actions so that error page can show.
            array('deny',
                'ips' => array('*'),
                'deniedCallback' => function() {
                    Yii::app()->user->logout();

                    //arguments are 'error' and 'denied'
                    Yii::app()->controller->redirect(array('/site/login?' . md5('error') . '=' . md5('denied')));
                }
            )
        );
    }

}