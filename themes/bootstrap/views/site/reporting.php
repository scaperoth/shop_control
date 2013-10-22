<?php
/* @var $this SiteController */
?>
<?php
$this->pageTitle = Yii::app()->name;
?>
<legend class="section-title">Reporting</legend>
<div class="center" style="text-align: center;" id="buttonControl">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'employeeTrackingForm',
        'action' => '../phpactions/export',
        'enableAjaxValidation' => true,
    ));
    ?>
    <input name="employeeTrackingForm" hidden>
    <input type="submit" class="btn btn-link" value="Download the Employee Tracking Data (.CSV)"/>
    <?php $this->endWidget(); ?>

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'shopHoursForm',
        'action' => '../phpactions/export',
        'enableAjaxValidation' => true,
    ));
    ?>
    <input name="shopHoursForm" hidden>
    <input type="submit" class="btn btn-link" value="Download the Shop Hours Tracking Data (.CSV)"/>
    <?php $this->endWidget(); ?>
</div>
