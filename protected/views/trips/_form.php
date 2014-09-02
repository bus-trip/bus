<?php
/* @var $this TripsController */
/* @var $model Trips */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'trips-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

<!--	<p class="note">Fields with <span class="required">*</span> are required.</p>-->

	<?php echo $form->errorSummary($model); ?>
	<div class="row">
	<?php
        if(isset($directions)){
		    echo $form->labelEx($model,'Направление');
		    echo $form->dropDownList($model,'idDirection',$directions);
		    echo $form->error($model,'idDirection');
        }
	?>
	</div>

	<div class="row">
	<?php
        if(isset($buses)){
		    echo $form->labelEx($model,'Автобус');
		    echo $form->dropDownList($model,'idBus',$buses);
		    echo $form->error($model,'idBus');
        }
	?>
	</div>

	<div class="row">
	<?php
		echo $form->labelEx($model,'Отправление');
		Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
		$this->widget('CJuiDateTimePicker',array(
			'model'=>$model,
			'attribute'=>'departure',
            'mode'=>'datetime',
			'options'=>array(
				'dateFormat' => 'yy-mm-dd', //'dd.mm.yy',
                'timeFormat' => 'hh:mm:ss',
                'changeMonth' => true,
                'changeYear' => true,
			),
			'language'=>'ru',
		));
		echo $form->error($model,'departure');
	?>
	</div>

    <div class="row">
        <?php
        echo $form->labelEx($model,'Прибытие');
        Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
        $this->widget('CJuiDateTimePicker',array(
            'model'=>$model,
            'attribute'=>'arrival',
            'mode'=>'datetime',
            'options'=>array(
                'dateFormat' => 'yy-mm-dd', //'dd.mm.yy',
                'timeFormat' => 'hh:mm:ss',
                'changeMonth' => true,
                'changeYear' => true,
            ),
            'language'=>'ru',
        ));
        echo $form->error($model,'arrival');
        ?>
    </div>

    <div class="row">
        <?php
            echo $form->labelEx($model,'Описание');
            echo $form->textField($model,'description');
            echo $form->error($model,'description');
        ?>
    </div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->