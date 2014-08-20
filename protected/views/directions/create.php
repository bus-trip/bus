<?php
/* @var $this DirectionsController */
/* @var $model Directions */

$this->breadcrumbs=array(
	'Directions'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Directions', 'url'=>array('index')),
	array('label'=>'Manage Directions', 'url'=>array('admin')),
);
?>

<h1>Create Directions</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>