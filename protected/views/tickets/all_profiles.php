<?php
$this->widget('zii.widgets.grid.CGridView', array(
	'id'           => 'user-auth-data-grid',
	'dataProvider' => $model->searchWithGroupBy(array('passport', 'black_list')),
	'template'     => '{items}{pager}',
	'filter'       => $model,
	'columns'      => array(
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
			'type'   => 'raw',
			'value'  => 'isset($data["id"]) ? CHtml::link($data["passport"],array("tickets/profile/" . $data["id"])):""',
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
			'name'   => 'black_list',
			'header' => 'BL',
			'filter' => CHtml::dropDownList('Profiles[black_list]', $model->black_list, array('' => '-Выберите-', 0 => 'Не в ЧС', 1 => 'В ЧС')),
		)
	)
)); ?>