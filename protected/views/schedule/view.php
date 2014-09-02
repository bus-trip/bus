<?php
/* @var $this ScheduleController */
/* @var $model Schedule */

$this->breadcrumbs=array(
	'Расписание'=>array('admin'),
	$model->id,
);

$this->menu=array(
	array('label'=>'Добавить участок в расписание', 'url'=>array('create')),
    array('label'=>'Управление расписанием маршрутов', 'url'=>array('admin')),
);
?>

<h1>
    Расписание маршрута <?php echo $schData->rawData[0]['startPoint']." - ".$schData->rawData[0]['endPoint']."."; ?>
    <br/>
</h1>
Отправление: <?php echo $schData->rawData[0]['departure']; ?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$schData,
	'columns'=>array(
		'id',
		'startPoint',
		'endPoint',
		'departure',
		'arrival',
        array(
            'class'=>'CButtonColumn',
            'template'=>'{update}&nbsp;&nbsp;&nbsp;&nbsp;{delete}',
            'buttons'=>array(
//                'view'=>array(
//                    'url'=>'Yii::app()->controller->createUrl("schedule/view", array("id"=>$data["id"]))',
//                ),
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
