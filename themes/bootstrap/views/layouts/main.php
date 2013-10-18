<?php
/* @var $this SiteController */

//$hostname = gethostname();
//echo gethostbyname($hostname);

$display_location = (($this->location) ? ' - ' . ucfirst($this->location) : '');
?>
<!DOCTYPE html>
<html lang="en">
    <!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
    <!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
    <!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
    <!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
        <head>
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

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
            <?php Yii::app()->bootstrap->register(); ?>
            <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/assets/silviomoreto-bootstrap-select-10ba1a3/bootstrap-select.min.css">
            <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/assets/js/jonthornton-jquery-timepicker-ced5953/jquery.timepicker.css">
            <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/assets/font-awesome/css/font-awesome.min.css">
            <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/assets/css/style.css">
            <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/assets/css/bootstrap-style-override.css">
            <script src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/js/vendor/modernizr-2.6.2.min.js"></script>
        </head>
        <body>
            <?php
            $separator = NULL;
            if(Yii::app()->user->checkAccess('admin')) $separator = '---';
            $this->widget('bootstrap.widgets.TbNavbar', array(
                'type' => 'null', // null or 'inverse'\
                'brand' => '<img src="' . Yii::app()->theme->baseUrl . '/assets/img/at_logo.png" alt="Academic Technologies\"/>',
                'brandUrl' => '/site/index',
                'collapse' => true, // requires bootstrap-responsive.css
                'items' => array(
                    array(
                        'class' => 'bootstrap.widgets.TbMenu',
                        'htmlOptions' => array('class' => 'pull-right', 'style' => 'margin-top:10px'),
                        'items' => array(
                            array('label' => 'Open/Close Shop', 'url' => array('/site/index'), 'visible' =>!Yii::app()->user->isGuest),
                            '---',
                            array('label' => 'Shop Details', 'url' => array('/site/admin'), 'visible' => Yii::app()->user->checkAccess('admin'),),
                            array('label' => 'Run Reports', 'url' => array('/site/reporting'), 'visible' => Yii::app()->user->checkAccess('admin'),),
                            array('label' => 'Update Admin Emails', 'url' => array('/config/emails'), 'visible' => Yii::app()->user->checkAccess('admin'),),
                            '---',
                            array('label' => 'Logout (' . Yii::app()->user->name . ')', 'url' => array('/site/logout'), 'visible' => !Yii::app()->user->isGuest),
                            
                        ),
                    ),
                ),
            ));
            ?>

            <div class="container" id="page">

                <!--[if lt IE 7]>
                    <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
                <![endif]-->
                <div class="span12 row-fluid">
                    <div class="title">
                        <h1 id="page-title">
                            <?php echo CHtml::encode(Yii::app()->name) . $display_location ?>
                        </h1>
                    </div>
                    <div class="clear"></div>
                </div>
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
                <hr>
                <div id="footer">

                    <a href="http://acadtech.gwu.edu" target="blank">Academic Technologies</a> of <a href="http://www.gwu.edu" target="_blank">the George Washington University</a><br/>
                    Phone: 202-994-7900 | Fax: 202-994-4747 | Email: <a href="mailto:acadtech@gwu.edu">acadtech.gwu.edu</a> <br/>

                </div><!-- footer -->

            </div><!-- container #page-->

            <script src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/js/bootstrap/jquery.metadata.js"></script>
            <script src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/js/bootstrap/jquery.tablesorter.min.js"></script>
            <script src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/js/bootstrap/jquery.tablecloth.js"></script>
            <script src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/silviomoreto-bootstrap-select-10ba1a3/bootstrap-select.min.js"></script>
            <script src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/js/plugins.js"></script>
            <script src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/js/jonthornton-jquery-timepicker-ced5953/jquery.timepicker.min.js"></script>

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
