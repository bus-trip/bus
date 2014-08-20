<?php
/* @var $this DirectionsController */
/* @var $model Directions */

$this->breadcrumbs=array(
	'Directions'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Directions', 'url'=>array('index')),
	array('label'=>'Create Directions', 'url'=>array('create')),
	array('label'=>'View Directions', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Directions', 'url'=>array('admin')),
);
?>

<h1>Update Directions <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>