<div class="itrip">
	<h2>Билет</h2>
	<?php print $trip; ?>

	<div class="qty">
		Количество мест: <?php print $qty; ?>
	</div>
</div>

<?php $form = $this->beginWidget('CActiveForm', array(
	'enableAjaxValidation' => FALSE,
	'action'               => $this->createUrl('/s/ticket')
));
?>

<div class="profiles">
	<h2>Профили</h2>
	<?php
	$i = 0;
	while ($i < $qty) {
		?>
		<div class="item-profile" data-item="<?php print $i; ?>">
			<select class="chose-profile" name="profile-<?php print $i; ?>">
				<option value="none">- Выберите -</option>
				<?php foreach ($profile_data as $item) { ?>
					<option value="<?php print $item['id']; ?>"><?php print $item['name']; ?></option>
				<?php } ?>
				<option value="new">Другой...</option>
			</select>
			<?php print $chose_profile; ?>
		</div>
		<?php $i++;
	} ?>
</div>


<div class="row buttons">
	<?php echo CHtml::submitButton('Бронировать'); ?>
</div>

<?php $this->endWidget(); ?>