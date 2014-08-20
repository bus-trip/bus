<?php
/* @var $this TicketsController */
/* @var $model Tickets */
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
		<?php echo $form->label($model,'idPassenger'); ?>
		<?php echo $form->textField($model,'idPassenger'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'idDirection'); ?>
		<?php echo $form->textField($model,'idDirection'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'idTrip'); ?>
		<?php echo $form->textField($model,'idTrip'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'idBus'); ?>
		<?php echo $form->textField($model,'idBus'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'place'); ?>
		<?php echo $form->textField($model,'place'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->