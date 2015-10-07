<?php
/* @var $this DirpointsController */
/* @var $data Dirpoints */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('prevId')); ?>:</b>
	<?php echo CHtml::encode($data->prevId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nextId')); ?>:</b>
	<?php echo CHtml::encode($data->nextId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('directionId')); ?>:</b>
	<?php echo CHtml::encode($data->directionId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />


</div>