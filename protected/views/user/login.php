<?php
/* @var $this UserController */
/* @var $model LoginForm */
/* @var $form CActiveForm */

?>

<?php if (isset($wizard_bus)) { ?>
	<p class="title-2">Схема мест в автобусе</p>
	<div class="grid grid_jc-c border-bottom">
		<?php if ($wizard_bus['schema']) { ?>
			<div class="grid__item grid__item_xs-12 grid__item_s-12 grid__item_m-auto">
				<div class="img-scheme">
					<?= CHtml::image($wizard_bus['schema']) ?>
				</div>
			</div>
		<?php } ?>
		<?php
		$places = array_chunk($wizard_bus['places'], 8, true);
		foreach ($places as $place_col) { ?>
			<div class="grid__item grid__item_xs-12 grid__item_s-auto">
				<?php foreach ($place_col as $item) { ?>
					<div class="bus-place"><?= preg_replace('#^(\d)#', '№$1:', $item) ?></div>
				<?php } ?>
			</div>
		<?php } ?>
	</div>

	<p class="title-2">Что бы продолжить оформление, пожалуйста, авторизуйтесь или зарегистрируйтесь</p>
<?php } ?>
<ul class="nav">
	<li><span class="link link_active">Авторизация</span></li>
	<li><a href="<?= $this->createUrl('/user/register') ?>" class="link">Регистрация</a></li>
</ul>
<div class="form">
	<?php $form = $this->beginWidget('CActiveForm', array(
		'id' => 'login-form',
	)); ?>

	<p class="note">Поля с <span class="required">*</span> являются обязательными.</p>

	<div class="row">
		<?php echo $form->labelEx($model, 'username'); ?>
		<?php echo $form->textField($model, 'username', ['class' => "input-text"]); ?>
		<?php echo $form->error($model, 'username'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'password'); ?>
		<?php echo $form->passwordField($model, 'password', ['class' => "input-text"]); ?>
		<?php echo $form->error($model, 'password'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Войти', ['class' => "btn"]); ?>
	</div>

	<div>
		<a href="<?= $this->createUrl('/user/recover') ?>">Забыли пароль?</a>
	</div>

	<?php $this->endWidget(); ?>
</div><!-- form -->
