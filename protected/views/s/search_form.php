<?php
/* @var $this TripsController */
/* @var $model Trips */
/* @var $form CActiveForm */
?>

<div class="form">

	<?php $form = $this->beginWidget('CActiveForm', array(
		'id'                   => 'trips-search-form',
		'method'               => 'GET',
		// Please note: When you enable ajax validation, make sure the corresponding
		// controller action is handling ajax validation correctly.
		// See class documentation of CActiveForm for details on this,
		// you need to use the performAjaxValidation()-method described there.
		'enableAjaxValidation' => FALSE,
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
			'flat'      => 1,
			'attribute' => 'departure',
			'language'  => 'ru',
			'options'   => array(
				'altFormat' => 'd.m.Y',
			)
		))  ?>
		<?php echo $form->error($model, 'departure'); ?>
	</div>


	<div class="row">
		<label>Количество мест</label>
		<?php print $form->dropDownList($model, 'places', array(0, 1, 2, 3, 4)); ?>
		<?php echo $form->error($model, 'tdeparture'); ?>
	</div>

	<div class="row">
		<label>Пункт отправления</label>
		<?php $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
			'model'     => $model,
			'attribute' => 'startPoint',
			'source'    => array('ac1', 'ac2', 'ac3'),
			'source'    => Yii::app()->createUrl('s/startPoint'),
			'options'   => array(
				'showAnim' => 'fold',
			),
		));
		?>
		<?php echo $form->error($model, 'startPoint'); ?>

	</div>
	<div class="row">
		<label>Пункт назначения</label>
		<?php $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
			'model'     => $model,
			'attribute' => 'endPoint',
			'source'    => Yii::app()->createUrl('s/endPoint'),
			'options'   => array(
				'showAnim' => 'fold',
			),
		)); ?>
		<?php echo $form->error($model, 'endPoint'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Поиск'); ?>
	</div>

	<?php $this->endWidget(); ?>

</div><!-- form -->