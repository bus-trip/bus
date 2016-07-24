<?php
/*
 * @var $this Controller
 */

$baseUrl      = Yii::app()->request->baseUrl;
$clientScript = Yii::app()->getClientScript();
$clientScript
	->registerCssFile($baseUrl . '/css/main.css')
	->registerCssFile($baseUrl . '/css/form.css')
	->registerCssFile($baseUrl . '/themes/bus/css/style.css')
	->registerScriptFile($baseUrl . '/themes/bus/js/plugins.js')
	->registerScriptFile($baseUrl . '/themes/bus/js/singlepagenav.jquery.js')
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
			<?php if ($this->isFront()) { ?>
				<div class="logo">
					<a href="#top"
					   title="<?php echo CHtml::encode(Yii::app()->name); ?>"><?php echo CHtml::encode(Yii::app()->name); ?></a>
				</div>
				<ul class="nav-main front">
					<li><a href="#about" class="link link_c-white">О компании</a></li>
					<li><a href="#actions" class="link link_c-white">Акции</a></li>
					<li><a href="#contacts" class="link link_c-white">Контакты</a></li>
				</ul>
			<?php } else { ?>
				<div class="logo">
					<a href="<?= Yii::app()->createUrl('UserInterface/default/index/step/find')?>"
					   title="<?php echo CHtml::encode(Yii::app()->name); ?>"><?php echo CHtml::encode(Yii::app()->name); ?></a>
				</div>
				<ul class="nav-main">
					<li><a href="<?= Yii::app()->createUrl('UserInterface/default/index/step/find')?>#about" class="link link_c-white">О компании</a></li>
					<li><a href="<?= Yii::app()->createUrl('UserInterface/default/index/step/find')?>#actions" class="link link_c-white">Акции</a></li>
					<li><a href="<?= Yii::app()->createUrl('UserInterface/default/index/step/find')?>#contacts" class="link link_c-white">Контакты</a></li>
				</ul>
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

<div class="content-form" id="top">
	<div class="content-form__layout">
		<?php echo $content; ?>
	</div>
</div>
<?php

//if ($this->isFront()) { ?>
<!--	<div class="content">-->
<!--		<div class="content__layout">-->
<!--			<h2 class="title title_ta-c title_fz-l" id="about">О компании</h2>-->
<!--			<div class="content__text">-->
<!--				<div class="content__image">-->
<!--					<img src="--><?php //echo Yii::app()->theme->getBaseUrl(); ?><!--/img/img_bus.jpg" alt="Автобус">-->
<!--				</div>-->
<!--				<div class="content__desc">-->
<!--					<h3 class="title title_ta-l title_fz-s">-->
<!--						Ежедневные поздки Элиста - Москва - Элиста-->
<!--					</h3>-->
<!--					<p class="text">-->
<!--						Коневодство ежегодно. Оазисное земледелие входит бесплатный расовый состав. Для пользования-->
<!--						телефоном-автоматом необходимы разменные монеты, однако субэкваториальный климат непосредственно-->
<!--						декларирует бальнеоклиматический курорт. Отгонное животноводство, несмотря на внешние-->
<!--						воздействия, точно применяет кандым.-->
<!--					</p>-->
<!--					<ul class="text">-->
<!--						<li>отправление из Элисты в 12:00</li>-->
<!--						<li>отправление из Москвы в 12:00</li>-->
<!--					</ul>-->
<!--				</div>-->
<!--			</div>-->
<!---->
<!--			<div class="advantage">-->
<!--				<div class="advantage__item">-->
<!--					<div class="advantage__image"></div>-->
<!--					<p class="advantage__title">wi-fi</p>-->
<!--				</div>-->
<!--				<div class="advantage__item">-->
<!--					<div class="advantage__image"></div>-->
<!--					<p class="advantage__title">подушки и пледы</p>-->
<!--				</div>-->
<!--				<div class="advantage__item">-->
<!--					<div class="advantage__image"></div>-->
<!--					<p class="advantage__title">Телевизор</p>-->
<!--				</div>-->
<!--				<div class="advantage__item">-->
<!--					<div class="advantage__image"></div>-->
<!--					<p class="advantage__title">Вода</p>-->
<!--				</div>-->
<!--				<div class="advantage__item">-->
<!--					<div class="advantage__image"></div>-->
<!--					<p class="advantage__title">Бесплатное такси <br>в пределах МКАД</p>-->
<!--				</div>-->
<!--			</div>-->
<!---->
<!--			<div class="content__text">-->
<!--				<div class="content__desc">-->
<!--					<p class="text">-->
<!--						Альбатрос прекрасно применяет вечнозеленый кустарник, а из холодных закусок можно выбрать-->
<!--						плоские колбасы "луканка" и "суджук". Флора и фауна перевозит вулканизм.-->
<!--					</p>-->
<!--				</div>-->
<!--			</div>-->
<!---->
<!--			<h2 class="title title_ta-c title_fz-l" id="actions">Акции</h2>-->
<!---->
<!--			<div class="content__text">-->
<!--				<div class="content__desc">-->
<!--					<p class="text">-->
<!--						Из первых блюд распространены супы-пюре и бульоны, но подают их редко, тем не менее производство-->
<!--						зерна и зернобобовых недоступно превышает символический центр современного Лондона, ни для кого-->
<!--						не секрет, что Болгария славится масличными розами, которые цветут по всей Казанлыкской долине.-->
<!--					</p>-->
<!--					<a href="#" class="btn btn_bg-pink">Подробнее</a>-->
<!--				</div>-->
<!--				<div class="content__image">-->
<!--					<img src="--><?php //echo Yii::app()->theme->getBaseUrl(); ?><!--/img/img_bus-1.jpg" alt="Автобус">-->
<!--				</div>-->
<!--			</div>-->
<!--			<h2 class="title title_ta-c title_fz-l" id="contacts">Контакты</h2>-->
<!---->
<!--			<div class="content__text">-->
<!--				<div class="content__image">-->
<!--					<script type="text/javascript" charset="utf-8" async-->
<!--							src="https://api-maps.yandex.ru/services/constructor/1.0/js/?sid=cv_Cgnxo2kS8QKBMoGerEbcBT0OHIX5O&width=448&height=277&lang=ru_RU&sourceType=constructor&scroll=true"></script>-->
<!--				</div>-->
<!--				<div class="content__desc">-->
<!--					<p class="text">-->
<!--						БРОНИРОВАНИЕ ПО НОМЕРУ +7-905-400-0468-->
<!--					</p>-->
<!--				</div>-->
<!--			</div>-->
<!--		</div>-->
<!--	</div>-->
<?php //} ?>
</body>
</html>
