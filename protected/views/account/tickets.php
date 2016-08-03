<?php
/**
 * Part of bus 2015
 * Created by: Александр on 12.07.2015:12:23
 *
 * @var $dataProvider CDataProvider
 */
?>
	<p style="font-weight: bold;">Для отмены бронирования позвоните <a
			href="<?= Yii::app()->createUrl('contacts') ?>">по телефонам</a>.</p>
<?php
$this->widget('zii.widgets.grid.CGridView', [
	'id'            => 'user-auth-data-grid',
	'dataProvider'  => $modelData,
	'template'      => '{items}{pager}',
	'itemsCssClass' => 'list-direction font-size-small',
	'columns'       => [
		['name'   => 'name',
		 'header' => 'ФИО'],
		['name'   => 'startPoint',
		 'header' => 'Пункт отправления',
		 'value'  => '$data["startPoint"] . " " . $data["address_from"]'],
		['name'   => 'departure',
		 'value' => 'date("d.m.Y H:i", strtotime($data["departure"]))',
		 'header' => 'Время отправления'],
		['name'   => 'endPoint',
		 'header' => 'Пункт прибытия',
		 'value'  => '$data["endPoint"] . " " . $data["address_to"]'],
		['name'   => 'arrival',
		 'value' => 'date("d.m.Y H:i", strtotime($data["arrival"]))',
		 'header' => 'Время прибытия'],
		['name'   => 'place',
		 'header' => 'Место'],
		['name'   => 'price',
		 'header' => 'Цена'],
		['name'   => 'status',
		 'header' => 'Статус'],
		['class'    => 'CButtonColumn',
		 'template' => '{boarding}',
		 'buttons'  => [
			 'boarding' => [
				 'label'    => 'Печать билета',
				 'imageUrl' => Yii::app()->request->baseUrl . '/images/print_ticket.png',
				 'url'      => 'Yii::app()->controller->createUrl("pdfmake/ticket/profileId/$data[profileId]")',
				 'visible'  => '$data["status"]!="Отменен"',
				 'click'    => 'function(){ newWin = window.open($(this).attr("href"),"Boarding Ticket", "height=600,width=800"); if(window.focus){ newWin.focus; newWin.print();} return false; }',
			 ]
		 ]
		]
	],
]);