<?php
/* @var $this DirpointsController */
/* @var $model Dirpoints */

$this->breadcrumbs=array(
	'Dirpoints'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Dirpoints', 'url'=>array('index')),
	array('label'=>'Create Dirpoints', 'url'=>array('create')),
	array('label'=>'Update Dirpoints', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Dirpoints', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Dirpoints', 'url'=>array('admin')),
);
?>

<h1>View Dirpoints #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'prevId',
		'nextId',
		'directionId',
		'name',
	),
)); ?>
