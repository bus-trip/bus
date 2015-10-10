<?php
/* @var $this DirpointsController */
/* @var $model Dirpoints */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'prevId'); ?>
		<?php echo $form->textField($model,'prevId'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'nextId'); ?>
		<?php echo $form->textField($model,'nextId'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'directionId'); ?>
		<?php echo $form->textField($model,'directionId'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>500)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->