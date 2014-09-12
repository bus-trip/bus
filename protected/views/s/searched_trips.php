<div>Найденные рейсы на маршрут СПб - Хельсинки:</div>

<?php foreach ($trips as $trip) { ?>
	<div class="row">
		<?php print $this->renderPartial('searched_one_trip', array('trip' => $trip), TRUE); ?>
	</div>
<?php } ?>

<a href="<?php print $this->createUrl('/s/search') ?> ">Назад</a>