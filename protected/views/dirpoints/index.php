<?php
/* @var $this DirpointsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Dirpoints',
);

$this->menu=array(
	array('label'=>'Create Dirpoints', 'url'=>array('create')),
	array('label'=>'Manage Dirpoints', 'url'=>array('admin')),
);
?>

<h1>Dirpoints</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
