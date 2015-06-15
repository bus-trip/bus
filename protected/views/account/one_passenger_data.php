<?php

$labels = $data->attributeLabels();

foreach ($data as $id => $item) {
	if ($id == 'sex') {
		switch ($item) {
			case null:
				$item = 'Не указано';
				break;
			case 0:
				$item = 'Мужской';
				break;
			case 1:
				$item = 'Женский';
				break;
		}
	}
	if (in_array($id, ['id', 'tid', 'uid', 'created']) || !$item || $item == 'null') continue;
	if ($id == 'black_list') {
		$item = $item == 1 ? 'да' : 'нет';
	} elseif ($id == 'birth') {
		$item = date('d.m.Y', $item);
	}
	?>
	<div class="profiles-data">
		<div class="label"><?php print $labels[$id]; ?>:</div>
		<div class="value"><?php print $item; ?></div>
	</div>
<?php } ?>