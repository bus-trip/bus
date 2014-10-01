<div class="item-profile form">

	<?php $form = $this->beginWidget('CActiveForm', array(
		'id'                   => 'profiles-one_passager-form',
		// Please note: When you enable ajax validation, make sure the corresponding
		// controller action is handling ajax validation correctly.
		// See class documentation of CActiveForm for details on this,
		// you need to use the performAjaxValidation()-method described there.
		'enableAjaxValidation' => FALSE,
	)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

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


	<div class="row">
		<div class="passport-input">
			<label for="user_passport1" class="required">Серия <span class="required">*</span></label>
			<input class="code" type="text" name="user_passport1" id="" maxlength="4" size="4"/>
			<label for="user_passport2" class="required">Номер <span class="required">*</span></label>
			<input class="number" type="text" name="user_passport2" id="" maxlength="6" size="6"/>

			<?php echo $form->hiddenField($model, 'passport', array('class' => 'code_number')); ?>
		</div>
		<?php echo $form->error($model, 'passport'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'phone'); ?>
		<?php echo $form->textField($model, 'phone', array('class' => 'phone')); ?>
		<?php echo $form->error($model, 'phone'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'address'); ?>
		<?php echo $form->textField($model, 'address'); ?>
		<?php echo $form->error($model, 'address'); ?>
	</div>

	<div class="row">
		<?php
		$sex = array('none' => '-Выберите-', 0 => 'Мужской', 1 => 'Женский');
		$model->sex = array_keys($sex, $model->sex);
		?>
		<?php echo $form->labelEx($model, 'sex'); ?>
		<?php echo $form->dropDownList($model, 'sex', $sex); ?>
		<?php echo $form->error($model, 'sex'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'birth'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
			'model'     => $model,
			'attribute' => 'birth',
			'language'  => 'ru',
			'options'   => array(
				'altFormat' => 'd.m.Y',
			)
		)) ?>
		<?php echo $form->error($model, 'birth'); ?>
	</div>

	<?php if (!$trip) { ?>
		<div class="row buttons">
			<?php echo CHtml::submitButton('Submit'); ?>
		</div>
	<?php } ?>

	<?php $this->endWidget(); ?>

</div><!-- form -->