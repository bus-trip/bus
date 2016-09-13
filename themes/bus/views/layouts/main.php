<?php
$baseUrl = Yii::app()->request->baseUrl;

/** @var CClientScript $clientScript */
$clientScript = Yii::app()->getClientScript();
$clientScript
//	->registerCssFile($baseUrl . '/css/main.css')
	->registerCssFile($baseUrl . '/themes/bus/css/fonts.css')
	->registerCssFile($baseUrl . '/themes/bus/css/style.css')
	->registerScriptFile($baseUrl . '/themes/bus/js/plugins.js')
	->registerScriptFile($baseUrl . '/themes/bus/js/singlepagenav.jquery.js')
	->registerScriptFile($baseUrl . '/themes/bus/js/main.js')
	->registerScriptFile($baseUrl . '/themes/bus/js/fonts.js')
	->registerScriptFile($baseUrl . '/themes/bus/js/dynamic.js');
?>
<!DOCTYPE html>
<html>
<head>
	<title>Спринт-тур</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css"
		  media="screen, projection"/>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css"
		  media="print"/>
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css"
		  media="screen, projection"/>
	<![endif]-->
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,700,400italic&subset=latin,cyrillic'
		  rel='stylesheet' type='text/css'>
</head>
<body>
<div class="page">
	<div class="header">
		<div class="header__wrapper">
			<div class="header__logo">
				<a href="/"
				   title="<?php echo CHtml::encode(Yii::app()->name); ?>"><?php echo CHtml::encode(Yii::app()->name); ?></a>
			</div>
			<div class="header__container">
				<div class="header__menu">
					<div class="menu">
						<?php $url = Yii::app()->request->url; ?>

						<a class="menu__item<?= $url === $this->createUrl('/about') ? ' menu__item_active' : '' ?>"
						   href="<?= $this->createUrl('/about') ?>"><span
								class="menu__name">О компании</span></a>
						<a class="menu__item<?= $url === $this->createUrl('/bus-schedule') ? ' menu__item_active' : '' ?>"
						   href="<?= $this->createUrl('/bus-schedule') ?>"><span class="menu__name">Расписание</span></a>
						<a class="menu__item<?= $url === $this->createUrl('/contacts') ? ' menu__item_active' : '' ?>"
						   href="<?= $this->createUrl('/contacts') ?>"><span class="menu__name">Контакты</span></a>
						<a class="menu__item<?= $url === $this->createUrl('/actions') ? ' menu__item_active' : '' ?>"
						   href="<?= $this->createUrl('/actions') ?>"><span
								class="menu__name">Акции</span></a>
					</div>
				</div>
				<div class="header__authorization">
					<div class="authorization">
						<?php if ($this->isFront()) { ?>
							<?php if (Yii::app()->user->isGuest) { ?>
								<div class="authorization__reg">
									<a href="<?php echo $this->createUrl('/user/register') ?>"
									   rel="nofollow">Регистрация</a>
								</div>
								<div class="authorization__login">
									<a href="<?php echo $this->createUrl('/user/login') ?>" rel="nofollow">Вход</a>
								</div>
							<?php } else { ?>
								<div class="authorization__reg">
									<a href="<?php echo $this->createUrl('/account'); ?>" rel="nofollow">Личный
										кабинет</a>&nbsp;(<?php echo Yii::app()->user->name; ?>)
								</div>
								<div class="authorization__login">
									<a href="<?php echo $this->createUrl('/user/logout') ?>" rel="nofollow">Выход</a>
								</div>
							<?php } ?>
						<?php } else { ?>
							<?php if (Yii::app()->user->isGuest) { ?>
								<div class="authorization__reg">
									<a href="<?php echo $this->createUrl('/user/register') ?>" rel="nofollow">Регистрация</a>
								</div>
								<div class="authorization__login">
									<a href="<?php echo $this->createUrl('/user/login') ?>" rel="nofollow">Вход</a>
								</div>
							<?php } else { ?>
								<div class="authorization__reg">
									<a href="<?php echo $this->createUrl('/account'); ?>" rel="nofollow">Личный
										кабинет</a>&nbsp;(<?php echo Yii::app()->user->name; ?>)
								</div>
								<div class="authorization__login">
									<a href="<?php echo $this->createUrl('/user/logout') ?>" rel="nofollow">Выход</a>
								</div>
							<?php } ?>
						<?php } ?>
					</div>
				</div>
				<?php if (Yii::app()->user->name === 'admin') { ?>
					<?php $this->widget('zii.widgets.CMenu', [
						'items' => [
							['label' => 'Рейсы на месяц', 'url' => ['/admin/index']],
							['label' => 'Автобусы', 'url' => ['/buses/admin']],
							['label' => 'Рейсы', 'url' => ['/trips/admin/status/actual']],
							['label' => 'Направления', 'url' => ['/directions/admin']],
							['label' => 'Все пассажиры', 'url' => ['/tickets/passengers?Profiles_sort=last_name']],
							['label' => 'Оформление билета', 'url' => ['/']],
						],
					]); ?>
				<?php } ?>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="main">
			<?php echo $content; ?>
		</div>
	</div>
	<div class="footer">
		<div class="footer__wrapper">
			<div class="footer__copyright">© 2016, OOO «Спринт-Тур». Все права защищены. «Спринт-Тур» — Онлайн
				сервис
				продажи билетов
			</div>
			<div class="footer__contacts">
				<div class="footer__item">
					<a class="footer__tel" href="tel:+7 905 400-04-68">+7 905 400-04-68</a>
				</div>
				<div class="footer__item">
					<a class="footer__email" href="mail:info@sprint-tour-08.ru">info@sprint-tour-08.ru</a>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="popup"></div>
<!--<link href="./f/css/style.css" rel="stylesheet">-->
<!--<script src="//ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>-->
<!--<script src="../js/script.js"></script>-->
</body>
</html>