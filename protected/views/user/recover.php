<?php
/* @var $this UserController */
/* @var $model LoginForm */
/* @var $form CActiveForm */

?>

<ul class="nav">
	<li><a href="<?= $this->createUrl('/user/login')?>" class="link">Авторизация</a></li>
	<li><a href="<?= $this->createUrl('/user/register')?>" class="link">Регистрация</a></li>
</ul>
<div class="form">
	<?php $form = $this->beginWidget('CActiveForm', array(
		'id' => 'recover-form',
	)); ?>

	<div class="row">
		<?php echo $form->labelEx($model, 'login'); ?>
		<?php echo $form->textField($model, 'login',['class'=>"input-text"]); ?>
		<?php echo $form->error($model, 'login'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Восстановить', ['class' => "btn"]); ?>
	</div>

	<?php $this->endWidget(); ?>
</div><!-- form -->
