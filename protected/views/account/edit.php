<?php
/* @var $this AccountController */

$this->breadcrumbs = array(
	'Аккаунт' => array('/account'),
	'Редактирование',
);
?>

<div class="form">

	<?php $form = $this->beginWidget('CActiveForm', array(
		'id'                   => 'user-edit-form',
		// Please note: When you enable ajax validation, make sure the corresponding
		// controller action is handling ajax validation correctly.
		// See class documentation of CActiveForm for details on this,
		// you need to use the performAjaxValidation()-method described there.
		'enableAjaxValidation' => FALSE,
	)); ?>

	<p class="note">Поля с <span class="required">*</span> являются обязательными.</p>

	<?php echo $form->errorSummary($model); ?>

	<fieldset>
		<label>Изменение пароля</label>

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

	</fieldset>
	<div class="row">
		<?php echo $form->labelEx($model, 'mail'); ?>
		<?php echo $form->textField($model, 'mail'); ?>
		<?php echo $form->error($model, 'mail'); ?>
	</div>


	<div class="row buttons">
		<?php echo CHtml::submitButton('Submit'); ?>
	</div>

	<?php $this->endWidget(); ?>

</div><!-- form -->