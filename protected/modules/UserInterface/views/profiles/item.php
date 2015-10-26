<?php
/**
 * Part of bus 2015
 * Created by: Александр on 07.06.2015:23:27
 *
 * @var     $profileModel Profiles
 * @var int $i
 */
?>
<?php if (!empty($userProfiles)) { ?>
	<div class="select-profile-wrap">
		<select class="select-profile" name="profile_<?php echo $i ?>">
			<option value="new">- Новый -</option>
			<?php foreach ($userProfiles as $userProfile) {
				/** @var Profiles $userProfile */
				?>
				<option value="<?php
				echo $userProfile->id ?>"><?php echo $userProfile->shortName() ?></option>
			<?php } ?>
		</select>
	</div>
<?php } ?>

<div class="row">
	<?php echo CHtml::activeLabelEx($profileModel, '[' . $i . ']last_name'); ?>
	<?php echo CHtml::activeTextField($profileModel, '[' . $i . ']last_name'); ?>
	<?php echo CHtml::error($profileModel, '[' . $i . ']last_name'); ?>
</div>

<div class="row">
	<?php echo CHtml::activeLabelEx($profileModel, '[' . $i . ']name'); ?>
	<?php echo CHtml::activeTextField($profileModel, '[' . $i . ']name'); ?>
	<?php echo CHtml::error($profileModel, '[' . $i . ']name'); ?>
</div>

<div class="row">
	<?php echo CHtml::activeLabelEx($profileModel, '[' . $i . ']middle_name'); ?>
	<?php echo CHtml::activeTextField($profileModel, '[' . $i . ']middle_name'); ?>
	<?php echo CHtml::error($profileModel, '[' . $i . ']middle_name'); ?>
</div>


<div class="row">
	<?php echo CHtml::activeLabelEx($profileModel, '[' . $i . ']doc_type'); ?>
	<?php echo CHtml::activeDropDownList($profileModel, '[' . $i . ']doc_type', [Profiles::DOC_PASSPORT          => 'Паспорт',
																				 Profiles::DOC_BIRTH_CERTIFICATE => 'Свидетельство о рождении',
																				 Profiles::DOC_FOREIGN_PASSPORT  => 'Загран паспорт',
																				 Profiles::DOC_MILITARY_ID       => 'Военный билет']); ?>
	<?php echo CHtml::error($profileModel, '[' . $i . ']doc_type'); ?>
</div>

<div class="row">
	<?php echo CHtml::activeLabelEx($profileModel, '[' . $i . ']doc_num'); ?>
	<?php echo CHtml::activeTextField($profileModel, '[' . $i . ']doc_num'); ?>
	<?php echo CHtml::error($profileModel, '[' . $i . ']doc_num'); ?>
</div>

<div class="row">
	<?php echo CHtml::activeLabelEx($profileModel, '[' . $i . ']phone'); ?>
	<?php echo CHtml::activeTextField($profileModel, '[' . $i . ']phone', ['class' => 'phone']); ?>
	<?php echo CHtml::error($profileModel, '[' . $i . ']phone'); ?>
</div>

<div class="row">
	<?php echo CHtml::activeLabelEx($profileModel, '[' . $i . ']sex'); ?>
	<?php echo CHtml::activeDropDownList($profileModel, '[' . $i . ']sex', ['none'               => '-Выберите-',
																			Profiles::SEX_MALE   => 'Мужской',
																			Profiles::SEX_FEMALE => 'Женский']); ?>
	<?php echo CHtml::error($profileModel, '[' . $i . ']sex'); ?>
</div>

<div class="row datapiker-wrap">
	<?php echo CHtml::activeLabelEx($profileModel, '[' . $i . ']birth'); ?>
	<?php $this->widget('zii.widgets.jui.CJuiDatePicker', [
		'model'     => $profileModel,
		'attribute' => '[' . $i . ']birth',
		'language'  => 'ru',
		'options'   => [
			'altFormat' => 'd.m.Y',
		]
	]) ?>
	<?php echo CHtml::error($profileModel, '[' . $i . ']birth'); ?>
</div>