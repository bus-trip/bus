<?php
/* @var $this UserController */
/* @var $model LoginForm */
/* @var $form CActiveForm */

$this->pageTitle = Yii::app()->name . ' - Login';
$this->breadcrumbs = array(
	'Авторизация',
);
?>

<h1>Авторизация в системе</h1>

<div class="form">
	<?php $form = $this->beginWidget('CActiveForm', array(
		'id' => 'login-form',
	)); ?>

	<p class="note">Поля с <span class="required">*</span> являются обязательными.</p>

	<div class="row">
		<?php echo $form->labelEx($model, 'username'); ?>
		<?php echo $form->textField($model, 'username'); ?>
		<?php echo $form->error($model, 'username'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'password'); ?>
		<?php echo $form->passwordField($model, 'password'); ?>
		<?php echo $form->error($model, 'password'); ?>
	</div>

	<div class="row rememberMe">
		<?php echo $form->checkBox($model, 'rememberMe', array('checked'=>'checked')); ?>
		<?php echo $form->label($model, 'rememberMe'); ?>
		<?php echo $form->error($model, 'rememberMe'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Login'); ?>
	</div>

	<?php $this->endWidget(); ?>
</div><!-- form -->
