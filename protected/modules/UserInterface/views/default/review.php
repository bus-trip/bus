<?php
/**
 * Part of bus 2015
 * Created by: Александр on 07.06.2015:21:35
 *
 * @var $checkoutModel \UserInterface\models\Checkout
 * @var $this DefaultController
 */
use UserInterface\controllers\DefaultController;

$profileLabels = (new Profiles())->attributeLabels();

?>
	<div class="search-form__container">
		<div class="check">
			<div class="check__wrapper">
				<?php print $this->renderPartial('trip', compact('trip', 'prices'), true); ?>
			</div>
			<div class="check__tickets">
				<h3 class="check__title"><?= count($saved[DefaultController::STEP_PROFILE]['profiles']) === 1 ? 'Билет' : 'Билеты' ?></h3>
				<?php foreach ($saved[DefaultController::STEP_PROFILE]['profiles'] as $i => $profileItem) { ?>
					<div class="check__tickets-info">
						<p>
							<span>Место: </span>
							<strong><?= $saved[DefaultController::STEP_PLACE]['places'][$i] ?></strong>
						</p>
						<p>
							<span>Стоимость: </span>
							<strong><?= $prices[$saved[DefaultController::STEP_PLACE]['places'][$i]] ?>₽</strong>
						</p>
						<?php
						foreach ($profileItem as $id => $item) {
							if ($id === 'sex') {
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
							} elseif ($id === 'doc_type') {
								$item = Profiles::getDocType($item);
							}
							?>
							<p>
								<span><?= $profileLabels[$id] ?>: </span>
								<strong><?= $item ?></strong>
							</p>
						<?php } ?>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
<?php
/** @var $form CActiveForm */
$form = $this->beginWidget('CActiveForm'); ?>

<?= CHtml::activeHiddenField($checkoutModel, 'reviewStep') ?>
	<div class="search-form__footer">
		<a class="search-form__prev-step btn btn_border" href="<?= $back ?>">Назад</a>
		<?php echo CHtml::submitButton(($this->robokassa() ? 'Оплатить' : 'Оформить'), ['class' => 'search-form__next-step btn']); ?>
	</div>
<?php $this->endWidget();