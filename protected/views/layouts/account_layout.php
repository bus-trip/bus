<?php
/**
 * Part of bus 2015
 * Created by: Александр on 14.04.2015:2:45
 *
 * @var        $this AccountController
 * @var string $content
 */

$this->beginContent('//layouts/column1');
?>
	<div id="profile-wrapper">
		<ul id="tabs">
			<li><a href="<?php echo $this->createUrl('/account'); ?>">Аккаунт</a></li>
			<li><a href="<?php echo $this->createUrl('/account/edit'); ?>">Редактировать</a></li>
			<li><a href="<?php echo $this->createUrl('/account/passengers'); ?>">Мои профили</a></li>
		</ul>
		<h1><?php echo $this->pageTitle ?></h1>

		<h2>Пользователь: <?php echo $this->user->login; ?></h2>

		<?= $content ?>
	</div>
<?php $this->endContent();