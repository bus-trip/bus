<?php
/* @var $this DirectionsController */
/* @var $model Directions */

$this->breadcrumbs=array(
	'Directions'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Directions', 'url'=>array('index')),
	array('label'=>'Create Directions', 'url'=>array('create')),
	array('label'=>'Update Directions', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Directions', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Directions', 'url'=>array('admin')),
);
?>

<h1>View Directions #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'parentId',
		'startPoint',
		'endPoint',
		'price',
	),
)); ?>
