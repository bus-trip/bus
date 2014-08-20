<?php
/* @var $this TripsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Trips',
);

$this->menu=array(
	array('label'=>'Create Trips', 'url'=>array('create')),
	array('label'=>'Manage Trips', 'url'=>array('admin')),
);
?>

<h1>Trips</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
