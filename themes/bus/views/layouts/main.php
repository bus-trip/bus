<?php
$baseUrl = Yii::app()->request->baseUrl;
$clientScript = Yii::app()->getClientScript();
$clientScript
	->registerCssFile($baseUrl . '/css/main.css')
	->registerCssFile($baseUrl . '/css/form.css')
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
			<?php if ($this->isFront()) { ?>
				<div class="header__logo">
					<a href="#top"
					   title="<?php echo CHtml::encode(Yii::app()->name); ?>"><?php echo CHtml::encode(Yii::app()->name); ?></a>
				</div>
				<div class="header__container">
					<div class="header__menu">
						<div class="menu">
							<a class="menu__item menu__item_active" href="#"><span
									class="menu__name">О компании</span></a>
							<a class="menu__item" href="#"><span class="menu__name">Расписание</span></a>
							<a class="menu__item" href="#"><span class="menu__name">Контакты</span></a>
							<a class="menu__item" href="#"><span class="menu__name">Акции</span></a>
						</div>
					</div>
					<div class="header__authorization">
						<div class="authorization">
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
						</div>
					</div>
				</div>
			<?php } else { ?>
				<div class="header__logo">
					<a href="<?= Yii::app()->createUrl('UserInterface/default/index/step/find') ?>"
					   title="<?php echo CHtml::encode(Yii::app()->name); ?>"><?php echo CHtml::encode(Yii::app()->name); ?></a>
				</div>
				<div class="header__container">
					<div class="header__menu">
						<div class="menu">
							<a class="menu__item menu__item_active"
							   href="<?= Yii::app()->createUrl('UserInterface/default/index/step/find') ?>#"><span
									class="menu__name">О компании</span></a>
							<a class="menu__item"
							   href="<?= Yii::app()->createUrl('UserInterface/default/index/step/find') ?>#"><span
									class="menu__name">Расписание</span></a>
							<a class="menu__item"
							   href="<?= Yii::app()->createUrl('UserInterface/default/index/step/find') ?>#"><span
									class="menu__name">Контакты</span></a>
							<a class="menu__item"
							   href="<?= Yii::app()->createUrl('UserInterface/default/index/step/find') ?>#"><span
									class="menu__name">Акции</span></a>
						</div>
					</div>
					<div class="header__authorization">
						<div class="authorization">
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
						</div>
					</div>
				</div>
			<?php } ?>
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
	</div>
	<div class="content">
		<div class="main">
			<?php echo $content; ?>


			<!--			<div class="search-form">-->
			<!--				<h1 class="search-form__title title">Найти билеты</h1>-->
			<!--				<div class="search-form__nav">-->
			<!--					<div class="form-nav">-->
			<!--						<a class="form-nav__item form-nav__item_active" href="/"><span class="form-nav__icon"><img-->
			<!--									src="/f/svg/icn-search.svg"/></span><span class="form-nav__name">Найти билеты</span></a>-->
			<!--						<a class="form-nav__item form-nav__item_disable" href="/seats.html"><span-->
			<!--								class="form-nav__icon"><img src="/f/svg/icn-seat.svg"/></span><span-->
			<!--								class="form-nav__name">Выбрать места</span></a>-->
			<!--						<a class="form-nav__item form-nav__item_disable" href="/profile.html"><span-->
			<!--								class="form-nav__icon"><img src="/f/svg/icn-ticket.svg"/></span><span-->
			<!--								class="form-nav__name">Данные билетов</span></a>-->
			<!--						<a class="form-nav__item form-nav__item_disable" href="/check.html"><span-->
			<!--								class="form-nav__icon"><img src="/f/svg/icn-check.svg"/></span><span-->
			<!--								class="form-nav__name">Проверка</span></a>-->
			<!--					</div>-->
			<!--				</div>-->
			<!--				<div class="search-form__container">-->
			<!--					<div class="direction">-->
			<!--						<div class="direction__item">-->
			<!--							<select>-->
			<!--								<option value="Элиста — Москва">Элиста — Москва</option>-->
			<!--								<option value="Москва — Элиста">Москва — Элиста</option>-->
			<!--							</select>-->
			<!--						</div>-->
			<!--						<div class="direction__item">-->
			<!--							<input type="date">-->
			<!--						</div>-->
			<!--						<div class="direction__item">-->
			<!--							<button class="btn" type="submit">Найти</button>-->
			<!--						</div>-->
			<!--					</div>-->
			<!--					<table class="list-direction">-->
			<!--						<thead>-->
			<!--						<tr>-->
			<!--							<th></th>-->
			<!--							<th>Направление</th>-->
			<!--							<th>Отправление</th>-->
			<!--							<th>Прибытие</th>-->
			<!--							<th>Стоимость</th>-->
			<!--							<th>Мест</th>-->
			<!--						</tr>-->
			<!--						</thead>-->
			<!--						<tbody class="table-direction">-->
			<!--						<tr>-->
			<!--							<td>-->
			<!--								<input type="radio">-->
			<!--							</td>-->
			<!--							<td>Элиста - Москва</td>-->
			<!--							<td class="table-direction__date">-->
			<!--								09.07.2016-->
			<!--								<span>12:07</span>-->
			<!--							</td>-->
			<!--							<td class="table-direction__date">-->
			<!--								10.07.2016-->
			<!--								<span>04:07</span>-->
			<!--							</td>-->
			<!--							<td class="table-direction__cost">2200 ₽</td>-->
			<!--							<td>15</td>-->
			<!--						</tr>-->
			<!--						</tbody>-->
			<!--					</table>-->
			<!--				</div>-->
			<!--				<div class="search-form__footer">-->
			<!--					<a class="search-form__next-step btn" href="/seats.html">Выбрать</a>-->
			<!--				</div>-->
			<!--			</div>-->
		</div>
	</div>
	<div class="footer">
		<div class="footer__wrapper">
			<div class="footer__copyright">© 2016, OOO «Спринт-Тур». Все права защищены. «Спринт-Тур» — Онлайн сервис
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
<!--<script src="/f/js/script.js"></script>-->
</body>
</html>