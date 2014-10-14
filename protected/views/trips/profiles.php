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
$gtripId = $tripId;
$gplaceId = $placeId;
$this->widget('zii.widgets.grid.CGridView', array(
	'id'           => 'user-auth-data-grid',
	'dataProvider' => $model->searchWithGroupBy('passport'),
	'filter'       => $model,
	'columns'      => array(
//		array(
//			'name'   => 'id',
//			'header' => 'ID',
//		),
//		array(
//			'name'   => 'uid',
//			'header' => 'UserID',
//		),
//		array(
//			'name'   => 'tid',
//			'header' => 'TicketID',
//		),
		array(
			'name'   => 'last_name',
			'header' => 'Фамилия',
		),
		array(
			'name'   => 'name',
			'header' => 'Имя',
		),
		array(
			'name'   => 'middle_name',
			'header' => 'Отчество',
		),
		array(
			'name'   => 'passport',
			'header' => 'Серия и номер паспорта',
		),
		array(
			'name'   => 'phone',
			'header' => 'Телефон',
		),
		array(
			'name'   => 'sex',
			'header' => 'Пол',
		),
		array(
			'name'   => 'birth',
			'header' => 'Дата рождения',
		),
		array(
			'class'    => 'CButtonColumn',
			'template' => '{chose_profile}',
			'buttons'  => array(
				'chose_profile' => array(
					'label'    => 'Выбрать профиль',
					'imageUrl' => Yii::app()->request->baseUrl . '/images/update.png',
					'url'      => function ($data) {
						global $gtripId, $gplaceId;

						return Yii::app()->controller->createUrl('trips/sheet/' . $gtripId . '/' . $gplaceId . '/' . $data['id']);
					},
				)
			)
		)
	)
)); ?>

<p><a href="<?php print Yii::app()->controller->createUrl('trips/sheet/' . $tripId . '/' . $placeId . '/0'); ?>">
	Создать билет с новым профилем пассажира</a></p>