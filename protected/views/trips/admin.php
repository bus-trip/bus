<?php
/* @var $this TripsController */
/* @var $model Trips */

$this->breadcrumbs=array(
    'Управление рейсами',
);

$this->menu=array(
    array('label'=>'Создать рейс', 'url'=>array('create')),
);

//Yii::app()->clientScript->registerScript('search', "
//$('.search-button').click(function(){
//	$('.search-form').toggle();
//	return false;
//});
//$('.search-form form').submit(function(){
//	$('#trips-grid').yiiGridView('update', {
//		data: $(this).serialize()
//	});
//	return false;
//});
//");
?>

<h1>Управление рейсами</h1>

<p>
    You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
    or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php //echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
    <?php
    //    $this->renderPartial('_search',array('model'=>$model,));
    ?>
</div><!-- search-form -->

<?php

$this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'trips-grid',
    'dataProvider'=>$tripsData,
//	'filter'=>$tripsData,
    'columns'=>array(
        array(
            'name'=>'id',
            'header'=>'ID',
        ),
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
            'class'=>'CButtonColumn',
            'template'=>'{update}&nbsp;&nbsp;&nbsp;{delete}',
            'buttons'=>array(
//                'view'=>array(
//                    'url'=>'Yii::app()->controller->createUrl("trips/view", array("id"=>$data["id"]))',
//                ),
                'update'=>array(
                    'url'=>'Yii::app()->controller->createUrl("trips/update", array("id"=>$data["id"]))',
                ),
                'delete'=>array(
                    'url'=>'Yii::app()->controller->createUrl("trips/delete", array("id"=>$data["id"]))',
                )
            )
        ),
    ),
));
?>
