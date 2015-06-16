<?php
/**
 * Part of bus 2015
 * Created by: Александр on 07.06.2015:21:35
 *
 * @var $this \UserInterface\controllers\DefaultController
 * @var $profileModels \Profiles[]
 * @var $userProfiles[] Profiles[]
 * @var $checkoutModel \UserInterface\models\Checkout
 */

$ajaxCheck = $this->createUrl('/UserInterface/profiles/check');
$ajaxForm  = $this->createUrl('/UserInterface/profiles/form');

$varProfiles = [];
foreach ($userProfiles as $userProfile) {
	$values = $userProfile->getAttributes();
	$values['birth'] = date('d.m.Y', $values['birth']);
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

<div id="checkout-profiles-wrapper">
	<p class="note">Поля с <span class="required">*</span> являются обязательными.</p>

	<div id="profiles" class="clearfix">
		<?php foreach ($profileModels as $i => $profileModel) { ?>
			<div class="profile-item">
				<?php $this->renderPartial('UserInterface.views.profiles.item', compact('profileModel', 'i', 'userProfiles')) ?>
				<?php if ($i > 0) print '<div class="del">Удалить</div>'; ?>
			</div>
		<?php } ?>
	</div>
	<div id="add-profile">Добавить пассажира</div>
</div>


<?= CHtml::activeHiddenField($checkoutModel, 'profileStep') ?>
<div class="row buttons">
	<?php echo CHtml::submitButton('Далее'); ?>
</div>

<?php $this->endWidget(); ?>
