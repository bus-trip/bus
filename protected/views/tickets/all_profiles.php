<?php
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
			'filter' => CHtml::dropDownList('Profiles[doc_type]', $model->doc_type, ['' => '-Выберите-', Profiles::DOC_PASSPORT => 'Паспорт', Profiles::DOC_BIRTH_CERTIFICATE => 'Свидетельство о рождении']),
		],
		[
			'name'   => 'doc_num',
			'header' => 'Номер документа',
			'type'   => 'raw',
			'value'  => 'isset($data["id"]) ? CHtml::link($data["doc_num"],array("tickets/profile/" . $data["id"])):""',
		],
		[
			'name'   => 'phone',
			'header' => 'Телефон',
		],
		[
			'name'   => 'sex',
			'header' => 'Пол',
			'filter' => CHtml::dropDownList('Profiles[sex]', $model->sex, ['' => '-Выберите-', 0 => 'Мужской', 1 => 'Женский']),
		],
		[
			'name'   => 'birth',
			'header' => 'Дата рождения',
		],
		[
			'name'   => 'black_list',
			'header' => 'BL',
			'filter' => CHtml::dropDownList('Profiles[black_list]', $model->black_list, ['' => '-Выберите-', 0 => 'Не в ЧС', 1 => 'В ЧС']),
			'value'  => 'isset($data["id"]) ? Profiles::getBlackList($data["black_list"]) : ""',
		],
		[
			'name'   => 'black_desc',
			'header' => 'Причина',
			'value'  => '!empty($data["black_desc"]) && $data["black_desc"] != "null" ? $data["black_desc"] : ""',
		]
	]
)); ?>