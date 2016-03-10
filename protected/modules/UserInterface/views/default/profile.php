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
<div id="checkout-profiles-wrapper">
	<div id="profiles" class="clearfix">
		<div class="grid">
			<?php foreach ($saved[DefaultController::STEP_PLACE]['places'] as $i => $place) {
				$profileModel = $profileModels[$i];
				?>
				<div class="profile-item grid__item grid__item_xs-12 grid__item_s-6 grid__item_l-auto">
					<table class="form-table form-table_ticket">
						<tr>
							<td colspan="2">
								<h3 class="title title_fz-s title_ta-r">Место №<?= $place ?></h3>
							</td>
						</tr>
						<?php $this->renderPartial('UserInterface.views.profiles.item', compact('profileModel', 'i', 'userProfiles')) ?>
					</table>
				</div>
			<?php } ?>
		</div>
	</div>
</div>
<div class="grid">
	<div class="grid__item grid__item_xs-12">
		<label>Посадка (адрес)</label>
		<?= $form->textArea($checkoutModel, 'address_from', ['class' => "textarea"]) ?>
	</div>

	<div class="grid__item grid__item_xs-12">
		<label>Высадка (адрес)</label>
		<?= $form->textArea($checkoutModel, 'address_to', ['class' => "textarea"]) ?>
	</div>
</div>
<div class="grid grid_jc-sb">
	<div class="grid__item grid__item_xs-auto">
		<a href="<?= $back ?>" class="btn btn_br-blue" title="Назад">Назад</a>
	</div>
	<div class="grid__item grid__item_xs-auto">
		<?php echo CHtml::submitButton('Продолжить', ['class' => 'btn']); ?>
	</div>
</div>
<?php $this->endWidget(); ?>
