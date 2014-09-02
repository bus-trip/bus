<?php
/* @var $this ScheduleController */
/* @var $model Schedule */

$this->breadcrumbs=array(
	'Расписание',
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

<h1>Manage Schedules</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

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
            'template'=>'{view}&nbsp;&nbsp;{update}&nbsp;&nbsp;{delete}',
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
