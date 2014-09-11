<?php
/* @var $this TripsController */
/* @var $model Trips */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'trips-search-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// See class documentation of CActiveForm for details on this,
	// you need to use the performAjaxValidation()-method described there.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>


	<div class="row">
		<label>Время отъезда</label>
			<?php echo $form->textField($model, 'tdeparture'); ?>
			<?php echo $form->error($model, 'tdeparture'); ?>
	</div>

	<div class="row">
		<label>Дата отъезда</label>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
			'model'     => $model,
			'attribute' => 'departure',
			'language'  => 'ru',
			'options'   => array(
				'altFormat' => 'd.m.Y',
			)
		)) ?>
		<?php echo $form->error($model,'departure'); ?>
	</div>



	<div class="row buttons">
		<?php echo CHtml::submitButton('Submit'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->