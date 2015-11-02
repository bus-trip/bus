<?php
/* @var $this UserController */
/* @var $model LoginForm */
/* @var $form CActiveForm */

$this->breadcrumbs = array(
	'Авторизация' => ['/user/login'],
	'Восстановление пароля'
);
?>

<h1><?= $this->pageTitle ?></h1>

<div class="form">
	<?php $form = $this->beginWidget('CActiveForm', array(
		'id' => 'recover-form',
	)); ?>

	<div class="row">
		<?php echo $form->labelEx($model, 'login'); ?>
		<?php echo $form->textField($model, 'login'); ?>
		<?php echo $form->error($model, 'login'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Восстановить'); ?>
	</div>

	<?php $this->endWidget(); ?>
</div><!-- form -->
