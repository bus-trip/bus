<?php

$labels = $data->attributeLabels();

foreach ($data as $id => $item) {
	if (in_array($id, array('id', 'uid', 'created')) || !$item) continue;
	?>
	<div class="profiles-data">
		<div class="label"><?php print $labels[$id]; ?>:</div>
		<div class="value"><?php print $item; ?></div>
	</div>
<?php } ?>