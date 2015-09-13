<?php
/* @var $this PassengersController */
/* @var $model Passengers */
/* @var $form CActiveForm */
?>

<div class="wide form">

	<?php $form = $this->beginWidget('CActiveForm', array(
		'action' => Yii::app()->createUrl($this->route),
		'method' => 'get',
	)); ?>

	<div class="row">
		<?php echo $form->label($model, 'id'); ?>
		<?php echo $form->textField($model, 'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'name'); ?>
		<?php echo $form->textField($model, 'name', array('size' => 60, 'maxlength' => 255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'birthdate'); ?>
		<?php echo $form->textField($model, 'birthdate'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'sex'); ?>
		<?php echo $form->textField($model, 'sex'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'doc_type'); ?>
		<?php echo $form->dropDownList($model, 'doc_type', [Profiles::DOC_PASSPORT          => 'Паспорт',
															Profiles::DOC_BIRTH_CERTIFICATE => 'Свидетельство о рождении']); ?>
		<?php echo $form->error($model, 'doc_type'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'doc_num'); ?>
		<?php echo $form->textField($model, 'doc_num'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'phone'); ?>
		<?php echo $form->textField($model, 'phone', array('size' => 15, 'maxlength' => 15)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'email'); ?>
		<?php echo $form->textField($model, 'email', array('size' => 60, 'maxlength' => 255)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

	<?php $this->endWidget(); ?>

</div><!-- search-form -->