<?php
/* @var $this AccountController */

$this->breadcrumbs = array(
	'Аккаунт' => array('/account'),
	'Мои профили',
);
?>

<p><b><a href="<?php print $this->createUrl('/account/passengers/add'); ?>">Добавить профиль</a></b></p>

<div id="passengers">
	<?php foreach ($profiles as $profile) { ?>
		<div class="item clearfix" data-id="<?php print $profile->id; ?>">
			<div class="name"><span><?php print $profile->shortName(); ?></span></div>
			<div class="rule">
				<a href="<?php print $this->createUrl('/account/passengers/edit/' . $profile->id); ?>"
				   class="edit">Редактировать</a>
				<a href="<?php print $this->createUrl('/account/passengers/delete/' . $profile->id); ?>"
				   calss="remove">Удалить</a></div>
			<div class="data">
				<?php print $this->renderPartial('one_passenger_data', array('data' => $profile), TRUE); ?>
			</div>
		</div>
	<?php } ?>

</div>
