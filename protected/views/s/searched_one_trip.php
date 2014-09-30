<div class="date">Отправление: <?php print cdate_format($trip->departure); ?></div>
<div class="date">Прибытие: <?php print cdate_format($trip->arrival); ?></div>
<?php if (!empty($trip->idBus0)) { ?>
	<div class="bus">Автобус: <?php print $trip->idBus0->model; ?>, гос. номер: <?php print $trip->idBus0->number; ?>,
		кол-во мест: <?php print $trip->idBus0->places; ?>
		<?php if (!empty($trip->idBus0->description)) { ?>
			<div class="bus-description"><?php print $trip->idBus0->description; ?></div>
		<?php } ?>
	</div>
<?php } ?>
<?php if (!empty($trip->idDirection0)) { ?>
	<div class="direction">
		Маршрут: <?php print $trip->idDirection0->startPoint; ?> - <?php print $trip->idDirection0->endPoint; ?>
		<?php if ($trip->idDirection0->parentId != 0) {
			$parent_direction = $trip->idDirection0->getParentDirection();
			?>
			<div class="main-direction">Направление: <?php print $parent_direction->startPoint; ?>
				- <?php print $parent_direction->endPoint; ?></div>
		<?php } ?>
	</div>
	<div class="price">Цена билета: <?php print $trip->idDirection0->price; ?> руб.</div>

<?php } ?>
<?php if (!empty($trip->description)) { ?>
	<div class="description"><?php print $trip->description; ?></div>
<?php } ?>
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