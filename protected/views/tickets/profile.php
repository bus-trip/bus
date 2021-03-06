<?php
/**
 * @var $this TicketsController
 */

if (isset($tripId["trip_id"])) {
	?>
	<form action="/trips/sheet/<?php echo $tripId["trip_id"]; ?>" method="post">
		<input type="hidden" name="yearSelect" value="<?php echo $tripId['yearSelect']; ?>"/>
		<input type="hidden" name="monthSelect" value="<?php echo $tripId['monthSelect']; ?>"/>
		<input type="submit" value="Вернуться к ведомости"
			   style="padding:0;background: none; border: none; color: #0066ff; text-decoration: underline; cursor: pointer;"/>
	</form>
	<p/>
	<?php
}
?>
<h1><?php print $this->pageTitle; ?></h1>
<div class="row">
	<h2>Информация о профиле пассажира</h2>

	<div class="data">
		<?php print $this->renderPartial('application.views.account.one_passenger_data', array('data' => $model), true); ?>
	</div>
	<div class="edit"><a href="<?= $this->createUrl('tickets/profileEdit', ['id' => $model->id]) ?>" target="_blank">Изменить</a></div>
</div>
<br><br>
<div class="row">
	<h2>Билеты</h2>

	<?php
	$this->widget('zii.widgets.grid.CGridView', array(
		'dataProvider'          => $dataProvider,
		'template'              => '{items}',
		'rowCssClassExpression' => '
	  ( $row%2 ? $this->rowCssClass[1] : $this->rowCssClass[0] ) .
	  (isset($data["status"])?" row-status-".$data["status"]:"")',
		'columns'               => array(
			array(
				'name'   => 'id',
				'header' => '#',
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
				'name'   => 'price',
				'header' => 'Стоимость, руб',
			),
			array(
				'name'   => 'place',
				'header' => 'Место',
			),
			array(
				'name'   => 'address_from',
				'header' => 'Адрес от',
			),
			array(
				'name'   => 'address_to',
				'header' => 'Адрес до',
			),
			array(
				'name'   => 'remark',
				'header' => 'Примечание к билету',
			),
		),
	));
	?>
</div>

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
