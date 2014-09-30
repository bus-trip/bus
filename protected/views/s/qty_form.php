<div class="form">
	<?php $form = $this->beginWidget('CActiveForm', array(
		'enableAjaxValidation' => FALSE,
		'action'               => $this->createUrl('/s/trip')
	));
	echo $form->hiddenField($trip, 'id');
	?>

	<div class="row">
		<?php echo $form->label($trip, 'Количество'); ?>
		<?php echo $form->textField($trip, 'qty') ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Выбрать'); ?>
	</div>

	<?php $this->endWidget(); ?>
</div><!-- form -->