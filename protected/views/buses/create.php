<?php
/* @var $this BusesController */
/* @var $model Buses */

$this->breadcrumbs=array(
	'Автобусы'=>array('admin'),
	'Создать',
);

$this->menu=array(
	array('label'=>'Управление автобусами', 'url'=>array('admin')),
);
?>

<h2>Новый автобус</h2>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>