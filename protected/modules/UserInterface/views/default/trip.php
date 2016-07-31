<?php
/**
 * Part of bus 2015
 * Created by: Александр on 17.06.2015:22:52
 *
 * @var $trip Trips
 */
?>
<div class="check__direction">
	<h3 class="check__title">Направление</h3>
	<div class="check__direction-info">
		<p><?php print $trip->idDirection0->startPoint . ' - ' . $trip->idDirection0->endPoint ?></p>
		<p>
			Стоимость:
			<strong><?= array_sum($prices) ?> ₽</strong>
		</p>
	</div>
</div>
<div class="check__bus">
	<h3 class="check__title">Информация об автобусе</h3>
	<div class="check__direction-info">
		<p><?php print trim($trip->idBus0->model) ?></p>
		<p>Гос. номер: <?php print $trip->idBus0->number ?></p>
	</div>
</div>