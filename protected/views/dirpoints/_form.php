<?php
/* @var $this DirpointsController */
/* @var $model Dirpoints */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'dirpoints-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'prevId'); ?>
		<?php echo $form->textField($model,'prevId'); ?>
		<?php echo $form->error($model,'prevId'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'nextId'); ?>
		<?php echo $form->textField($model,'nextId'); ?>
		<?php echo $form->error($model,'nextId'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'directionId'); ?>
		<?php echo $form->textField($model,'directionId'); ?>
		<?php echo $form->error($model,'directionId'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>500)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->