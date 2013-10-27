<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
Yii::setPathOfAlias('bootstrap', dirname(__FILE__).'/../extensions/bootstrap');
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Classroom Support Center Control',
    'homeurl' => 'login',
    'defaultController' => 'site/login',
    'theme' => 'bootstrap',
    // preloading 'log' component
    'preload' => array('log'),
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
    ),
    'modules' => array(
        // uncomment the following to enable the Gii tool
        /**/
        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => 'qWINT7nllFaTsSeI37wg',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => array('127.0.0.1', '::1'),
        ),
    ),
    // application components
    'components' => array(
        'bootstrap'=>array(
            'class'=>'bootstrap.components.Bootstrap',
        ),
        'session' => array(
            'class' => 'CDbHttpSession',
            'timeout' => 1200,
            'autoCreateSessionTable' => 'false',
        ),
        'authManager' => array(
            'class' => 'CPhpAuthManager'

        //          'authFile' => 'path'                  // only if necessary
        ),
        'user' => array(
            // enable cookie-based authentication
            'allowAutoLogin' => false,
            'class' => 'WebUser'
        ),
        // uncomment the following to enable URLs in path-format
        /**/
        'urlManager' => array(
            'urlFormat' => 'path',
            //uncomment to clean "index" out of url
            //'showScriptName' => false,
            //'caseSensitive' => false,
            //'useStrictParsing'=>true,
            'rules' => array(
                'config/emails' => 'config/emails',
                'api/shopinfo' => 'api/shopinfo',
                'api/shopinfo/<whichshop:[\w]+>' => 'api/shopinfo',
                'api/shophours' => 'api/shopinfo',
                'api/shophours/<whichshop:[\w]+>' => 'api/shopinfo',
                'api/shopstatus' => 'api/shopstatus',
                'api/shopstatus/<location:[^a-zA-Z]>' => 'api/shopstatus',
                'api/cron/<action:\w+>' => 'api/cron',
                'api/changeshopstatus/<whichshop:[\w\s]+>/<changeto:[\w]+>' => 'api/changeshopstatus',
                
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ),
        'db' => array(
            'connectionString' => 'sqlite:' . dirname(__FILE__) . '/../data/testdrive.db',
        ),
        // uncomment the following to use a MySQL database
        /**/
        'db' => array(
            'connectionString' => 'mysql:host=localhost;dbname=shop_control',
            'emulatePrepare' => true,
            'username' => 'shop_db',
            'password' => 'pr0d_My$ql',
            'charset' => 'utf8',
        ),
        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
            // uncomment the following to show log messages on web pages
            /*
              array(
              'class'=>'CWebLogRoute',
              ),
             */
            ),
        ),
    ),
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array(
        // this is used in contact page
        'ldap' => array(
            'host' => 'fbwndc22.cats.gwu.edu',
            'port' => 389,
            'domain' => "@cats.gwu.edu",
            'oustaff' => 'staff', // such as "people" or "users"
            'oupeople' => 'people', // such as "people" or "users"
            'outest' => 'test1',
            'dc' => array('cats', 'gwu', 'edu'),
        ),
        'ip' => CHttpRequest::getUserHostAddress(),
        //set default location
        'location',
        'current_state',
    ),
);