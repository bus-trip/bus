<?php
/* @var $this DirectionsController */
/* @var $model Directions */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'directions-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Поля, отмеченные <span class="required">*</span> обязательны к заполнению.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'parentId'); ?>
        <?php echo $form->dropDownList($model,'parentId',$parentDir); ?>
		<?php echo $form->error($model,'parentId'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'startPoint'); ?>
		<?php echo $form->textField($model,'startPoint',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'startPoint'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'endPoint'); ?>
		<?php echo $form->textField($model,'endPoint',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'endPoint'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'price'); ?>
		<?php echo $form->textField($model,'price'); ?>
		<?php echo $form->error($model,'price'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Обновить'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->