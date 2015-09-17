<?php
/**
 * @var $model Profiles
 */
?>
<p class="note">Поля с <span class="required">*</span> являются обязательными.</p>

<?php echo $form->errorSummary($model); ?>

<fieldset>
	<div class="row">
		<?php echo $form->checkBox($model, 'black_list'); ?>
		<?php echo $form->labelEx($model, 'black_list', array('class' => 'inline')); ?>
		<?php echo $form->error($model, 'black_list'); ?>
	</div>
</fieldset>

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
	<?php echo $form->labelEx($model, 'doc_type'); ?>
	<?php echo $form->dropDownList($model, 'doc_type', [Profiles::DOC_PASSPORT          => 'Паспорт',
														Profiles::DOC_BIRTH_CERTIFICATE => 'Свидетельство о рождении',
														Profiles::DOC_FOREIGN_PASSPORT  => 'Загран паспорт',
														Profiles::DOC_MILITARY_ID       => 'Военный билет']); ?>
	<?php echo $form->error($model, 'doc_type'); ?>
</div>

<div class="row">
	<?php echo $form->labelEx($model, 'doc_num'); ?>
	<?php echo $form->textField($model, 'doc_num'); ?>
	<?php echo $form->error($model, 'doc_num'); ?>
</div>

<div class="row">
	<?php echo $form->labelEx($model, 'phone'); ?>
	<?php echo $form->textField($model, 'phone', array('class' => 'phone')); ?>
	<?php echo $form->error($model, 'phone'); ?>
</div>

<div class="row">
	<?php echo $form->labelEx($model, 'sex'); ?>
	<?php echo $form->dropDownList($model, 'sex', array('none' => '-Выберите-', '0' => 'Мужской', '1' => 'Женский')); ?>
	<?php echo $form->error($model, 'sex'); ?>
</div>

<div class="row">
	<?php echo $form->labelEx($model, 'birth'); ?>
	<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
		'model'     => $model,
		'attribute' => 'birth',
		'language'  => 'ru',
		'options'   => [
			'altFormat' => 'd.m.Y',
		]
	]) ?>
	<?php echo $form->error($model, 'birth'); ?>
</div>