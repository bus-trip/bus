<?php
/**
 * Part of bus 2015
 * Created by: Alexander Sumarokov on 13.09.2015:15:00
 */
?>
<div class="item-profile form">

	<?php $form = $this->beginWidget('CActiveForm', array(
		'id'                   => 'profiles-one_passager-form',
		// Please note: When you enable ajax validation, make sure the corresponding
		// controller action is handling ajax validation correctly.
		// See class documentation of CActiveForm for details on this,
		// you need to use the performAjaxValidation()-method described there.
		'enableAjaxValidation' => false,
	)); ?>

	<?php $this->renderPartial('application.views.account.profile_form', array('form' => $form, 'model' => $model)); ?>


	<div class="row buttons">
		<?php echo CHtml::submitButton('Submit'); ?>
	</div>

	<?php $this->endWidget(); ?>

</div><!-- form -->