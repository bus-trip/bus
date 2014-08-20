<?php
/* @var $this DirectionsController */
/* @var $data Directions */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('directions/view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('parentId')); ?>:</b>
	<?php echo CHtml::encode($data->parentId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('startPoint')); ?>:</b>
	<?php echo CHtml::encode($data->startPoint); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('endPoint')); ?>:</b>
	<?php echo CHtml::encode($data->endPoint); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('price')); ?>:</b>
	<?php echo CHtml::encode($data->price); ?>
	<br />

</div>