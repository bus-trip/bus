<?php

$this->breadcrumbs = array(
	'Аккаунт'     => array('/account'),
	'Мои профили' => array('/account/passengers'),
	'Добавление профиля'
);
?>

<div class="item-profile form">

	<?php $form = $this->beginWidget('CActiveForm', array(
		'id'                   => 'profiles-one_passager-form',
		// Please note: When you enable ajax validation, make sure the corresponding
		// controller action is handling ajax validation correctly.
		// See class documentation of CActiveForm for details on this,
		// you need to use the performAjaxValidation()-method described there.
		'enableAjaxValidation' => FALSE,
	)); ?>

	<?php $this->renderPartial('profile_form', array('form' => $form, 'model' => $model)); ?>


	<div class="row buttons">
		<?php echo CHtml::submitButton('Submit'); ?>
	</div>

	<?php $this->endWidget(); ?>

</div><!-- form -->