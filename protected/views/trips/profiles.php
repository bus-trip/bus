<h1>Profiles</h1>

<?php

$this->widget('zii.widgets.grid.CGridView', array(
	'id'           => 'user-auth-data-grid',
	'dataProvider' => $model->search(),
	'filter'       => $model,
	'columns'      => array(
		array(
			'name'   => 'id',
			'header' => 'ID',
		),
		array(
			'name'   => 'uid',
			'header' => 'UserID',
		),
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
					//					'url'      => 'Yii::app()->controller->createUrl("trips/sheet/' . $dataHeader['trips']['id'] . '/$data[place]")'
				)
			)
		)
	)
)); ?>