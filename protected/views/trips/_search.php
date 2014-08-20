<?php
/* @var $this TripsController */
/* @var $model Trips */
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
		<?php echo $form->label($model,'idDirection'); ?>
		<?php echo $form->textField($model,'idDirection'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'idBus'); ?>
		<?php echo $form->textField($model,'idBus'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'departure'); ?>
		<?php echo $form->textField($model,'departure'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->