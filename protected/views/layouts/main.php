<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="language" content="en"/>

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css"
		  media="screen, projection"/>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css"
		  media="print"/>
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css"
		  media="screen, projection"/>
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css"/>

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<div class="container" id="page">

	<div id="header">
		<div id="logo" class="left"><?php echo CHtml::encode(Yii::app()->name); ?></div>

		<ul class="right">
			<?php if (Yii::app()->user->isGuest) { ?>
<!--				<li><a href="--><?php //echo $this->createUrl('/user/register') ?><!--" rel="nofollow">Регистрация</a></li>-->
				<li><a href="<?php echo $this->createUrl('/user/login') ?>" rel="nofollow">Вход</a></li>
			<?php } else { ?>
				<li><a href="<?php echo $this->createUrl('/account'); ?>" rel="nofollow">Личный
						кабинет</a>&nbsp;(<?php echo Yii::app()->user->name; ?>)
				</li>
				<li><a href="<?php echo $this->createUrl('/user/logout') ?>" rel="nofollow">Выход</a></li>
			<?php } ?>
		</ul>

		<div style="clear:both"></div>
	</div>

	<!-- header -->

	<div id="mainmenu">
		<?php
            if(Yii::app()->user->name == 'admin'){
            $this->widget('zii.widgets.CMenu', array(
			    'items' => array(
				    array('label' => 'Главная', 'url' => array('/admin/index')),
                    array('label' => 'Автобусы', 'url' => array('/buses/admin')),
                    array('label' => 'Рейсы', 'url' => array('/trips/admin/status/actual')),
//                    array('label' => 'Расписания', 'url' => array('/schedule/admin')),
                    array('label' => 'Направления', 'url' => array('/directions/admin')),
                    array('label' => 'Все пассажиры', 'url' => array('/tickets/passengers?Profiles_sort=last_name')),
			    ),
		    ));
            }
        ?>
	</div>
	<!-- mainmenu -->
	<?php if (isset($this->breadcrumbs)): ?>
		<?php $this->widget('application.widgets.Breadcrumbs', array(
			'links' => $this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
	<?php endif ?>
		<?php if (Yii::app()->user->hasFlash('success')) { ?>
			<div class="wrapper">
				<div class="flash-success"><?php echo Yii::app()->user->getFlash('success'); ?></div>
			</div>
		<?php
		}
		if (Yii::app()->user->hasFlash('error')) {
			?>
			<div class="wrapper">
				<div class="flash-error"><?php echo Yii::app()->user->getFlash('error'); ?></div>
			</div>
		<?php
		}
		if (Yii::app()->user->hasFlash('notice')) {
			?>
			<div class="wrapper">
			<div class="flash-notice"><?php echo Yii::app()->user->getFlash('notice'); ?></div>
			</div>
		<?php } ?>

	<?php echo $content; ?>

	<div class="clear"></div>

	<div id="footer">

	</div>
	<!-- footer -->

</div>
<!-- page -->

</body>
</html>
