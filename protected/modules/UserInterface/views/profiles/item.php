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
	<div class="profile__field">
		<label
			class="profile__label"></label>
		<div class="select-profile-wrap">
			<select class="profile__select select-profile" name="profile_<?php echo $i ?>"
					data-placeholder="Выберите">
				<option value="new">- Новый -</option>
				<?php foreach ($userProfiles as $userProfile) {
					/** @var Profiles $userProfile */
					?>
					<option value="<?php
					echo $userProfile->id ?>"><?php echo $userProfile->shortName() ?></option>
				<?php } ?>
			</select>
		</div>
	</div>
<?php } ?>
<div class="profile__form">
	<div class="profile__col">
		<div class="profile__field">
			<label
				class="profile__label"><?php echo CHtml::activeLabelEx($profileModel, '[' . $i . ']last_name'); ?></label>
			<?php echo CHtml::activeTextField($profileModel, '[' . $i . ']last_name', ['class' => 'profile__input']); ?>
			<?php echo CHtml::error($profileModel, '[' . $i . ']last_name'); ?>
		</div>
		<div class="profile__field">
			<label
				class="profile__label"><?php echo CHtml::activeLabelEx($profileModel, '[' . $i . ']name'); ?></label>
			<?php echo CHtml::activeTextField($profileModel, '[' . $i . ']name', ['class' => 'profile__input']); ?>
			<?php echo CHtml::error($profileModel, '[' . $i . ']name'); ?>
		</div>
		<div class="profile__field">
			<label
				class="profile__label"><?php echo CHtml::activeLabelEx($profileModel, '[' . $i . ']middle_name'); ?></label>
			<?php echo CHtml::activeTextField($profileModel, '[' . $i . ']middle_name', ['class' => 'profile__input']); ?>
			<?php echo CHtml::error($profileModel, '[' . $i . ']middle_name'); ?>
		</div>
		<div class="profile__field">
			<label
				class="profile__label"><?php echo CHtml::activeLabelEx($profileModel, '[' . $i . ']sex'); ?></label>
			<?php echo CHtml::activeDropDownList($profileModel, '[' . $i . ']sex', ['none'               => '-Выберите-',
																					Profiles::SEX_MALE   => 'Мужской',
																					Profiles::SEX_FEMALE => 'Женский'],
												 ['class' => "profile__select", 'data-placeholder' => "Выберите"]); ?>
			<?php echo CHtml::error($profileModel, '[' . $i . ']sex'); ?>
		</div>
	</div>
	<div class="profile__col">
		<div class="profile__field">
			<label
				class="profile__label"><?php echo CHtml::activeLabelEx($profileModel, '[' . $i . ']birth'); ?></label>
			<div class="row datapiker-wrap">
				<?php $this->widget('zii.widgets.jui.CJuiDatePicker', [
					'model'       => $profileModel,
					'attribute'   => '[' . $i . ']birth',
					'language'    => 'ru',
					'options'     => [
						'showAnim'    => 'fadeIn',//'slide','fold','slideDown','fadeIn','blind','bounce','clip','drop'
						'changeMonth' => true,
						'changeYear'  => true,
						'yearRange'   => (date('Y') - 100) . ':' . date('Y'),
						'altFormat'   => 'd.m.Y',
						'maxDate'     => date('d.m.Y'),
					],
					'htmlOptions' => [
						'class'    => 'input-text  input-text_fz-s',
						'readonly' => 'readonly'
					]
				]) ?>
				<?php echo CHtml::error($profileModel, '[' . $i . ']birth'); ?>
			</div>
		</div>
		<div class="profile__field">
			<label
				class="profile__label"><?php echo CHtml::activeLabelEx($profileModel, '[' . $i . ']phone'); ?></label>
			<?php echo CHtml::activeTextField($profileModel, '[' . $i . ']phone', ['class' => 'phone profile__input']); ?>
			<?php echo CHtml::error($profileModel, '[' . $i . ']phone'); ?>
		</div>
		<div class="profile__field">
			<label
				class="profile__label"><?php echo CHtml::activeLabelEx($profileModel, '[' . $i . ']doc_type'); ?></label>
			<?php echo CHtml::activeDropDownList($profileModel, '[' . $i . ']doc_type', [Profiles::DOC_PASSPORT          => 'Паспорт РФ',
																						 Profiles::DOC_BIRTH_CERTIFICATE => 'Свидетельство о рождении',
																						 Profiles::DOC_FOREIGN_PASSPORT  => 'Загран паспорт',
																						 Profiles::DOC_MILITARY_ID       => 'Военный билет',
																						 Profiles::DOC_OTHER_PASSPORT    => 'Паспорт другой страны'],
												 ['class' => "profile__select", 'data-placeholder' => "Выберите", 'onChange' => 'setDocNumMask(this)']); ?>
			<?php echo CHtml::error($profileModel, '[' . $i . ']doc_type'); ?>
		</div>
		<div class="profile__field">
			<label
				class="profile__label"><?php echo CHtml::activeLabelEx($profileModel, '[' . $i . ']doc_num'); ?></label>
			<?php echo CHtml::activeTextField($profileModel, '[' . $i . ']doc_num', ['class' => "doc_num input-text input-text_fz-s"]); ?>
			<?php echo CHtml::error($profileModel, '[' . $i . ']doc_num'); ?>
		</div>
	</div>
</div>