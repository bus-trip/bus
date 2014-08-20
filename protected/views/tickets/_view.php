<?php
/* @var $this TicketsController */
/* @var $data Tickets */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('idPassenger')); ?>:</b>
	<?php echo CHtml::encode($data->idPassenger); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('idDirection')); ?>:</b>
	<?php echo CHtml::encode($data->idDirection); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('idTrip')); ?>:</b>
	<?php echo CHtml::encode($data->idTrip); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('idBus')); ?>:</b>
	<?php echo CHtml::encode($data->idBus); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('place')); ?>:</b>
	<?php echo CHtml::encode($data->place); ?>
	<br />


</div>