<?php var_dump($trip->id); ?>

<div>18:20</div>
<div>Автобус: гос. номер: 123, volkswagen, есть wi-fy</div>
<div>Направление СПб - Хельсинки - СПб</div>
<div>Прибытие: 29.08.2014 - 18:10</div>
<div>Количество свободных мест: 10</div>
<div>
	<?php $form = $this->beginWidget('CActiveForm', array(
		'enableAjaxValidation' => FALSE,
		'action'               => $this->createUrl('/s/trip')
	));
	echo $form->hiddenField($trip, 'id');
	?>

	<div class="row">
		<?php echo $form->label($trip, 'qty'); ?>
		<?php echo $form->textField($trip, 'qty') ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Выбрать'); ?>
	</div>

	<?php $this->endWidget(); ?>
</div><!-- form -->