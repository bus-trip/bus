<?php foreach ($trips as $trip) { ?>
	<div class="row">
		<?php print $this->renderPartial('searched_one_trip', array('trip' => $trip), TRUE); ?>
	</div>
<?php } ?>