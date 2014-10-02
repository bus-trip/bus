<?php
/* @var $this ScheduleController */
/* @var $model Schedule */

$this->breadcrumbs=array(
	'Расписание'=>array('index'),
	'Редактирование',
);

$this->menu=array(
    array('label'=>'Управление расписанием маршрутов', 'url'=>array('admin')),
    array('label'=>'Вернуться к расписанию маршрута', 'url'=>array($idSchedule)),
);
?>

<h1>Обновление расписания № <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model,'trips'=>$trips,'directions'=>$directions)); ?>