<?php
/**
 * Part of bus 2015
 * Created by: Александр on 07.06.2015:21:24
 */

foreach ($menu->items as $item) {

	$options          = isset($item['linkOptions']) ? $item['linkOptions'] : [];
	$options['class'] = 'form-nav__item';
	if ($item['active']) {
		$options['class'] .= ' form-nav__item_active';
	}

	switch ($item['step']) {
		case 'place':
			$img = 'seat';
			break;
		case 'profile':
			$img = 'ticket';
			break;
		case 'review':
			$img = 'check';
			break;
		default:
			$img = 'search';
	}

	$label = '<span class="form-nav__icon"><img src="/themes/bus/svg/icn-' . $img . '.svg"/></span>' .
		'<span class="form-nav__name">' . $item['label'] . '</span>';
	if (!empty($item['url'])) {
		print CHtml::link($label, $item['url'], $options);
	} else {
		print '<a class="' . $options['class'] . ' form-nav__item_disable" href="#">' . $label . '</a>';
	}
}