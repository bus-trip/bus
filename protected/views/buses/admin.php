<?php
/* @var $this BusesController */
/* @var $model Buses */

$this->breadcrumbs = array(
	'Автобусы',
);

$this->menu = array(
	array('label' => 'Добавить автобус', 'url' => array('create')),
);
?>

<h2>Автобусы</h2>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'           => 'buses-grid',
	'dataProvider' => $modelData,
	'template'     => '{items}{pager}',
	'ajaxUpdate'   => FALSE,
	'columns'      => array(
		array(
			'name'   => 'id',
			'header' => '№',
		),
		array(
			'name'   => 'model',
			'header' => 'Модель',
		),
		array(
			'name'   => 'number',
			'header' => 'Гос.номер',
		),
		array(
			'name'   => 'places',
			'header' => 'Кол-во мест',
		),
		array(
			'name'   => 'plane',
			'header' => 'Схема',
			'type'   => 'raw',
			'value'  => '(!empty($data["plane"])) ? CHtml::image(Yii::app()->baseUrl . "/" . Buses::UPLOAD_DIR . "/" . $data["plane"],"",array("style"=>"width:50px; height:70px;")) : ""',
		),
		array(
			'name'   => 'description',
			'header' => 'Описание',
		),
		array(
			'name'   => 'status',
			'header' => 'Статус',
		),
		array(
			'header'             => 'Действия',
			'class'              => 'CButtonColumn',
			'deleteConfirmation' => 'Вы точно хотите снять автобус с рейсов?',
			//			'afterDelete'        => 'function(link,success,data){if(data == 0) $("#info").html("Нельзя снять с рейсов автобус, назначенный в рейс!");}',
			'template'           => '{update}&nbsp;&nbsp;&nbsp;{delete}',
			'buttons'            => array(
				'update' => array(
					'imageUrl' => Yii::app()->request->baseUrl . '/images/update.png',
					'url'      => 'Yii::app()->controller->createUrl("buses/update", array("id"=>$data["id"]))',
				),
				'delete' => array(
					'imageUrl' => Yii::app()->request->baseUrl . '/images/delete.png',
					'url'      => 'Yii::app()->controller->createUrl("buses/delete", array("id"=>$data["id"]))',
				)
			)
		),
	),
)); ?>
