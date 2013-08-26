<?php
/* @var $this SiteController */
?>
<?php
$this->pageTitle = Yii::app()->name;
$open_or_closed = (($this->current_state)?'open':'closed');
?>

<?php if($this->current_state!=''):?>
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
    <input type="submit" id="statusToggle" class='<?php echo 'is'.$open_or_closed;?>' value="<?php echo $open_or_closed;?>"/>
<?php $this->endWidget(); ?>
<div>
    <span class="description">{ click to change state }</span>
</div>

<div id="clock">
    <p>

    </p>
</div>
</div>
<?php endif;?>
<?php if($this->current_state==''):?>
<div>
    <h3 id="statusUpdate" style="margin:30px;">You are not at a shop location.</h3>
</div>
<?php endif;?>