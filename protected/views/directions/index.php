<?php
/* @var $this DirectionsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Directions',
);

$this->menu=array(
	array('label'=>'Create Directions', 'url'=>array('create')),
	array('label'=>'Manage Directions', 'url'=>array('admin')),
);
?>

<h1>Directions</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
