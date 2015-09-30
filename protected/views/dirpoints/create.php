<?php
/* @var $this DirpointsController */
/* @var $model Dirpoints */

$this->breadcrumbs=array(
	'Dirpoints'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Dirpoints', 'url'=>array('index')),
	array('label'=>'Manage Dirpoints', 'url'=>array('admin')),
);
?>

<h1>Create Dirpoints</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>