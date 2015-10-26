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

		<div id="profiles" class="clearfix">
			<?php foreach ($saved[DefaultController::STEP_PROFILE]['profiles'] as $i => $profileItem) { ?>
				<div class="profile-item">
					<div class="row">
						Номер места: <b><?= $saved[DefaultController::STEP_PLACE]['places'][$i] ?></b>
					</div>
					<div class="row">
						Стоимость: <b><?= $prices[$saved[DefaultController::STEP_PLACE]['places'][$i]] ?> руб.</b>
					</div>
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
						<div class="row">
							<?php print $profileLabels[$id] . ': <b>' . $item . '</b>' ?>
						</div>
					<?php } ?>
				</div>
			<?php } ?>
		</div>
	</div>
<?php
/** @var $form CActiveForm */
$form = $this->beginWidget('CActiveForm'); ?>

<?= CHtml::activeHiddenField($checkoutModel, 'reviewStep') ?>
	<div class="row buttons">
		<?php echo CHtml::submitButton('Далее'); ?>
	</div>

<?php $this->endWidget(); ?>