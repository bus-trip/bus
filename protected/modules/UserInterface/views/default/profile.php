<?php
/**
 * Part of bus 2015
 * Created by: Александр on 07.06.2015:21:35
 *
 * @var $this DefaultController
 * @var $profileModels \Profiles[]
 * @var $userProfiles [] Profiles[]
 * @var $checkoutModel \UserInterface\models\Checkout
 */
use UserInterface\controllers\DefaultController;

$ajaxCheck = $this->createUrl('/UserInterface/profiles/check');
$ajaxForm  = $this->createUrl('/UserInterface/profiles/form');

$varProfiles = [];
/** @var Profiles[] $userProfile */
foreach ($userProfiles as $userProfile) {
	$values                        = $userProfile->getAttributes();
	$varProfiles[$userProfile->id] = $values;
}
$jsonProfiles = json_encode($varProfiles);

Yii::app()->clientScript
	->registerScript('UserInterface.views.default.profile', <<<JS
var params = {ajaxCheck: "$ajaxCheck", ajaxForm: "$ajaxForm"},
	profiles = $jsonProfiles;
JS
		, CClientScript::POS_BEGIN)
	->registerScriptFile(Yii::app()->baseUrl . '/js/checkout.js', CClientScript::POS_END);
?>

<?php
/** @var $form CActiveForm */
$form = $this->beginWidget('CActiveForm'); ?>

<?= CHtml::activeHiddenField($checkoutModel, 'profileStep') ?>
<div class="search-form__container">
	<?php echo $form->errorSummary($checkoutModel); ?>
	<div class="profile">
		<div class="profile__wrapper">
			<?php foreach ($saved[DefaultController::STEP_PLACE]['places'] as $i => $place) {
				$profileModel = $profileModels[$i];
				?>
				<div class="profile__item">
					<h3 class="profile__title">Место №<?= $place ?></h3>
					<?php $this->renderPartial('UserInterface.views.profiles.item', compact('profileModel', 'i', 'userProfiles')) ?>
				</div>
			<?php } ?>
		</div>
		<div class="profile__address">
			<label>Адрес посадки</label>
			<?= $form->textArea($checkoutModel, 'address_from', ['class' => 'textarea', 'rows' => '3', 'placeholder' => 'Например, г. Москва, ул. Бобруйская улица, д. 3']) ?>
		</div>
		<div class="profile__address">
			<label>Адрес высадки</label>
			<?= $form->textArea($checkoutModel, 'address_to', ['class' => 'textarea', 'placeholder' => 'Например, г. Элиста, ул. Ленина, д. 23', 'rows' => '3']) ?>
		</div>
	</div>
</div>
<div class="search-form__footer">
	<a class="search-form__prev-step btn btn_border" href="<?= $back ?>">Назад</a>
	<?php echo CHtml::submitButton('Продолжить', ['class' => 'search-form__next-step btn']); ?>
</div>
<?php $this->endWidget(); ?>
