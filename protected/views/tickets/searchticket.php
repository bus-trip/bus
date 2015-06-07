<?php
/* @var $this TicketsController */
/* @var $model Tickets */

$form = $this->beginWidget('CActiveForm', array(
	'id'                   => 'searchticket-form',
	'enableAjaxValidation' => FALSE,
));

if (isset($points)) {
	echo $form->dropDownList($model, 'startPoint', $points);
	echo $form->dropDownList($model, 'endPoint', $points);
	echo "<br/>";
	echo CHtml::submitButton('Искать');
} elseif (isset($startPoints)) {
	echo '<pre>';
	print_r($tickets);
	foreach($trips as $t){
		print_r($t);
	}
	echo '</pre>';
}

$this->endWidget();
?>