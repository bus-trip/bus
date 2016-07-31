<?php
/**
 * @var $model Profiles
 */
?>
<p class="note">Поля с <span class="required">*</span> являются обязательными.</p>

<?php echo $form->errorSummary($model); ?>
<?php if ($edit_bl) { ?>
	<fieldset>
		<div class="row">
			<?php echo $form->checkBox($model, 'black_list'); ?>
			<?php echo $form->labelEx($model, 'black_list', ['class' => 'inline']); ?>
			<?php echo $form->error($model, 'black_list'); ?>
		</div>
	</fieldset>
<?php } ?>

<div class="row">
	<?php echo $form->labelEx($model, 'last_name'); ?>
	<?php echo $form->textField($model, 'last_name', ['class' => "input-text"]); ?>
	<?php echo $form->error($model, 'last_name'); ?>
</div>

<div class="row">
	<?php echo $form->labelEx($model, 'name'); ?>
	<?php echo $form->textField($model, 'name', ['class' => "input-text"]); ?>
	<?php echo $form->error($model, 'name'); ?>
</div>

<div class="row">
	<?php echo $form->labelEx($model, 'middle_name'); ?>
	<?php echo $form->textField($model, 'middle_name', ['class' => "input-text"]); ?>
	<?php echo $form->error($model, 'middle_name'); ?>
</div>


<div class="row">
	<?php echo $form->labelEx($model, 'doc_type'); ?>
	<?php echo $form->dropDownList($model, 'doc_type', [Profiles::DOC_PASSPORT          => 'Паспорт',
														Profiles::DOC_BIRTH_CERTIFICATE => 'Свидетельство о рождении',
														Profiles::DOC_FOREIGN_PASSPORT  => 'Загран паспорт',
														Profiles::DOC_MILITARY_ID       => 'Военный билет'],
								   ['data-placeholder' => 'Выберите']); ?>
	<?php echo $form->error($model, 'doc_type'); ?>
</div>

<div class="row">
	<?php echo $form->labelEx($model, 'doc_num'); ?>
	<?php echo $form->textField($model, 'doc_num', ['class' => "input-text"]); ?>
	<?php echo $form->error($model, 'doc_num'); ?>
</div>

<div class="row">
	<?php echo $form->labelEx($model, 'phone'); ?>
	<?php echo $form->textField($model, 'phone', array('class' => 'phone input-text')); ?>
	<?php echo $form->error($model, 'phone'); ?>
</div>

<div class="row">
	<?php echo $form->labelEx($model, 'sex'); ?>
	<?php
	switch ($model->sex) {
		case 'Мужской':
			$model->sex = Profiles::SEX_MALE;
			break;
		case 'Женский':
			$model->sex = Profiles::SEX_FEMALE;
			break;
	}
	echo $form->dropDownList($model, 'sex',
							 ['none'               => '-Выберите-',
							  Profiles::SEX_MALE   => 'Мужской',
							  Profiles::SEX_FEMALE => 'Женский'],
							 ['data-placeholder' => 'Выберите']); ?>
	<?php echo $form->error($model, 'sex'); ?>
</div>

<div class="row">
	<?php echo $form->labelEx($model, 'birth'); ?>
	<?php $this->widget('zii.widgets.jui.CJuiDatePicker', [
		'model'       => $model,
		'attribute'   => 'birth',
		'language'    => 'ru',
		'options'     => [
			'altFormat' => 'd.m.Y',
		],
		'htmlOptions' => [
			'class' => 'input-text'
		]
	]) ?>
	<?php echo $form->error($model, 'birth'); ?>
</div>