<?php

$labels = $data->attributeLabels();

foreach ($data as $id => $item) {
	if ($id == 'black_list') {
		$item = $item == 1 ? 'да' : 'нет';
	} elseif ($id == 'doc_type') {
		switch ($item) {
			case Profiles::DOC_PASSPORT:
				$item = 'Паспорт';
				break;
			case Profiles::DOC_BIRTH_CERTIFICATE:
				$item = 'Свидетельство о рождении';
				break;
			case Profiles::DOC_FOREIGN_PASSPORT:
				$item = 'Загран паспорт';
				break;
			case Profiles::DOC_MILITARY_ID:
				$item = 'Военный билет';
				break;
		}
	}
	if (in_array($id, array('id', 'tid', 'uid', 'created')) || !$item || $item == 'null') continue;

	?>
	<div class="profiles-data">
		<div class="label"><?php print $labels[$id]; ?>:</div>
		<div class="value"><?php print $item; ?></div>
	</div>
<?php } ?>