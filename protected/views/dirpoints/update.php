<?php
/* @var $this DirpointsController */
/* @var $model Dirpoints */

$this->breadcrumbs=array(
	'Dirpoints'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Dirpoints', 'url'=>array('index')),
	array('label'=>'Create Dirpoints', 'url'=>array('create')),
	array('label'=>'View Dirpoints', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Dirpoints', 'url'=>array('admin')),
);
?>

<h1>Update Dirpoints <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>