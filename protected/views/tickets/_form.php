<?php
/* @var $this TicketsController */
/* @var $model Tickets */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'tickets-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'idPassenger'); ?>
		<?php echo $form->textField($model,'idPassenger'); ?>
		<?php echo $form->error($model,'idPassenger'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'idDirection'); ?>
		<?php echo $form->textField($model,'idDirection'); ?>
		<?php echo $form->error($model,'idDirection'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'idTrip'); ?>
		<?php echo $form->textField($model,'idTrip'); ?>
		<?php echo $form->error($model,'idTrip'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'idBus'); ?>
		<?php echo $form->textField($model,'idBus'); ?>
		<?php echo $form->error($model,'idBus'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'place'); ?>
		<?php echo $form->textField($model,'place'); ?>
		<?php echo $form->error($model,'place'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->