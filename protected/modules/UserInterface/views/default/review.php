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
<?php print $this->renderPartial('trip', compact('trip'), true) ?>

	<div id="checkout-profiles-wrapper">
		<h2>Профили билета</h2>

		<div id="profiles" class="clearfix">
			<?php foreach ($saved[DefaultController::STEP_PROFILE]['profiles'] as $profileItem) { ?>
				<div class="profile-item">
					<?php
					foreach ($profileItem as $id => $item) {
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