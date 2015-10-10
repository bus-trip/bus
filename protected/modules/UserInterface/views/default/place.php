<?php
/**
 * Part of bus 2015
 * Created by: Alexander Sumarokov on 10.10.2015:13:47
 *
 * @var $this \UserInterface\controllers\DefaultController
 * @var $checkoutModel \UserInterface\models\Checkout
 */

Yii::app()->clientScript
	->registerScriptFile(Yii::app()->baseUrl . '/js/checkout.js', CClientScript::POS_END);

?>

<?php
/** @var $form CActiveForm */
$form = $this->beginWidget('CActiveForm'); ?>

<div id="checkout-places-wrapper">
	<?php echo $form->errorSummary($checkoutModel); ?>

	<div><?= $form->labelEx($checkoutModel, 'places') ?></div>
	<div><?= $form->checkBoxList($checkoutModel, 'places', $places) ?></div>
</div>


<?= CHtml::activeHiddenField($checkoutModel, 'placesStep') ?>
<div class="row buttons">
	<?php echo CHtml::submitButton('Далее'); ?>
</div>

<?php $this->endWidget(); ?>

