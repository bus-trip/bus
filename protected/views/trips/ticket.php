<div class="item-profile form">

	<?php $form = $this->beginWidget('CActiveForm', array(
		'id'                   => 'admin-ticket-form',
		// Please note: When you enable ajax validation, make sure the corresponding
		// controller action is handling ajax validation correctly.
		// See class documentation of CActiveForm for details on this,
		// you need to use the performAjaxValidation()-method described there.
		'enableAjaxValidation' => FALSE,
	)); ?>

	<fieldset>
		<label>Профиль пассажира</label>

		<?php $this->renderPartial('application.views.account.profile_form', array('form' => $form, 'model' => $profile)); ?>
	</fieldset>


	<fieldset>
		<label>Адрес</label>

		<div class="row">
			<?php echo $form->labelEx($model, 'От'); ?>
			<?php echo $form->textArea($model, 'address_from'); ?>
			<?php echo $form->error($model, 'model'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model, 'До'); ?>
			<?php echo $form->textArea($model, 'address_to'); ?>
			<?php echo $form->error($model, 'model'); ?>
		</div>

	</fieldset>

	<div class="row">
		<?php echo $form->labelEx($model, 'Статус'); ?>
		<?php echo $form->dropDownList($model, 'status', $model->getStatuses()); ?>
		<?php echo $form->error($model, 'model'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Сохранить'); ?>
	</div>

<?php $this->endWidget(); ?>