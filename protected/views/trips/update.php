<?php
/* @var $this TripsController */
/* @var $model Trips */

$this->breadcrumbs = array(
	'Рейсы' => array('admin'),
	'Редактирование',
);

$this->menu = array(
	array('label' => 'Рейсы', 'url' => array('admin')),
);
?>

	<h1>Редактирование рейса №<?php echo $model->id; ?></h1>


<?php $this->renderPartial(
	'_form',
	array(
		'model'      => $model,
		'directions' => $directions,
		'buses'      => $buses,
		'actual'     => $actual
	)
); ?>