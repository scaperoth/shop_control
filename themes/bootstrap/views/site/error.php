<?php
/* @var $this SiteController */
/* @var $error array */

$this->pageTitle=Yii::app()->name . ' - Error';

?>
<style>#mainmenu{display:none;}</style>
<h2 id="page-title">Error <?php echo $code;  ?></h2>

<div class="error text-center red">
<?php echo CHtml::encode($message); ?>
</div>