<div class="item-profile form">

	<?php $form = $this->beginWidget('CActiveForm', [
		'id'                   => 'admin-ticket-form',
		// Please note: When you enable ajax validation, make sure the corresponding
		// controller action is handling ajax validation correctly.
		// See class documentation of CActiveForm for details on this,
		// you need to use the performAjaxValidation()-method described there.
		'enableAjaxValidation' => false,
	]); ?>

	<fieldset>
		<label>Профиль пассажира</label>

		<?php $this->renderPartial('profile_form', ['form' => $form, 'model' => $profile, 'edit_bl' => true]); ?>
	</fieldset>


	<fieldset>
		<label>Адрес</label>

		<div class="row">
			<?php echo $form->labelEx($model, 'От'); ?>
			<?php echo $form->textArea($model, 'address_from'); ?>
			<?php echo $form->error($model, 'address_from'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model, 'До'); ?>
			<?php echo $form->textArea($model, 'address_to'); ?>
			<?php echo $form->error($model, 'address_to'); ?>
		</div>

	</fieldset>

	<div class="row">
		<?php echo $form->labelEx($model, 'Примечание'); ?>
		<?php echo $form->textArea($model, 'remark'); ?>
		<?php echo $form->error($model, 'remark'); ?>
	</div>


	<div class="row">
		<?php echo $form->labelEx($model, 'Статус'); ?>
		<?php echo $form->dropDownList($model, 'status', $model->getStatuses()); ?>
		<?php echo $form->error($model, 'status'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'Цена'); ?>
		<?php echo $form->textField($model, 'price'); ?>
		<?php echo $form->error($model, 'price'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Сохранить'); ?>
	</div>

<?php $this->endWidget(); ?>