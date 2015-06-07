<?php
/**
 * Part of bus 2015
 * Created by: Александр on 07.06.2015:23:27
 *
 * @var $profileModel Profiles
 * @var int $i
 */
?>

<div class="row">
	<?php echo CHtml::activeLabel($profileModel, '[' . $i . ']last_name'); ?>
	<?php echo CHtml::activeTextField($profileModel, '[' . $i . ']last_name'); ?>
	<?php echo CHtml::error($profileModel, '[' . $i . ']last_name'); ?>
</div>

<div class="row">
	<?php echo CHtml::activeLabel($profileModel, '[' . $i . ']name'); ?>
	<?php echo CHtml::activeTextField($profileModel, '[' . $i . ']name'); ?>
	<?php echo CHtml::error($profileModel, '[' . $i . ']name'); ?>
</div>

<div class="row">
	<?php echo CHtml::activeLabel($profileModel, '[' . $i . ']middle_name'); ?>
	<?php echo CHtml::activeTextField($profileModel, '[' . $i . ']middle_name'); ?>
	<?php echo CHtml::error($profileModel, '[' . $i . ']middle_name'); ?>
</div>


<div class="row">
	<div class="passport-input">
		<label for="user_passport1" class="required">Серия <span class="required">*</span></label>
		<input class="code" type="text" name="user_passport1" id="" maxlength="4" size="4"/>
		<label for="user_passport2" class="required">Номер <span class="required">*</span></label>
		<input class="number" type="text" name="user_passport2" id="" maxlength="6" size="6"/>

		<?php echo CHtml::activeHiddenField($profileModel, '[' . $i . ']passport', ['class' => 'code_number']); ?>
	</div>
	<?php echo CHtml::error($profileModel, '[' . $i . ']passport'); ?>
</div>

<div class="row">
	<?php echo CHtml::activeLabel($profileModel, '[' . $i . ']phone'); ?>
	<?php echo CHtml::activeTextField($profileModel, '[' . $i . ']phone', ['class' => 'phone']); ?>
	<?php echo CHtml::error($profileModel, '[' . $i . ']phone'); ?>
</div>

<div class="row">
	<?php echo CHtml::activeLabel($profileModel, '[' . $i . ']sex'); ?>
	<?php echo CHtml::activeDropDownList($profileModel, '[' . $i . ']sex', ['none' => '-Выберите-', '0' => 'Мужской', '1' => 'Женский']); ?>
	<?php echo CHtml::error($profileModel, '[' . $i . ']sex'); ?>
</div>

<div class="row">
	<?php echo CHtml::activeLabel($profileModel, '[' . $i . ']birth'); ?>
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