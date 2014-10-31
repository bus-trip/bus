<?php
/* @var $this TripsController */
/* @var $model Trips */

$this->breadcrumbs = array(
	'Рейсы' => array('admin'),
	'Создать рейс',
);

$this->menu = array(
	array('label' => 'Рейсы', 'url' => array('admin')),
);
?>

	<h2>Создать рейс</h2>

<?php $this->renderPartial(
	'_form',
	array(
		'model'      => $model,
		'directions' => $directions,
		'buses'      => $buses,
		'actual'     => 1,
	)
);
?>