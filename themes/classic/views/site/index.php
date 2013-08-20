<?php
/* @var $this SiteController */
?>
<?php
$this->pageTitle = Yii::app()->name;
?>

<h1 id="page-title"><?php echo CHtml::encode(Yii::app()->name); ?>: User Page <i>(<?php echo Yii::app()->params->ip; ?>)</i></h1>
<div>
    <h3 id="statusUpdate">This shop is now</h3>
</div>
<div class="center" id="buttonControl">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'toggleShopForm',
        'enableAjaxValidation' => true,
    ));
    ?>
    <input type="submit" id="statusToggle" value="closed"/>
<?php $this->endWidget(); ?>
<div>
    <dfn class="description">click to change state</dfn>
</div>

<div id="clock">
    <p>

    </p>
</div>
</div>

