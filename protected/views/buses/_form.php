<?php
/* @var $this BusesController */
/* @var $model Buses */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'buses-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Поля, отмеченные <span class="required">*</span> обязательны к заполнению.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'Госномер автобуса'); ?>
		<?php echo $form->textField($model,'number',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'number'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Количество мест'); ?>
		<?php echo $form->textField($model,'places'); ?>
		<?php echo $form->error($model,'places'); ?>
	</div>

    <div class="row">
        <?php echo $form->labelEx($model,'Модель автобуса'); ?>
        <?php echo $form->textField($model,'model',array('size'=>10,'maxlength'=>10)); ?>
        <?php echo $form->error($model,'model'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'Краткое описание'); ?>
        <?php echo $form->textField($model,'description',array('size'=>20,'maxlength'=>100)); ?>
        <?php echo $form->error($model,'description'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'Статус автобуса'); ?>
        <?php echo $form->dropDownList($model,'status',array(1=>'Работает',0=>'Не работает')); ?>
        <?php // echo $form->textField($model,'status',array('size'=>20,'maxlength'=>100)); ?>
        <?php echo $form->error($model,'status'); ?>
    </div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Обновить'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->