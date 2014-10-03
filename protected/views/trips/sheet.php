<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 01.10.14
 * Time: 17:48
 */
$this->breadcrumbs=array(
    'Рейсы'=>array('admin'),
    'Посадочная ведомость',
);

$this->menu=array(
    array('label'=>'Рейсы', 'url'=>array('admin')),
);
?>

<h2>Посадочная ведомость</h2>
Направление: <?php echo $dataHeader['direction']['startPoint'] .' - '. $dataHeader['direction']['endPoint']; ?><br/>
Отправление: <?php echo $dataHeader['trips']['departure']; ?>&nbsp;&nbsp;&nbsp;&nbsp;Прибытие: <?php echo $dataHeader['trips']['arrival']; ?><br/>
Автобус: <?php echo $dataHeader['bus']['model'] .', номер '. $dataHeader['bus']['number']; ?><br/>

<?php

$this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'trips-sheet',
    'dataProvider'=>$dataProvider,
    'template'=>'{items}',
    'columns'=>array(
        array(
            'name'=>'place',
            'header'=>'Место',
        ),
        array(
            'name'=>'passenger',
            'header'=>'ФИО',
        ),
        array(
            'name'=>'startPoint',
            'header'=>'Посадка',
        ),
        array(
            'name'=>'endPoint',
            'header'=>'Высадка',
        ),
        array(
            'name'=>'phone',
            'header'=>'Номер телефона',
        ),
        array(
            'name' => 'price',
            'header' => 'Стоимость',
        ),
    ),
));

echo '<a href="'. Yii::app()->createUrl('trips/sheetprint',array('id'=>$_GET['id'])) .'" target="_blank">Версия для печати</a>';

?>
