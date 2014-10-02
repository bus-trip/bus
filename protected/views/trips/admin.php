<?php
/* @var $this TripsController */
/* @var $model Trips */

$this->breadcrumbs=array(
    'Рейсы',
);

$this->menu=array(
    array('label'=>'Создать рейс', 'url'=>array('create')),
    array('label'=>'Актуальные рейсы', 'url'=>array('trips/admin/status/actual')),
    array('label'=>'Неактуальные рейсы', 'url'=>array('trips/admin/status/noactual')),
    array('label'=>'Все рейсы', 'url'=>array('admin')),
);
?>

<!--<h1>Управление рейсами</h1>-->

<?php

$this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'trips-grid',
    'dataProvider'=>$tripsData,
    'columns'=>array(
        array(
            'name'=>'startPoint',
            'header'=>'Начальный пункт',
        ),
        array(
            'name'=>'endPoint',
            'header'=>'Конечный пункт',
        ),
        array(
            'name'=>'number',
            'header'=>'Номер автобуса',
        ),
        array(
            'name'=>'departure',
            'header'=>'Время отправления',
        ),
        array(
            'name'=>'arrival',
            'header'=>'Время прибытия',
        ),
        array(
            'name' => 'status',
            'header' => 'Статус',
        ),
        array(
            'header' => 'Действия',
            'class'=>'CButtonColumn',
            'template'=>'{sheet}&nbsp;{update}&nbsp;{delete}',
            'buttons'=>array(
                'sheet' => array(
                    'label' => 'Посадочная ведомость',
                    'imageUrl'=>Yii::app()->request->baseUrl.'/images/view.png',
                    'url' => 'Yii::app()->controller->createUrl("trips/sheet", array("id"=>$data["id"]))'
                ),
                'update'=>array(
                    'label' => 'Редактировать',
                    'url'=>'Yii::app()->controller->createUrl("trips/update", array("id"=>$data["id"]))',
                ),
                'delete'=>array(
                    'label' => 'Удалить',
                    'url'=>'Yii::app()->controller->createUrl("trips/delete", array("id"=>$data["id"]))',
                )
            )
        ),
    ),
));
?>
