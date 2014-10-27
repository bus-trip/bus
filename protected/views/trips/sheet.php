<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 01.10.14
 * Time: 17:48
 */
$this->breadcrumbs = array(
	'Рейсы' => array('admin'),
	'Посадочная ведомость',
);

$this->menu = array(
	array('label' => 'Рейсы', 'url' => array('admin')),
);
?>

<h2>Посадочная ведомость</h2>
Направление: <?php echo $dataHeader['direction']['startPoint'] . ' - ' . $dataHeader['direction']['endPoint']; ?><br/>
Отправление: <?php echo $dataHeader['trips']['departure']; ?>&nbsp;&nbsp;&nbsp;&nbsp;Прибытие: <?php echo $dataHeader['trips']['arrival']; ?>
<br/>
Автобус: <?php echo $dataHeader['bus']['model'] . ', номер ' . $dataHeader['bus']['number']; ?><br/>

<?php

$this->widget('zii.widgets.grid.CGridView', array(
	'id'                    => 'trips-sheet',
	'dataProvider'          => $dataProvider,
	'template'              => '{items}',
	'rowCssClassExpression' => '
	  ( $row%2 ? $this->rowCssClass[1] : $this->rowCssClass[0] ) .
	  (isset($data["status"])?" row-status-".$data["status"]:"")',
	'columns'               => array(
		array(
			'name'   => 'place',
			'header' => 'Место',
		),
		array(
			'name'   => 'passenger',
			'header' => 'ФИО',
		),
		array(
			'name'   => 'startPoint',
			'header' => 'Посадка',
		),
		array(
			'name'   => 'endPoint',
			'header' => 'Высадка',
		),
		array(
			'name'   => 'phone',
			'header' => 'Номер телефона',
		),
		array(
			'name'   => 'price',
			'header' => 'Стоимость',
		),
		array(
			'name'        => 'black_list',
			'header'      => 'BL',
			'htmlOptions' => array('class' => 'center')
		),
		array(
			'class'    => 'CButtonColumn',
			'template' => '{ticket}',
			'buttons'  => array(
				'ticket' => array(
					'label'    => 'Оформление билета',
					'imageUrl' => Yii::app()->request->baseUrl . '/images/update.png',
					//					'url'   => 'Yii::app()->controller->createUrl("trips/sheet", array("id"=>'.$dataHeader['trips']['id'].', "place"=>$data["place"]))'
					'url'      => 'Yii::app()->controller->createUrl("trips/sheet/' . $dataHeader['trips']['id'] . '/$data[place]")'
				)
			)
		),
	),
));
?>
<div class="legends">
	Статусы билетов: <br>
	<?php foreach (Tickets::statuses() as $key => $value) {
		if ($key == TICKET_CANCELED) continue;
		?>
		<div class="row">
			<div class="legend-ico row-status-<?php print $key; ?>"></div>
			<div class="legend"><?php print $value; ?></div>
		</div>
	<?php } ?>

</div>

<p><?php
	echo '<a href="' . Yii::app()->createUrl('trips/sheetprint',
											 array('id' => $_GET['id'])) . '" target="_blank">Версия для печати</a>';
	?></p>