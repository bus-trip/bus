<?php
/* @var $this ScheduleController */
/* @var $model Schedule */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'schedule-schedule-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// See class documentation of CActiveForm for details on this,
	// you need to use the performAjaxValidation()-method described there.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'idTrip'); ?>
		<?php echo $form->textField($model,'idTrip'); ?>
		<?php echo $form->error($model,'idTrip'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'idDirection'); ?>
		<?php echo $form->textField($model,'idDirection'); ?>
		<?php echo $form->error($model,'idDirection'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'departure'); ?>
		<?php echo $form->textField($model,'departure'); ?>
		<?php echo $form->error($model,'departure'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'arrival'); ?>
		<?php echo $form->textField($model,'arrival'); ?>
		<?php echo $form->error($model,'arrival'); ?>
	</div>


	<div class="row buttons">
		<?php echo CHtml::submitButton('Submit'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->