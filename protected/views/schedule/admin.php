<?php
/* @var $this ScheduleController */
/* @var $model Schedule */

$this->breadcrumbs=array(
	'Расписания',
);

//$this->menu=array(
//	array('label'=>'List Schedule', 'url'=>array('index')),
//	array('label'=>'Create Schedule', 'url'=>array('create')),
//);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#schedule-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<!--<h2>Расписания</h2>-->

<?php //echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<!--<div class="search-form" style="display:none">-->
<?php //$this->renderPartial('_search',array(
//	'model'=>$model,
//)); ?>
<!--</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'schedule-grid',
	'dataProvider'=>$schData,
//	'filter'=>$model,
	'columns'=>array(
		'id',
		'startPoint',
		'endPoint',
		'departure',
		'arrival',
		array(
			'class'=>'CButtonColumn',
            'template'=>'{view}&nbsp;{update}&nbsp;{delete}',
            'buttons'=>array(
                'view'=>array(
                    'url'=>'Yii::app()->controller->createUrl("schedule/view", array("id"=>$data["id"]))',
                ),
                'update'=>array(
                    'url'=>'Yii::app()->controller->createUrl("schedule/update", array("id"=>$data["id"]))',
                ),
                'delete'=>array(
                    'url'=>'Yii::app()->controller->createUrl("schedule/delete", array("id"=>$data["id"]))',
                )
            )
		),
	),
)); ?>
