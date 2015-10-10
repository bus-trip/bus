<?php
/* @var $this DirectionsController */
/* @var $model Directions */

$this->breadcrumbs=array(
	'Направления'
);

$this->menu=array(
	array('label'=>'Новое направление', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#directions-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h2>Направления</h2>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'main-directions-grid',
	'dataProvider'=>$modelData,//$model->search(),
//	'filter'=>$model,
	'columns'=>array(
//		array(
//            'name'=>'id',
//            'header'=>'№',
//        ),
		array(
            'name'=>'parentId',
            'header'=>'Рейс',
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
            'name'=>'price',
            'header'=>'Стоимость',
        ),
		array(
            'header'=>'Действия',
			'class'=>'CButtonColumn',
            'template'=>'{update}&nbsp;&nbsp;{delete}',
            'buttons'=>array(
				'update'=>array(
					'url'=>'Yii::app()->controller->createUrl("directions/edit", array("id"=>$data["id"]))',
				),
                'delete'=>array(
                    'url'=>'Yii::app()->controller->createUrl("directions/delete", array("id"=>$data["id"]))',
                )
            )
		),
	),
)); ?>
