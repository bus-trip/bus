<?php
/* @var $this DiscountsController */
/* @var $model Discounts */

$this->breadcrumbs=array(
	'Discounts'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Discounts', 'url'=>array('index')),
	array('label'=>'Manage Discounts', 'url'=>array('admin')),
);
?>

<h1>Create Discounts</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>