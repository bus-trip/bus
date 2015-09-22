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
			'value'  => 'CHtml::radioButton("' . CHtml::modelName($model) . '[tripId]",false, array("value" => $data["id"], "onClick" => "document.getElementById(\'tripsSelect\').removeAttribute(\'disabled\')"))',
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
			'name'   => 'description',
			'header' => 'Описание',
		),
	),
));

echo CHtml::submitButton('Выбрать', ['id' => 'tripsSelect', 'name' => 'tripsSelect', 'disabled' => 'disabled']);