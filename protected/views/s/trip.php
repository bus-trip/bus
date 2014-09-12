<div>Билет....</div>





<?php $form = $this->beginWidget('CActiveForm', array(
	'enableAjaxValidation' => FALSE,
	'action'               => $this->createUrl('/s/ticket')
));
?>

<div>Профили.....</div>


<div class="row buttons">
	<?php echo CHtml::submitButton('Бронировать'); ?>
</div>

<?php $this->endWidget(); ?>