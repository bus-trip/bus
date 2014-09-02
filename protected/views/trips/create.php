<?php
/* @var $this TripsController */
/* @var $model Trips */

$this->breadcrumbs=array(
	'Trips'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Управление автобусами', 'url'=>array('admin')),
);
?>

<h1>Create Trips</h1>

<?php $this->renderPartial('_form', array('model'=>$model,'directions'=>$directions, 'buses'=>$buses)); ?>