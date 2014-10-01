<?php foreach ($data as $item) { ?>
	<div class="profile-data hide" data-id="<?php print $item['id']; ?>"><?php print $item['data']; ?></div>
<?php } ?>
<div class="profile-data hide" data-id="new">Новый профайл.......

	<?php $this->renderPartial('application.views.account.one_passenger', array('model' => new Profiles(), 'trip' => TRUE)); ?>

</div>