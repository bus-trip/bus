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
	<tr>
		<td class="form-table__name"><label>&nbsp;</label></td>
		<td>
			<div class="select-profile-wrap">
				<select class="select-profile select select_fz-s" name="profile_<?php echo $i ?>"
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
		</td>
	</tr>
<?php } ?>

<tr>
	<td class="form-table__name"><?php echo CHtml::activeLabelEx($profileModel, '[' . $i . ']last_name'); ?></td>
	<td>
		<?php echo CHtml::activeTextField($profileModel, '[' . $i . ']last_name', ['class' => 'input-text input-text_fz-s']); ?>
		<?php echo CHtml::error($profileModel, '[' . $i . ']last_name'); ?>
	</td>
</tr>
<tr>
	<td class="form-table__name"><?php echo CHtml::activeLabelEx($profileModel, '[' . $i . ']name'); ?></td>
	<td>
		<?php echo CHtml::activeTextField($profileModel, '[' . $i . ']name', ['class' => "input-text input-text_fz-s"]); ?>
		<?php echo CHtml::error($profileModel, '[' . $i . ']name'); ?>
	</td>
</tr>
<tr>
	<td class="form-table__name"><?php echo CHtml::activeLabelEx($profileModel, '[' . $i . ']middle_name'); ?></td>
	<td>
		<?php echo CHtml::activeTextField($profileModel, '[' . $i . ']middle_name', ['class' => "input-text input-text_fz-s"]); ?>
		<?php echo CHtml::error($profileModel, '[' . $i . ']middle_name'); ?>
	</td>
</tr>
<tr>
	<td class="form-table__name"><?php echo CHtml::activeLabelEx($profileModel, '[' . $i . ']doc_type'); ?></td>
	<td>
		<?php echo CHtml::activeDropDownList($profileModel, '[' . $i . ']doc_type', [Profiles::DOC_PASSPORT          => 'Паспорт РФ',
																					 Profiles::DOC_BIRTH_CERTIFICATE => 'Свидетельство о рождении',
																					 Profiles::DOC_FOREIGN_PASSPORT  => 'Загран паспорт',
																					 Profiles::DOC_MILITARY_ID       => 'Военный билет',
																					 Profiles::DOC_OTHER_PASSPORT    => 'Паспорт другой страны'],
											 ['class' => "select select_fz-s", 'data-placeholder' => "Выберите", 'onChange' => 'setDocNumMask(this)']); ?>
		<?php echo CHtml::error($profileModel, '[' . $i . ']doc_type'); ?>
	</td>
</tr>
<tr>
	<td class="form-table__name"><?php echo CHtml::activeLabelEx($profileModel, '[' . $i . ']doc_num'); ?></td>
	<td>
		<?php echo CHtml::activeTextField($profileModel, '[' . $i . ']doc_num', ['class' => "doc_num input-text input-text_fz-s"]); ?>
		<?php echo CHtml::error($profileModel, '[' . $i . ']doc_num'); ?>
	</td>
</tr>
<tr>
	<td class="form-table__name"><?php echo CHtml::activeLabelEx($profileModel, '[' . $i . ']phone'); ?></td>
	<td>
		<?php echo CHtml::activeTextField($profileModel, '[' . $i . ']phone', ['class' => 'phone input-text input-text_fz-s']); ?>
		<?php echo CHtml::error($profileModel, '[' . $i . ']phone'); ?>
	</td>
</tr>
<tr>
	<td class="form-table__name"><?php echo CHtml::activeLabelEx($profileModel, '[' . $i . ']sex'); ?></td>
	<td>
		<?php echo CHtml::activeDropDownList($profileModel, '[' . $i . ']sex', ['none'               => '-Выберите-',
																				Profiles::SEX_MALE   => 'Мужской',
																				Profiles::SEX_FEMALE => 'Женский'],
											 ['class' => "select select_fz-s", 'data-placeholder' => "Выберите"]); ?>
		<?php echo CHtml::error($profileModel, '[' . $i . ']sex'); ?>
	</td>
</tr>
<tr>
	<td class="form-table__name"><?php echo CHtml::activeLabelEx($profileModel, '[' . $i . ']birth'); ?></td>
	<td>
		<div class="row datapiker-wrap">
			<?php $this->widget('zii.widgets.jui.CJuiDatePicker', [
				'model'       => $profileModel,
				'attribute'   => '[' . $i . ']birth',
				'language'    => 'ru',
				'options'     => [
					'showAnim'    => 'fadeIn',//'slide','fold','slideDown','fadeIn','blind','bounce','clip','drop'
					'changeMonth' => TRUE,
					'changeYear'  => TRUE,
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
	</td>
</tr>