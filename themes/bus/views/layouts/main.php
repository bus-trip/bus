<?php
/*
 * @var $this Controller
 */

$baseUrl      = Yii::app()->request->baseUrl;
$clientScript = Yii::app()->getClientScript();
$clientScript
	->registerCssFile($baseUrl . '/css/main.css')
	->registerCssFile($baseUrl . '/css/form.css')
	->registerCssFile($baseUrl . '/themes/bus/css/main.css')
	->registerScriptFile($baseUrl . '/themes/bus/js/plugins.js')
	->registerScriptFile($baseUrl . '/themes/bus/js/main.js');
?>

<!DOCTYPE html>
<html lang="ru">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width">
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

<div class="header">
	<div class="header__layout">
		<div class="header__item">
			<div class="logo">
				<a href="/"
				   title="<?php echo CHtml::encode(Yii::app()->name); ?>"><?php echo CHtml::encode(Yii::app()->name); ?></a>
			</div>
			<ul class="nav-main">
				<li><a href="#" class="link link_c-white link_active">О компании</a></li>
				<li><a href="#" class="link link_c-white">Акции</a></li>
				<li><a href="#" class="link link_c-white">Контакты</a></li>
			</ul>
			<?php if (Yii::app()->user->name == 'admin') { ?>
				<?php $this->widget('zii.widgets.CMenu', [
					'items' => [
						['label' => 'Рейсы на месяц', 'url' => ['/admin/index']],
						['label' => 'Автобусы', 'url' => ['/buses/admin']],
						['label' => 'Рейсы', 'url' => ['/trips/admin/status/actual']],
						['label' => 'Направления', 'url' => ['/directions/admin']],
						['label' => 'Все пассажиры', 'url' => ['/tickets/passengers?Profiles_sort=last_name']],
						['label' => 'Оформление билета', 'url' => ['/UserInterface/default/index']],
					],
				]); ?>
			<?php } ?>
		</div>
		<div class="header__item">
			<ul class="login-block">
				<?php if (Yii::app()->user->isGuest) { ?>
					<li><a href="<?php echo $this->createUrl('/user/register') ?>" rel="nofollow">Регистрация</a></li>
					<li><a href="<?php echo $this->createUrl('/user/login') ?>" rel="nofollow">Вход</a></li>
				<?php } else { ?>
					<li><a href="<?php echo $this->createUrl('/account'); ?>" rel="nofollow">Личный
							кабинет</a>&nbsp;(<?php echo Yii::app()->user->name; ?>)
					</li>
					<li><a href="<?php echo $this->createUrl('/user/logout') ?>" rel="nofollow">Выход</a></li>
				<?php } ?>
			</ul>
		</div>
	</div>
</div>

<div class="content-form">
	<div class="content-form__layout">
		<?php echo $content; ?>
	</div>
</div>
<?php

if ($this->isFront()) { ?>
	<div class="content">
		<div class="content__layout">
			<h2 class="title title_ta-c title_fz-m">О компании</h2>
		</div>
	</div>
<?php } ?>
</body>
</html>
