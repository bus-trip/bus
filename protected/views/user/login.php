<?php
/* @var $this UserController */
/* @var $model LoginForm */
/* @var $form CActiveForm */

?>
	<div class="search-form__nav">
		<div class="form-nav">
			<a class="form-nav__item form-nav__item_active" href="/"><span class="form-nav__name">Авторизация</span></a>
			<a class="form-nav__item" href="<?= $this->createUrl('/user/register') ?>"><span
					class="form-nav__name">Регистрация</span></a>
		</div>
	</div>
	<div class="search-form__container">
		<div class="seats-form">
			<p class="title-2 clearfix">Что бы продолжить оформление, пожалуйста, авторизуйтесь или
				зарегистрируйтесь</p>
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
		</div>
	</div>
<?php if (isset($wizard_bus)) { ?>
	<div class="search-form__nav">
		<div class="form-nav">
			<a class="form-nav__item" href="/"><span class="form-nav__icon"><img
						src="/themes/bus/svg/icn-search.svg"></span><span class="form-nav__name">Найти билеты</span></a>
			<a class="form-nav__item form-nav__item_active" href="#"><span class="form-nav__icon"><img
						src="/themes/bus/svg/icn-seat.svg"></span><span class="form-nav__name">Выбрать места</span></a>
			<a class="form-nav__item form-nav__item_disable" href="#"><span class="form-nav__icon"><img
						src="/themes/bus/svg/icn-ticket.svg"></span><span
					class="form-nav__name">Данные билетов</span></a>
			<a class="form-nav__item form-nav__item_disable" href="#"><span class="form-nav__icon"><img
						src="/themes/bus//svg/icn-check.svg"></span><span class="form-nav__name">Проверка</span></a>
		</div>
	</div>
	<div class="search-form__container">
		<div class="seats">
			<?php if ($wizard_bus['schema']) { ?>
				<div class="seats__scheme">
					<p class="title-2 clearfix">Схема мест в автобусе</p>
					<?= CHtml::image($wizard_bus['schema']) ?>
				</div>
			<?php } ?>
			<ul class="seats__list">
				<?php foreach ($wizard_bus['places'] as $item) { ?>
					<li class="seats__choose"><?= preg_replace('#^(\d)#', '№$1:', $item) ?></li>
				<?php } ?>
			</ul>
		</div>
	</div>
<?php } ?>