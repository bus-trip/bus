<?php
/**
 * Part of bus 2015
 * Created by: Александр on 07.06.2015:21:35
 *
 * @var $this \UserInterface\controllers\DefaultController
 * @var $checkoutModel \UserInterface\models\Checkout
 */
?>
find

<?php
/** @var $form CActiveForm */
$form = $this->beginWidget('CActiveForm'); ?>
<?php echo $form->errorSummary($checkoutModel); ?>

	<div class="row">
		<?php echo $form->labelEx($checkoutModel, 'tripId'); ?>
		<?php echo $form->textField($checkoutModel, 'tripId'); ?>
		<?php echo $form->error($checkoutModel, 'tripId'); ?>
	</div>


	<div class="row buttons">
		<?php echo CHtml::submitButton('Далее'); ?>
	</div>

<?php $this->endWidget(); ?>