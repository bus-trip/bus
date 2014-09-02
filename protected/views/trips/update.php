<?php
/* @var $this TripsController */
/* @var $model Trips */

$this->breadcrumbs=array(
	'Управление рейсами'=>array('admin'),
	'Редактирование',
);

$this->menu=array(
	array('label'=>'Управление рейсами', 'url'=>array('admin')),
);
?>

<h1>Редактирование рейса №<?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model,'directions'=>$directions,'buses'=>$buses)); ?>