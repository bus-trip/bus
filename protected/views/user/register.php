<?php
/* @var $this UsersController */
/* @var $model Users */
/* @var $form CActiveForm */
?>

<div class="form">

	<?php $form = $this->beginWidget('CActiveForm', array(
		'id'                   => 'users-register-form',
		'enableAjaxValidation' => TRUE,
		'clientOptions'        => array(
			'validateOnSubmit' => TRUE,
			'validateOnChange' => TRUE,
			'validateOnType'   => FALSE,
		),
	)); ?>

	<p class="note">Поля с <span class="required">*</span> обязательны.</p>

	<?php echo $form->errorSummary($model); ?>

	<fieldset>
		<label>Данные профиля для входа на сайт</label>

		<div class="row">
			<?php echo $form->labelEx($model, 'mail'); ?>
			<?php echo $form->textField($model, 'mail'); ?>
			<?php echo $form->error($model, 'mail'); ?>
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
	</fieldset>


	<fieldset>
		<label>Личные данные</label>

		<div class="row">
			<?php echo $form->labelEx($model, 'last_name'); ?>
			<?php echo $form->textField($model, 'last_name'); ?>
			<?php echo $form->error($model, 'last_name'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model, 'name'); ?>
			<?php echo $form->textField($model, 'name'); ?>
			<?php echo $form->error($model, 'name'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model, 'middle_name'); ?>
			<?php echo $form->textField($model, 'middle_name'); ?>
			<?php echo $form->error($model, 'middle_name'); ?>
		</div>

		<fieldset class="row">
			<label>Паспортные данные</label>

			<div class="passport-input">
				<label for="user_passport1" class="required">Серия <span class="required">*</span></label>
				<input class="code" type="text" name="user_passport1" id="" maxlength="4" size="4"/>
				<label for="user_passport2" class="required">Номер <span class="required">*</span></label>
				<input class="number" type="text" name="user_passport2" id="" maxlength="6" size="6"/>

				<?php echo $form->hiddenField($model, 'passport', array('class' => 'code_number')); ?>
			</div>

			<?php echo $form->error($model, 'passport'); ?>
		</fieldset>

		<div class="row">
			<?php echo $form->labelEx($model, 'phone'); ?>
			<div class="phone-input">
				+7 (<input class="code" type="text" name="user_phone1" id="" maxlength="3" size="3"/>)
				<input class="number" type="text" name="user_phone2" id="" maxlength="7" size="7"/>
				<?php echo $form->hiddenField($model, 'phone', array('class' => 'code_number')); ?>
			</div>
			<?php echo $form->error($model, 'phone'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model, 'sex'); ?>
			<?php echo $form->radioButtonList($model, 'sex', array('Мужской', 'Женский')); ?>
			<?php echo $form->error($model, 'sex'); ?>
		</div>


		<div class="row">
			<?php echo $form->labelEx($model, 'birth'); ?>
			<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
				'name'     => 'Users[birth]',
				'language' => 'ru',
				'options'  => array(
					'altFormat' => 'd.m.Y'
				)
			)) ?>
			<?php echo $form->error($model, 'birth'); ?>
		</div>

	</fieldset>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Submit'); ?>
	</div>

	<?php $this->endWidget(); ?>

</div><!-- form -->