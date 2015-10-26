<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 18.09.2015
 * Time: 0:03
 */
/* @var $this DirectionsController */
/* @var $model Directions */

$this->breadcrumbs = array(
	'Направления' => array('admin'),
	'Редактирование направления',
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

	<h2>Направление: <?php echo $parent['startPoint'] . " - " . $parent['endPoint']; ?></h2>

<?php

$this->widget('zii.widgets.grid.CGridView', array(
	'id'           => 'dirpoints-grid',
	'dataProvider' => $dirPoints,
	'ajaxUpdate'   => FALSE,
	'columns'      => array(
		array(
			'name'   => 'name',
			'header' => 'Пункты остановок'
		),
		array(
			'header'   => 'Действия',
			'class'    => 'CButtonColumn',
			'template' => '{add}&nbsp;&nbsp;{update}&nbsp;&nbsp;{delete}',
			'buttons'  => array(
				'add'    => array(
					'imageUrl' => Yii::app()->request->baseUrl . '/images/add1.png',
					'url'      => 'Yii::app()->controller->createUrl(
																	"directions/addPoint",
																	array(
																		"id"=>$data["id"]
																	)
																)',
					'click'    => "js: function(){
										$('#pointFrame').load($(this).attr('href'));
										$('#pointDialog').dialog('open');
										return false;
									}",
					'visible'  => '$row!=' . ($dirPointsSize - 1)
				),
				'update' => array(
					'url'     => 'Yii::app()->controller->createUrl("directions/editPoint", array("id"=>$data["id"]))',
					'visible' => '$row!=' . ($dirPointsSize - 1) . ' && $row!=0',
					'click'   => "js: function(){
										$('#editPointFrame').load($(this).attr('href'));
										$('#editPointDialog').dialog('open');
										return false;
									}",
				),
				'delete' => array(
					'url'     => 'Yii::app()->controller->createUrl("directions/deletePoint", array("id"=>$data["id"]))',
					'visible' => '$row!=' . ($dirPointsSize - 1) . ' && $row!=0'
				)
			)
		)
	)
));

$this->widget('zii.widgets.grid.CGridView', array(
	'id'           => 'directions-grid',
	'dataProvider' => $modelData,//$model->search(),
	//	'filter'=>$model,
	'columns'      => array(
		array(
			'name'   => 'startPoint',
			'header' => 'Начальный пункт',
		),
		array(
			'name'   => 'endPoint',
			'header' => 'Конечный пункт',
		),
		array(
			'name'   => 'price',
			'header' => 'Стоимость',
		),
		array(
			'header'   => 'Действия',
			'class'    => 'CButtonColumn',
			'template' => '{update}',
			'buttons'  => array(
				'update' => array(
					'url' => 'Yii::app()->controller->createUrl("directions/update", array("id"=>$data["id"]))',
				),
			)
		),
	),
)); ?>

<?php

$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
	'id'      => 'pointDialog',
	'options' => array(
		'title'    => 'Новый пункт в направлении',
		'autoOpen' => FALSE,
		'modal'    => TRUE,
		'width'    => 620,
		'height'   => 190,
	),
));
?>
	<div id="pointFrame" width="100%" height="100%"></div>
<?php
$this->endWidget();

$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
	'id'      => 'editPointDialog',
	'options' => array(
		'title'    => 'Редактирование названия пункта',
		'autoOpen' => FALSE,
		'modal'    => TRUE,
		'width'    => 350,
		'height'   => 180,
	),
));
?>
	<div id="editPointFrame" width="100%" height="100%"></div>
<?php
$this->endWidget();
?>