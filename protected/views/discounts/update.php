<?php
/* @var $this DiscountsController */
/* @var $model Discounts */

$this->breadcrumbs=array(
	'Discounts'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Discounts', 'url'=>array('index')),
	array('label'=>'Create Discounts', 'url'=>array('create')),
	array('label'=>'View Discounts', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Discounts', 'url'=>array('admin')),
);
?>

<h1>Update Discounts <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>