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
Автобус: <?php echo $dataHeader['bus']['model'] . ', номер ' . $dataHeader['bus']['number']; ?>
&nbsp;&nbsp;
<?php
echo CHtml::link('Редактировать', "#", array("id" => "bus-link"));
?>
<div id="select-bus">
	<h3>Выбор автобуса для рейса:</h3>
	<form action="/trips/selectbus" method="post">
		<input type="hidden" name="idTrip" value="<?php echo $dataHeader['trips']['id'] ?>">
		<?php
		echo CHtml::dropDownList('buslist', $buses, $dataHeader['buses'], array('options' => array($dataHeader['bus']['id'] => array('selected' => 'selected'))));
		?>
		<input type="submit" value="Назначить автобус в рейс">
	</form>
</div>

<br/>

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
			'header' => '№',
		),
		array(
			'name'   => 'passport',
			'header' => 'Паспорт',
			'type'   => 'raw',
			'value'  => 'isset($data["profile_id"]) ? CHtml::link($data["passport"],array("tickets/profile/" . $data["profile_id"])):""',
		),
		array(
			'name'   => 'last_name',
			'header' => 'Фамилия',
		),
		array(
			'name'   => 'name',
			'header' => 'Имя',
		), array(
			'name'   => 'middle_name',
			'header' => 'Отчество',
		),
		array(
			'name'   => 'phone',
			'header' => 'Номер телефона',
		),
		array(
			'name'   => 'birthday',
			'header' => 'Дата рождения',
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
			'name'   => 'remark',
			'header' => 'Примечание',
		),
		array(
			'name'   => 'price',
			'header' => 'Стоимость',
		),
		array(
			'class'    => 'CButtonColumn',
			'template' => '{create_ticket}{edit_ticket}&nbsp;&nbsp;&nbsp;{confirm}{delete}&nbsp;&nbsp;&nbsp;{blacklist}{unblacklist}&nbsp;&nbsp;&nbsp;{ticket}',
			'buttons'  => array(
				'create_ticket' => array(
					'label'    => 'Создать билет',
					'imageUrl' => Yii::app()->request->baseUrl . '/images/add1.png',
					'visible'  => 'empty($data["passport"])',
					'url'      => 'Yii::app()->controller->createUrl("trips/sheet/' . $dataHeader['trips']['id'] . '/$data[place]")',
					'options'  => array('class' => 'create-ticket', 'data-tripId' => $dataHeader['trips']['id']),
				),
				'edit_ticket'   => array(
					'label'    => 'Редактировать билет',
					'imageUrl' => Yii::app()->request->baseUrl . '/images/edit.png',
					'visible'  => '!empty($data["passport"])',
					'url'      => 'Yii::app()->controller->createUrl("trips/sheet/' . $dataHeader['trips']['id'] . '/$data[place]")',
					'options'  => array('class' => 'edit-ticket', 'data-tripid' => $dataHeader['trips']['id']),
				),
				'confirm'       => array(
					'label'    => 'Подтвердить бронь',
					'imageUrl' => Yii::app()->request->baseUrl . '/images/success.png',
					'url'      => 'Yii::app()->controller->createUrl("tickets/confirm/$data[ticket_id]")',
					'visible'  => '!empty($data["passport"]) && $data["status"] != 2',
					'click'    => 'function(){return confirm("Вы хотите подтвердить бронь?");}'
				),
				'delete'        => array(
					'label'    => 'Отменить бронь',
					'imageUrl' => Yii::app()->request->baseUrl . '/images/cancel.png',
					'url'      => 'Yii::app()->controller->createUrl("trips/deleteticket/$data[ticket_id]")',
					'visible'  => '!empty($data["passport"])',
					'click'    => 'function(){ return confirm("Вы хотите отменить бронирование/покупку этого билета?"); }'
				),
				'blacklist'     => array(
					'label'    => 'Внести в чёрный список',
					'url'      => 'Yii::app()->controller->createUrl("tickets/blacklist/id/' . $dataHeader['trips']['id'] . '/profileid/$data[profile_id]/action/add")',
					'imageUrl' => Yii::app()->request->baseUrl . '/images/blacklist.png',
					'visible'  => 'empty($data["black_list"]) && !empty($data["passport"])',
					'click'    => 'function(){ return confirm("Хотите внести пассажира в чёрный список?"); }'
				),
				'unblacklist'   => array(
					'label'    => 'Извлечь из чёрного списка',
					'url'      => 'Yii::app()->controller->createUrl("tickets/blacklist/id/' . $dataHeader['trips']['id'] . '/profileid/$data[profile_id]/action/del")',
					'imageUrl' => Yii::app()->request->baseUrl . '/images/unblacklist.png',
					'visible'  => '!empty($data["black_list"])',
					'click'    => 'function(){ return confirm("Хотите извлечь пассажира из чёрного списка?"); }'
				),
				'ticket'        => array(
					'label'    => 'Войти в билет',
					'imageUrl' => Yii::app()->request->baseUrl . '/images/document_9498.png',
					'visible'  => '!empty($data["passport"])',
					'url'      => 'Yii::app()->controller->createUrl("trips/sheet/' . $dataHeader['trips']['id'] . '/$data[place]")',
				),
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