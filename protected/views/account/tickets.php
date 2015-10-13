<?php
/**
 * Part of bus 2015
 * Created by: Александр on 12.07.2015:12:23
 *
 * @var $dataProvider CDataProvider
 */

$this->widget('zii.widgets.grid.CGridView', array(
	'id'           => 'user-auth-data-grid',
	'dataProvider' => $modelData,
	'template'     => '{items}{pager}',
	'columns'      => array(
		array(
			'name'   => 'name',
			'header' => 'Имя',
		),
		array(
			'name'   => 'place',
			'header' => 'Место',
		),
		array(
			'name'   => 'price',
			'header' => 'Цена',
		),
		array(
			'name'   => 'status',
			'header' => 'Статус',
		),
		array(
			'name'   => 'address_from',
			'header' => 'Выезд',
		),
		array(
			'name'   => 'address_to',
			'header' => 'Приезд',
		),
		array(
			'name'   => 'departure',
			'header' => 'Время отъезда',
		),
		array(
			'name'   => 'arrival',
			'header' => 'Время прибытия',
		),
		array(
			'name'   => 'startPoint',
			'header' => 'Маршрут от',
		),
		array(
			'name'   => 'endPoint',
			'header' => 'Маршрут до',
		),

	),
));