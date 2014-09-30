<div class="itrip">
	<h2>Билет</h2>
	<?php print $trip; ?>

	<div class="qty">
		Количество мест: <?php print $qty; ?>
	</div>
</div>

<?php $form = $this->beginWidget('CActiveForm', array(
	'enableAjaxValidation' => FALSE,
	'action'               => $this->createUrl('/s/ticket')
));
?>

<div class="profiles">
	<?php print $chose_profile; ?>
</div>


<div class="row buttons">
	<?php echo CHtml::submitButton('Бронировать'); ?>
</div>

<?php $this->endWidget(); ?>