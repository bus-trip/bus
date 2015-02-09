<?php
/* @var $this TripsController */
/* @var $model Trips */
/* @var $form CActiveForm */
?>

<div class="form">

	<?php $form = $this->beginWidget('CActiveForm', array(
		'id'                   => 'trips-form',
		// Please note: When you enable ajax validation, make sure the corresponding
		// controller action is handling ajax validation correctly.
		// There is a call to performAjaxValidation() commented in generated controller code.
		// See class documentation of CActiveForm for details on this.
		'enableAjaxValidation' => FALSE,
	)); ?>

	<!--	<p class="note">Fields with <span class="required">*</span> are required.</p>-->

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php
		if (isset($directions)) {
			echo $form->labelEx($model, 'Направление');
			if (!$actual) echo $form->dropDownList($model, 'idDirection', $directions, array('disabled' => 'disabled'));
			else echo $form->dropDownList($model, 'idDirection', $directions);
			echo $form->error($model, 'idDirection');
		}
		?>
	</div>

	<div class="row">
		<?php
		if (isset($buses)) {
			echo $form->labelEx($model, 'Автобус');
			if (!$actual) echo $form->dropDownList($model, 'idBus', $buses, array('disabled' => 'disabled'));
			else echo $form->dropDownList($model, 'idBus', $buses);
			echo $form->error($model, 'idBus');
		}
		?>
	</div>

	<div class="row">
		<?php
		echo $form->labelEx($model, 'Отправление');
		Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
		$this->widget('CJuiDateTimePicker', array(
			'model'     => $model,
			'attribute' => 'departure',
			'mode'      => 'datetime',
			'options'   => array(
				'dateFormat'  => 'yy-mm-dd', //'dd.mm.yy',
				'timeFormat'  => 'hh:mm:ss',
				'changeMonth' => TRUE,
				'changeYear'  => TRUE,
				'minDate'     => 0,
				'disabled'    => !$actual ? TRUE : FALSE,
			),
			'language'  => 'ru',
		));
		echo $form->error($model, 'departure');
		?>
	</div>

	<div class="row">
		<?php
		echo $form->labelEx($model, 'Прибытие');
		Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
		$this->widget('CJuiDateTimePicker', array(
			'model'     => $model,
			'attribute' => 'arrival',
			'mode'      => 'datetime',
			'options'   => array(
				'dateFormat'  => 'yy-mm-dd', //'dd.mm.yy',
				'timeFormat'  => 'hh:mm:ss',
				'changeMonth' => TRUE,
				'changeYear'  => TRUE,
				'minDate'     => 0,
				'disabled'    => !$actual ? TRUE : FALSE,
			),
			'language'  => 'ru',
		));
		echo $form->error($model, 'arrival');
		?>
	</div>

	<div class="row">
		<?php
		echo $form->labelEx($model, 'Описание');
		if (!$actual) echo $form->textField($model, 'description', array('disabled' => 'disabled'));
		else echo $form->textField($model, 'description');
		echo $form->error($model, 'description');
		?>
	</div>

	<div class="row">
		<?php
		echo $form->labelEx($model, 'Статус рейса');
		echo $form->dropDownList(
			$model,
			'status',
			array(
				DIRTRIP_MAIN     => 'Актуальный',
				DIRTRIP_EXTEND   => 'Дополнительный',
				DIRTRIP_CANCELED => 'Неактуальный',
			),
			array(
				'options' => array(
					DIRTRIP_EXTEND => array(
						'selected' => FALSE
					),
					DIRTRIP_MAIN   => array(
						'selected' => FALSE
					),
					DIRTRIP_CANCELED   => array(
						'selected' => FALSE
					),
					$actual => array(
						'selected' => TRUE
					),
				)
			)
		);
		echo $form->error($model, 'status');
		?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Обновить'); ?>
	</div>

	<?php $this->endWidget(); ?>

</div><!-- form -->