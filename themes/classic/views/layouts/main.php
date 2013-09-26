<?php
/* @var $this SiteController */

//$hostname = gethostname();
//echo gethostbyname($hostname);

$display_location = (($this->location) ? ' - ' . ucfirst($this->location) : '');
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.9.1.min.js"><\/script>')</script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/js/bootstrap/jquery-1.10.2.min.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/js/bootstrap/jquery-migrate-1.2.1.min.js"></script>
        <title><?php echo CHtml::encode($this->pageTitle) . $display_location; ?></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">
        <link rel="shortcut icon" href="<?php echo Yii::app()->theme->baseUrl; ?>/img/gw_logo.ico">

        <!--[if lt IE 8]>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/ie.css" media="screen, projection" />
        <![endif]-->
        <link href='http://fonts.googleapis.com/css?family=Quicksand:300,400' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/assets/css/bootstrap/bootstrap.css">
        <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/assets/css/bootstrap/bootstrap-responsive.css">
        <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/assets/css/bootstrap/tablecloth.css">
        <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/assets/css/bootstrap/bootstrap-datepicker.css">
        <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/assets/silviomoreto-bootstrap-select-10ba1a3/bootstrap-select.min.css">
        <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/assets/js/jonthornton-jquery-timepicker-ced5953/jquery.timepicker.css">
        <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/assets/css/bootstrap/prettify.css">
        <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/assets/css/style.css">
        <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/assets/css/bootstrap-style-override.css">
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/js/vendor/modernizr-2.6.2.min.js"></script>
    </head>
    <body>

        <div class="limiter">
            <div class="container" id="page">

                <!--[if lt IE 7]>
                    <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
                <![endif]-->

                <div class="masthead">
                    <h3 class="muted">
                        <img src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/img/AT%20Logo.jpg" alt="Academic Technologies"/>
                    </h3>
                    <div class="title">
                        <h1 id="page-title">
                            <?php echo CHtml::encode(Yii::app()->name) . $display_location ?>
                        </h1>
                    </div>
                    <div class="clear"></div>
                    <div class="navbar <?php echo (Yii::app()->user->checkAccess('admin') ? '' : 'user'); ?>">
                        <div class="navbar-inner">
                            <div class="container">
                                <?php
                                $this->widget('zii.widgets.CMenu', array(
                                    'items' => array(
                                        array('label' => 'Open/Close Shop', 'url' => array('/site/index')),
                                        array('label' => 'Change Shop Hours and Holidays', 'url' => array('/site/admin'), 'visible' => Yii::app()->user->checkAccess('admin')),
                                        array('label' => 'Run Reports', 'url' => array('/site/reporting'), 'visible' => Yii::app()->user->checkAccess('admin')),
                                        array('label' => 'Logout (' . Yii::app()->user->name . ')', 'url' => array('/site/logout'), 'visible' => !Yii::app()->user->isGuest),
                                        array('label' => 'Login', 'url' => array('/site/login'), 'visible' => Yii::app()->user->isGuest),
                                    ), 'lastItemCssClass' => 'mitem-last',
                                    'activateParents' => true,
                                    'htmlOptions' => array('class' => 'nav')
                                ));
                                ?>
                            </div>
                        </div>
                    </div><!-- /.navbar -->
                </div><!--end masthead-->
                <div id='flash'>
                    <?php if (Yii::app()->user->hasFlash('error')): ?>
                        <div class="alert alert-error">
                            <a class="close" data-dismiss="alert">&#215;</a>
                            <div id="flash_error"><?php echo Yii::app()->user->getFlash('error'); ?></div>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="clear"></div>
                <?php if (isset($this->breadcrumbs)): ?>
                    <?php
                    $this->widget('zii.widgets.CBreadcrumbs', array(
                        'links' => $this->breadcrumbs,
                    ));
                    ?><!-- breadcrumbs -->
                <?php endif ?>

                <?php echo $content; ?>


                <div class="clear"></div>

                <div id="footer">

                    <a href="http://acadtech.gwu.edu" target="blank">Academic Technologies</a> of <a href="http://www.gwu.edu" target="_blank">the George Washington University</a><br/>
                    Phone: 202-994-7900 | Fax: 202-994-4747 | Email: <a href="mailto:acadtech@gwu.edu">acadtech.gwu.edu</a> <br/>

                </div><!-- footer -->

            </div><!-- container #page-->
        </div><!-- limiter -->

        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/js/bootstrap/bootstrap.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/js/bootstrap/jquery.metadata.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/js/bootstrap/jquery.tablesorter.min.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/js/bootstrap/jquery.tablecloth.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/silviomoreto-bootstrap-select-10ba1a3/bootstrap-select.min.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/js/plugins.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/js/jonthornton-jquery-timepicker-ced5953/jquery.timepicker.min.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/js/bootstrap/bootstrap-transition.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/js/bootstrap/bootstrap-datepicker.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/js/main.js"></script>
        <?php
        /**
         * load admin.js if page is admin
         */
        if (Yii::app()->getController()->getAction()->id == 'admin') {
            echo'<script src="' . Yii::app()->theme->baseUrl . '/assets/js/admin.js"></script>';
        }
        ?>

        <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
        <script>
            var _gaq = [['_setAccount', 'UA-XXXXX-X'], ['_trackPageview']];
            (function(d, t) {
                var g = d.createElement(t), s = d.getElementsByTagName(t)[0];
                g.src = '//www.google-analytics.com/ga.js';
                s.parentNode.insertBefore(g, s)
            }(document, 'script'));
            $(document).ready(function() {
                $("table").tablecloth({
                    theme: "default",
                    striped: true,
                    condensed: true,
                    clean: true,
                    cleanElements: "th td"
                });
                $('.selectpicker').selectpicker({
                    width: '100%'
                });
                
                $('.datepicker').datepicker({
                    format: 'M dd'
                });
            });
        </script>
    </body>
</html>
