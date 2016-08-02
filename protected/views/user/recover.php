<?php
/* @var $this UserController */
/* @var $model LoginForm */
/* @var $form CActiveForm */

?>

<div class="search-form__nav">
	<div class="form-nav">
		<a class="form-nav__item" href="<?= $this->createUrl('/user/login') ?>"><span
				class="form-nav__name">Авторизация</span></a>
		<a class="form-nav__item" href="<?= $this->createUrl('/user/register') ?>"><span
				class="form-nav__name">Регистрация</span></a>
	</div>
</div>
<div class="search-form__container">
	<div class="seats-form">
		<div class="form">
			<?php $form = $this->beginWidget('CActiveForm', array(
				'id' => 'recover-form',
			)); ?>

			<div class="row">
				<?php echo $form->labelEx($model, 'login'); ?>
				<?php echo $form->textField($model, 'login', ['class' => "input-text"]); ?>
				<?php echo $form->error($model, 'login'); ?>
			</div>

			<div class="row buttons">
				<?php echo CHtml::submitButton('Восстановить', ['class' => "btn"]); ?>
			</div>

			<?php $this->endWidget(); ?>
		</div><!-- form -->
	</div>
</div>
