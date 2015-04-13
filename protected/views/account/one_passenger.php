<?php
/**
 * @var $this AccountController
 * @var $model Profiles
 */
?>
<div class="item-profile form">

	<?php $form = $this->beginWidget('CActiveForm', [
		'id'                   => 'profiles-one_passager-form',
		'enableAjaxValidation' => false,
	]); ?>

	<?php $this->renderPartial('profile_form', ['form' => $form, 'model' => $model]); ?>


	<div class="row buttons">
		<?php echo CHtml::submitButton('Сохранить'); ?>
	</div>

	<?php $this->endWidget(); ?>

</div><!-- form -->