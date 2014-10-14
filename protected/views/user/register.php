<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */
?>

<div class="form">

	<?php $form = $this->beginWidget('CActiveForm', array(
		'id'                   => 'user-register-form',
		// Please note: When you enable ajax validation, make sure the corresponding
		// controller action is handling ajax validation correctly.
		// See class documentation of CActiveForm for details on this,
		// you need to use the performAjaxValidation()-method described there.
		'enableAjaxValidation' => FALSE,
	)); ?>

	<p class="note">Поля с <span class="required">*</span> являются обязательными.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model, 'login'); ?>
		<?php echo $form->textField($model, 'login'); ?>
		<?php echo $form->error($model, 'login'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'pass'); ?>
		<?php echo $form->passwordField($model, 'pass'); ?>
		<?php echo $form->error($model, 'pass'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'pass2'); ?>
		<?php echo $form->passwordField($model, 'pass2'); ?>
		<?php echo $form->error($model, 'pass2'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'mail'); ?>
		<?php echo $form->textField($model, 'mail'); ?>
		<?php echo $form->error($model, 'mail'); ?>
	</div>

	<div class="row rememberMe">
		<?php echo $form->checkBox($model, 'rememberMe', array('checked' => 'checked')); ?>
		<?php echo $form->label($model, 'rememberMe'); ?>
		<?php echo $form->error($model, 'rememberMe'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Submit'); ?>
	</div>

	<?php $this->endWidget(); ?>

</div><!-- form -->