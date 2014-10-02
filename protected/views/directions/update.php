<?php
/* @var $this DirectionsController */
/* @var $model Directions */

$this->breadcrumbs=array(
	'Направления'=>array('admin'),
	'Редактирование',
);

$this->menu=array(
	array('label'=>'Новое направление', 'url'=>array('create')),
	array('label'=>'Направления', 'url'=>array('admin')),
);
?>

<h2>Редактирование направления "<?php echo $model->startPoint.' - '.$model->endPoint; ?>"</h2>

<?php $this->renderPartial('_form', array('model'=>$model, 'parentDir'=>$parentDir)); ?>