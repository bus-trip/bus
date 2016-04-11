<?php
/**
 * Part of bus 2015
 * Created by: Александр on 07.06.2015:21:35
 *
 * @var $checkoutModel \UserInterface\models\Checkout
 * @var $this DefaultController
 */
use UserInterface\controllers\DefaultController;

$profile       = new Profiles();
$profileLabels = $profile->attributeLabels();

?>
<?php print $this->renderPartial('trip', compact('trip', 'prices'), true) ?>
	<div id="checkout-profiles-wrapper">
		<h2><?= count($saved[DefaultController::STEP_PROFILE]['profiles']) == 1 ? 'Билет' : 'Билеты' ?></h2>
		<div class="grid">
			<?php foreach ($saved[DefaultController::STEP_PROFILE]['profiles'] as $i => $profileItem) { ?>
				<div class="grid__item grid__item_xs-12 grid__item_s-6 grid__item_l-auto">
					<table class="form-table form-table_ticket">
						<tr>
							<td class="form-table__name">Место</td>
							<td><b>№<?= $saved[DefaultController::STEP_PLACE]['places'][$i] ?></b></td>
						</tr>
						<tr>
							<td class="form-table__name">Стоимость</td>
							<td><b><?= $prices[$saved[DefaultController::STEP_PLACE]['places'][$i]] ?></b> руб.</td>
						</tr>
						<?php
						foreach ($profileItem as $id => $item) {
							if ($id == 'sex') {
								switch ($item) {
									case 'none':
										$item = 'Не указано';
										break;
									case Profiles::SEX_MALE:
										$item = 'Мужской';
										break;
									case Profiles::SEX_FEMALE:
										$item = 'Женский';
										break;
								}
							} elseif ($id == 'doc_type') {
								$item = Profiles::getDocType($item);
							}
							?>
							<tr>
								<td class="form-table__name"><?= $profileLabels[$id] ?></td>
								<td><?= $item ?></td>
							</tr>
						<?php } ?>
					</table>
				</div>
			<?php } ?>
		</div>
	</div>
<?php
/** @var $form CActiveForm */
$form = $this->beginWidget('CActiveForm'); ?>

<?= CHtml::activeHiddenField($checkoutModel, 'reviewStep') ?>
	<div class="grid grid_jc-sb">
		<div class="grid__item grid__item_xs-auto">
			<a href="<?= $back ?>" class="btn btn_br-blue" title="Назад">Назад</a>
		</div>
		<div class="grid__item grid__item_xs-auto">
			<?php
			$label = 'Оформить';
			if ($this->robokassa()) {
				$label = 'Оплатить';
			}
			echo CHtml::submitButton($label, ['class' => 'btn']); ?>
		</div>
	</div>
<?php $this->endWidget(); ?>