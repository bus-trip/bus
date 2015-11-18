<?php
/**
 * Part of bus 2015
 * Created by: Alexander Sumarokov on 21.09.2015:21:34
 */

$this->widget('zii.widgets.grid.CGridView', array(
	'id'           => 'trips-grid',
	'dataProvider' => $trips,
	'template'     => '{items}{pager}',
	'ajaxUpdate'   => false,
	'columns'      => array(
		array(
			'header' => '',
			'name'   => 'id',
			'type'   => 'raw',
			'value'  => 'CHtml::radioButton("' . CHtml::modelName($model) . '[tripId]",false, array("value" => $data["id"], "onClick" => "document.getElementById(\'tripsSelect\').removeAttribute(\'disabled\')"))."<br/>".CHtml::hiddenField("' . CHtml::modelName($model) . '[idDirection]", $data["idDirection"])',
		),
		array(
			'name'   => 'trip',
			'header' => 'Маршрут следования',
		),
		array(
			'name'   => 'direction',
			'header' => 'Направление',
		),
		array(
			'name'   => 'departure',
			'header' => 'Отправление',
		),
		array(
			'name'   => 'arrival',
			'header' => 'Прибытие',
		),
		array(
			'name'   => 'price',
			'header' => 'Стоимость',
		),
		array(
			'name'   => 'places',
			'header' => 'Свободных мест',
		),
	),
));

echo CHtml::submitButton('Выбрать', ['id' => 'tripsSelect', 'name' => 'tripsSelect', 'disabled' => 'disabled']);