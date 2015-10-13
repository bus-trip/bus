<?php
/* @var $this DirectionsController */
/* @var $model Directions */

$this->breadcrumbs=array(
	'Направления'=>array('admin'),
	'Новое направление',
);

$this->menu=array(
	array('label'=>'Направления', 'url'=>array('admin')),
);
?>

<h2>Новое направление</h2>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>