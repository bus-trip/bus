<?php
/**
 * Part of bus 2015
 * Created by: Александр on 07.06.2015:21:24
 */

foreach ($menu->items as $item) { ?>
	<li class="<?= $item['active'] ? 'active' : '' ?>">
		<table>
			<tr class="cols4">
				<?php if (!empty($item['url'])) {
					print '<td class="small link" data-href="' . CHtml::normalizeUrl($item['url']) . '">';
					print '<div class="link">' . CHtml::link($item['label'], $item['url'], isset($item['linkOptions']) ? $item['linkOptions'] : []) . '</div>';
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
					print '<td class="small novalid">';
					print $item['label'];
				}

				print '</td>';
				?>

			</tr>
		</table>
	</li>
<?php } ?>