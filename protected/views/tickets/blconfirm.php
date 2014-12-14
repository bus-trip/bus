<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 12.12.2014
 * Time: 1:22
 */
?>

<div class="form">

	<h2>Внесение пассажира в чёрный список</h2>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'blacklist-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>
<?php
	echo '<b>'.$blParam['Profile']->name.' '.$blParam['Profile']->middle_name.' '.$blParam['Profile']->last_name.'</b><br/><br/>';
	echo 'Причина внесения в чёрный список:<br/>';
	echo $form->textArea($blParam['Profile'],'black_desc',array('rows'=>6, 'cols'=>50));
?>
<div class="row buttons">
<?php echo CHtml::submitButton('Внести в чёрный список'); ?>
</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
