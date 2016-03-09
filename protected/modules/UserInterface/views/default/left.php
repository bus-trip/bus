<?php
/**
 * Part of bus 2015
 * Created by: Александр on 07.06.2015:21:24
 */

foreach ($menu->items as $item) { ?>
	<li>
		<?php if (!empty($item['url'])) {
			$options          = isset($item['linkOptions']) ? $item['linkOptions'] : [];
			$options['class'] = 'link';
			if ($item['active']) {
				$options['class'] .= ' link_active';
			}
			print CHtml::link($item['label'], $item['url'], $options);
//					switch ($item['step']) {
//						case 'tariff':
//							if (isset($item['data']['tariff'])) {
//								print '<div class="wizard-data">';
//								print $this->getTariffValue($item['data']['tariff'], $item['data']['packageId']);
//								print '</div>';
//							}
//							break;
//						case $this::STEP_SIP:
//							if (isset($item['data']['sip'])) {
//								print '<div class="wizard-data">';
//								print $item['data']['sip'];
//								print '</div>';
//							}
//							break;
//						case 'abc':
//							if (!empty($item['data']['numbers'])) {
//								print '<div class="wizard-data">';
//								$reserved        = $session[NumberForm::class . 'reserve'] ?: [];
//								$activationTotal = 0;
//								$recurringTotal  = 0;
//								foreach ($item['data']['numbers'] as $number) {
//									if (isset($reserved[$number])) {
//										$activationTotal += $reserved[$number]['activationPrice'];
//										$recurringTotal += $reserved[$number]['recurringPrice'];
//
//										print NumberForm::numberFormat($number) . '<br>';
//									}
//								}
//								print '<div class="total item-1">Подключение - ' . $activationTotal . ' руб.</div>';
//								print '<div class="total item-2">Абонентская плата - ' . $recurringTotal . ' руб./мес.</div>';
//								print '</div>';
//							}
//							break;
//					}
		} else {
			print '<span class="link link_disactive">' . $item['label'] . '</span>';
		}

		?>
	</li>
<?php } ?>