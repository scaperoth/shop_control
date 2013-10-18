

<div class="form">
    <style>
        .alert-error,.error {
            text-align: left;
            margin: 0px auto;
        }
    </style>
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'vertical',
        'type' => 'vertical',
        'enableAjaxValidation'=>true,
    ));
    ?>
    <legend>Admin Emails Control Panel</legend>
    <?php
    $this->widget('bootstrap.widgets.TbGridView', array(
        'dataProvider' => $model->search(),
        'template' => "{summary}\n{items}\n{pager}",
        'ajaxUpdate'=>true,
        'columns' => array(
            array('name' => 'email_id', 
                'header' => 'ID', 
                'visible' => FALSE,
                ),
            array('name' => 'email', 
                'header' =>'Email',),
            array(
                'class' => 'bootstrap.widgets.TbButtonColumn',
                'template' => '{update}&nbsp;&nbsp;{delete}',
                'buttons' => array
                    (
                    'update' => array
                        (
                        'visible' => 'false',
                        'url' => 'Yii::app()->controller->createUrl("config/update",array("name"=>$data->email))',
                    ),
                    'delete' => array
                        (
                        'url' => 'Yii::app()->controller->createUrl("config/delete",array("id"=>$data->email_id))',
                    ),
                ),
            ),
        ),
            )
    );
    ?>
    <fieldset>


        <?php echo $form->errorSummary($model); ?>

        <?php echo $form->textFieldRow($model, 'email', array('class' => 'input-medium', 'prepend' => '<i class="icon-user"></i>', 'title'=>'Add a new administrative email')); ?>
    </fieldset>
    <div class="form-actions">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'label' => 'Submit')); ?>
    </div>


    <?php $this->endWidget(); ?>
</div><!-- form -->