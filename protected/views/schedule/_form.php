<?php
/* @var $this ScheduleController */
/* @var $model Schedule */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'schedule-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'Рейс'); ?>
		<?php //echo $form->textField($model,'idTrip'); ?>
        <?php echo $form->dropDownList($model,'idTrip',$trips['data'],$trips['selOptions']); ?>
		<?php echo $form->error($model,'idTrip'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Направление'); ?>
		<?php //echo $form->textField($model,'idDirection'); ?>
        <?php echo $form->dropDownList($model,'idDirection',$directions); ?>
		<?php echo $form->error($model,'idDirection'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Время отправления'); ?>
		<?php //echo $form->textField($model,'departure');
        Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
        $this->widget('CJuiDateTimePicker',array(
            'model'=>$model,
            'attribute'=>'departure',
            'mode'=>'datetime',
            'options'=>array(
                'dateFormat' => 'yy-mm-dd', //'dd.mm.yy',
                'timeFormat' => 'hh:mm:ss',
                'changeMonth' => true,
                'changeYear' => true,
            ),
            'language'=>'ru',
        ));
        ?>
		<?php echo $form->error($model,'departure'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Время прибытия'); ?>
		<?php //echo $form->textField($model,'arrival');
        Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
        $this->widget('CJuiDateTimePicker',array(
            'model'=>$model,
            'attribute'=>'arrival',
            'mode'=>'datetime',
            'options'=>array(
                'dateFormat' => 'yy-mm-dd', //'dd.mm.yy',
                'timeFormat' => 'hh:mm:ss',
                'changeMonth' => true,
                'changeYear' => true,
            ),
            'language'=>'ru',
        ));
        ?>
		<?php echo $form->error($model,'arrival'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->