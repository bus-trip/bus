<?php

$this->breadcrumbs = array(
	'Рейсы' => array('admin'),
	'Посадочная ведомость',
);

$this->menu = array(
	array('label' => 'Рейсы', 'url' => array('admin')),
);

?>

<h1>Выбор пассажира</h1>

<?php
global $gtripId, $gplaceId;
$gtripId  = $tripId;
$gplaceId = $placeId;
$this->widget('zii.widgets.grid.CGridView', array(
	'id'           => 'user-auth-data-grid',
	'dataProvider' => $model->searchWithGroupBy(array('doc_type', 'doc_num', 'black_list')),
	'template'     => '{items}{pager}',
	'filter'       => $model,
	'columns'      => [
		[
			'name'   => 'last_name',
			'header' => 'Фамилия',
		],
		[
			'name'   => 'name',
			'header' => 'Имя',
		],
		[
			'name'   => 'middle_name',
			'header' => 'Отчество',
		],
		[
			'name'   => 'doc_type',
			'header' => 'Тип документа',
			'type'   => 'raw',
			'value'  => 'isset($data["id"]) ? Profiles::getDocType($data["doc_type"]) : ""',
		],
		[
			'name'   => 'doc_num',
			'header' => 'Номер документа',
		],
		[
			'name'   => 'phone',
			'header' => 'Телефон',
		],
		[
			'name'   => 'sex',
			'header' => 'Пол',
		],
		[
			'name'   => 'birth',
			'header' => 'Дата рождения',
		],
		[
			'name'   => 'black_list',
			'header' => 'BL',
		],
		[
			'class'    => 'CButtonColumn',
			'template' => '{chose_profile}',
			'buttons'  => [
				'chose_profile' => [
					'label'    => 'Выбрать профиль',
					'imageUrl' => Yii::app()->request->baseUrl . '/images/update.png',
					'url'      => function ($data) {
						global $gtripId, $gplaceId;

						return Yii::app()->controller->createUrl('trips/sheet/' . $gtripId . '/' . $gplaceId . '/' . $data['id']);
					},
				]
			]
		]
	]
)); ?>

<p><a href="<?php print Yii::app()->controller->createUrl('trips/sheet/' . $tripId . '/' . $placeId . '/0'); ?>">
		Создать билет с новым профилем пассажира</a></p>