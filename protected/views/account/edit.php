<?php
/**
 * @var $this AccountController
 * @var $model User
 */
?>

<div class="form">
	<?php $form = $this->beginWidget('CActiveForm', [
		'id'                   => 'user-edit-form',
		'enableAjaxValidation' => false,
	]); ?>

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
		<?php echo CHtml::submitButton('Сохранить'); ?>
	</div>

	<?php $this->endWidget(); ?>

</div><!-- form -->