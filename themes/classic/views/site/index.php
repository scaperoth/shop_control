<?php
/* @var $this SiteController */
?>
<?php
$this->pageTitle = Yii::app()->name;
?>

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
    <input type="submit" id="statusToggle" value="<?php echo Yii::app()->user->getState('current_state');?>"/>
<?php $this->endWidget(); ?>
<div>
    <span class="description">{ click to change state }</span>
</div>

<div id="clock">
    <p>

    </p>
</div>
</div>

