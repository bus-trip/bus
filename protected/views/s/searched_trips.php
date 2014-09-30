<?php if (empty($trips)) { ?>
	<p>Рейсов по маршруту <?php print $startPoint; ?> - <?php print $endPoint; ?> с датой
		отправления <?php print $departure; ?> <b>не найдено</b>.</p>


<?php } else { ?>

	<p>Рейсы по маршруту <?php print $startPoint; ?> - <?php print $endPoint; ?>:</p>

	<?php foreach ($trips as $trip) { ?>
		<div class="row">
			<?php print $this->renderPartial('searched_one_trip', array('trip' => $trip), TRUE); ?>
		</div>
	<?php } ?>
<?php } ?>

<a href="<?php print $this->createUrl('/s/search') ?> ">Назад</a>