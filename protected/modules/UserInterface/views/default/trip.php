<?php
/**
 * Part of bus 2015
 * Created by: Александр on 17.06.2015:22:52
 *
 * @var $trip Trips
 */
?>

<div id="checkout-ticket-wrapper">
	<h2>Направление</h2>

	<p><?php print $trip->idDirection0->startPoint . ' - ' . $trip->idDirection0->endPoint ?><br>
		Стоимость одного билета: <?php print $trip->idDirection0->price ?> руб.</p>

	<h2>Информация об автобусе</h2>

	<p><?php print trim($trip->idBus0->model) ?>; гос. номер: <?php print $trip->idBus0->number ?></p>
</div>