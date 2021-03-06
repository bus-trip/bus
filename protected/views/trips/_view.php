<?php
/* @var $this TripsController */
/* @var $data Trips */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('idDirection')); ?>:</b>
	<?php echo CHtml::encode($data->idDirection); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('idBus')); ?>:</b>
	<?php echo CHtml::encode($data->idBus); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('departure')); ?>:</b>
	<?php echo CHtml::encode($data->departure); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('arrival')); ?>:</b>
	<?php echo CHtml::encode($data->arrival); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
	<?php echo CHtml::encode($data->description); ?>
	<br />


</div>