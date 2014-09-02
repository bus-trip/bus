<?php
/* @var $this ScheduleController */
/* @var $data Schedule */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('idTrip')); ?>:</b>
	<?php echo CHtml::encode($data->idTrip); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('idDirection')); ?>:</b>
	<?php echo CHtml::encode($data->idDirection); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('departure')); ?>:</b>
	<?php echo CHtml::encode($data->departure); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('arrival')); ?>:</b>
	<?php echo CHtml::encode($data->arrival); ?>
	<br />


</div>