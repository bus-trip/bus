<?php
/**
 * Part of bus 2015
 * Created by: Александр on 12.07.2015:12:23
 *
 * @var $dataProvider CDataProvider
 */

$this->widget('zii.widgets.grid.CGridView', [
	'id'           => 'user-auth-data-grid',
	'dataProvider' => $modelData,
	'template'     => '{items}{pager}',
	'columns'      => [
		[
			'name'   => 'name',
			'header' => 'Имя',
		],
		[
			'name'   => 'place',
			'header' => 'Место',
		],
		[
			'name'   => 'price',
			'header' => 'Цена',
		],
		[
			'name'   => 'status',
			'header' => 'Статус',
		],
		[
			'name'   => 'address_from',
			'header' => 'Выезд',
		],
		[
			'name'   => 'address_to',
			'header' => 'Приезд',
		],
		[
			'name'   => 'departure',
			'header' => 'Время отъезда',
		],
		[
			'name'   => 'arrival',
			'header' => 'Время прибытия',
		],
		[
			'name'   => 'startPoint',
			'header' => 'Маршрут от',
		],
		[
			'name'   => 'endPoint',
			'header' => 'Маршрут до',
		],
		[
			'class'    => 'CButtonColumn',
			'template' => '{boarding}',
			'buttons'  => [
				'boarding' => [
					'label'    => 'Печать билета',
					'imageUrl' => Yii::app()->request->baseUrl . '/images/print_ticket.png',
					'url'      => 'Yii::app()->controller->createUrl("pdfmake/boardingticket/profileId/$data[profileId]")',
					'click'    => 'function(){ newWin = window.open($(this).attr("href"),"Boarding Ticket", "height=600,width=800"); if(window.focus){ newWin.focus; newWin.print();} return false; }',
				]
			]]
	],
]);