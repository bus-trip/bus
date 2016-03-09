<?php
/* @var $this UserController */
/* @var $model LoginForm */
/* @var $form CActiveForm */

?>
<ul class="nav">
	<li><span class="link link_active">Авторизация</span></li>
	<li><a href="<?= $this->createUrl('/user/register')?>" class="link">Регистрация</a></li>
</ul>
<div class="form">
	<?php $form = $this->beginWidget('CActiveForm', array(
		'id' => 'login-form',
	)); ?>

	<p class="note">Поля с <span class="required">*</span> являются обязательными.</p>

	<div class="row">
		<?php echo $form->labelEx($model, 'username'); ?>
		<?php echo $form->textField($model, 'username',['class'=>"input-text"]); ?>
		<?php echo $form->error($model, 'username'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'password'); ?>
		<?php echo $form->passwordField($model, 'password', ['class'=>"input-text"]); ?>
		<?php echo $form->error($model, 'password'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Войти', ['class'=>"btn"]); ?>
	</div>

	<div>
		<a href="<?= $this->createUrl('/user/recover') ?>">Забыли пароль?</a>
	</div>

	<?php $this->endWidget(); ?>
</div><!-- form -->
