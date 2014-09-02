<?php
/* @var $this TripsController */
/* @var $model Trips */

$this->breadcrumbs=array(
	'Trips'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Trips', 'url'=>array('index')),
	array('label'=>'Create Trips', 'url'=>array('create')),
	array('label'=>'Update Trips', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Trips', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Trips', 'url'=>array('admin')),
);
?>

<h1>View Trips #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'idDirection',
		'idBus',
		'departure',
		'arrival',
		'description',
	),
)); ?>
