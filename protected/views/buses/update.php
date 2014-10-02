<?php
/* @var $this BusesController */
/* @var $model Buses */

$this->breadcrumbs=array(
	'Автобусы'=>array('admin'),
	'Редактирование',
);

$this->menu=array(
	array('label'=>'Добавить автобус', 'url'=>array('create')),
	array('label'=>'Управление автобусами', 'url'=>array('admin')),
);
?>

<h2><?php echo $model->model.", госномер: ".$model->number; ?></h2>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>