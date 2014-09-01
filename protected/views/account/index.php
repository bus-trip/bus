<div id="profile-wrapper">
	<ul id="tabs">
		<li><a href="<?php echo $this->createUrl('/account'); ?>">Аккаунт</a></li>
		<li><a href="<?php echo $this->createUrl('/account/edit'); ?>">Редактировать</a></li>
		<li><a href="<?php echo $this->createUrl('/account/passengers'); ?>">Мои профили</a></li>
	</ul>
	<h1><?php echo $this->pageTitle ?></h1>
	<h2>Пользователь: <?php echo $name; ?></h2>
	<?php echo $content ?>
</div>