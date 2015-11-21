<?php
/* @var $this BusesController */
/* @var $data Buses */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('buses/view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('number')); ?>:</b>
	<?php echo CHtml::encode($data->number); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('model')); ?>:</b>
	<?php echo CHtml::encode($data->places); ?>
	<br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('places')); ?>:</b>
    <?php echo CHtml::encode($data->places); ?>
    <br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('plane')); ?>:</b>
	<?php echo CHtml::encode($data->plane); ?>
	<br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
    <?php echo CHtml::encode($data->places); ?>
    <br />


</div>