<?php
/* @var $this BusesController */
/* @var $model Buses */

$this->breadcrumbs=array(
	'Автобусы',
);

$this->menu=array(
	array('label'=>'Добавить автобус', 'url'=>array('create')),
);
?>

<h2>Автобусы</h2>



<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'buses-grid',
	'dataProvider'=>$modelData,
	'columns'=>array(
		array(
            'name' => 'id',
            'header' => '№',
        ),
        array(
            'name' =>'model',
            'header' => 'Модель',
        ),
		array(
            'name' => 'number',
            'header' => 'Гос.номер',
        ),
		array(
            'name' => 'places',
            'header' => 'Кол-во мест',
        ),
        array(
            'name' => 'description',
            'header' => 'Описание',
        ),
        array(
            'name' => 'status',
            'header' => 'Статус',
        ),
		array(
            'header' => 'Действия',
            'class'=>'CButtonColumn',
            'template'=>'{update}&nbsp;&nbsp;&nbsp;{delete}',
            'buttons'=>array(
                'update'=>array(
                    'url'=>'Yii::app()->controller->createUrl("buses/update", array("id"=>$data["id"]))',
                ),
                'delete'=>array(
                    'url'=>'Yii::app()->controller->createUrl("buses/delete", array("id"=>$data["id"]))',
                )
            )
		),
	),
)); ?>
