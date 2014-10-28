<?php

$labels = $data->attributeLabels();

foreach ($data as $id => $item) {
	if (in_array($id, array('id', 'tid', 'uid', 'created')) || !$item) continue;
	if ($id == 'black_list') {
		$item = $item == 1 ? 'да' : 'нет';
	}
	?>
	<div class="profiles-data">
		<div class="label"><?php print $labels[$id]; ?>:</div>
		<div class="value"><?php print $item; ?></div>
	</div>
<?php } ?>