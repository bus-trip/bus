<?php
/* @var $this AccountController */

$this->breadcrumbs = array(
	'Аккаунт',
);

foreach ($account['values'] as $id => $item) {
	if ($item != '') {
		?>
		<div class="item">
			<div class="label"><?php print $account['labels'][$id]; ?>: </div>
			<div class="value"><?php print $item; ?></div>
		</div>
	<?php
	}
} ?>