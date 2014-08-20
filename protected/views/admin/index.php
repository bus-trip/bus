<?php
$this->pageTitle=Yii::app()->name;
$this->breadcrumbs=array(
	'Administration',
);
$monthNames = array(
    '01' => 'Январь',
    '02' => 'Февраль',
    '03' => 'Март',
    '04' => 'Апрель',
    '05' => 'Май',
    '06' => 'Июнь',
    '07' => 'Июль',
    '08' => 'Август',
    '09' => 'Сентябрь',
    '10' => 'Октябрь',
    '11' => 'Ноябрь',
    '12' => 'Декабрь',
);
?>
<table id="main">
	    <caption>
            <div style="float: left;"><b>Рейсы на <?php echo $currentDate['year'].'  '.$monthNames[$currentDate['month']]; ?></b></div>
            <div style="float: right;">
                <form action='/index.php?r=admin/index' method='post' style="float: left;">
                    <?php echo CHtml::submitButton('Текущий месяц', array('submit'=>array('admin/index'))); ?>
                </form>
                <?php
                    if(isset($currentDate) && is_array($currentDate)){
                        echo CHtml::dropDownList(
                            'monthSelect',
                            $monthSelect,
                            $monthNames,
                            array(
                                'empty' => 'Выберите месяц',
                                'options' => array(
                                    $currentDate['month']=>array(
                                        'selected'=>true
                                    )
                                ),
                                'ajax' => array(
                                    'type' => 'POST',
                                    'url' => '/index.php?r=admin/index',
                                    'data' => array(
                                        'monthSelect' => 'js:this.value',
                                        'yearSelect' => 'js:document.getElementById("yearSelect").value'
                                    ),
                                    'update' => 'body'
                                )
                            )
                        );
//                        echo "</form>";
                        echo CHtml::dropDownList(
                            'yearSelect',
                            $yearSelect,
                            array(
                                date("Y")-1 => date("Y")-1,
                                date("Y") => date("Y"),
                                date("Y")+1 => date("Y")+1,
                            ),
                            array(
                                'empty' => 'Выберите год',
                                'options' => array(
                                    $currentDate['year'] => array(
                                        'selected'=>true
                                    )
                                ),
                                'ajax' => array(
                                    'type' => 'POST',
                                    'url' => '/index.php?r=admin/index',
                                    'data' => array(
                                        'monthSelect' => 'js:document.getElementById("monthSelect").value',
                                        'yearSelect' => 'js:this.value'
                                    ),
                                    'update' => 'body'
                                )
                            )
                        );
                    }
                ?>
            </div>
        </caption>
	    <?php
            $firstDOW = date("w", strtotime(date("01.".$currentDate['month'].".".$currentDate['year'])));
            if($firstDOW == 0) $firstDOW = 7;
            $firstDOW--;
            $maxDays = date("t",strtotime($currentDate['year']."-".$currentDate['month']."-".$currentDate['day']));
            $count = $firstDOW > 4 ? 42 : 35;
            echo "<tr>";
            for($i=0; $i<$count; $i++){
			    if($i % 7 == 0){
                    echo "</tr>";
                    echo "<tr>";
                }
				echo "<td style='border: 1px solid black; width: 113px; height: 80px; vertical-align: top;";
                if($i < $firstDOW || $i > $maxDays+$firstDOW-1) echo "background-color: lightgray;";
                if($i == date("d")+$firstDOW-1 && date("m") == $currentDate['month'] && date("Y") == $currentDate['year']) echo "background-color: lightgreen;";
                echo "'>";
                if($i >= $firstDOW && $i <= $maxDays+$firstDOW-1){
                    echo "<div style='width: 70px; float: left;'>";
                    if($i < 10) echo "0";
                    echo date($i+1-$firstDOW.".".$currentDate['month'].".".$currentDate['year']);
                    echo "</div>";
                    if($i <= $maxDays+$firstDOW-1 && strtotime(date("d.m.Y")) <= strtotime($i+1-$firstDOW.".".$currentDate['month'].".".$currentDate['year'])){
                        echo "<div style='width: 30px; float: right; margin: -5px -12px 0 0;'>";
                        echo "<form action='/index.php?r=trips/create' method='post' target='_blank'>";
                        echo CHtml::hiddenField('trips-date',date($currentDate['year']."-".$currentDate['month']."-".($i+1-$firstDOW)). ' 00:00:00');
                        echo CHtml::submitButton('+', array('submit'=>array('trips/create')));
                        echo "</form></div>";
                    }
                    $tripCounts = 0;
                    foreach($dataProvider as $d){
                        if($d->attributes['departure'] >= date($currentDate['year']."-".$currentDate['month']."-".($i+1-$firstDOW)). ' 00:00:00' && $d->attributes['departure'] <= date($currentDate['year']."-".$currentDate['month']."-".($i+1-$firstDOW)). ' 23:59:59')
                            $tripCounts++;
                    }
                    if($tripCounts != 0){
                        echo "<form action='/index.php?r=trips/admin' method='post' target='_blank'>";
                        echo CHtml::hiddenField('trips-date',date($currentDate['year']."-".$currentDate['month']."-".($i+1-$firstDOW)));
                        echo CHtml::submitButton('Рейсов: '.$tripCounts, array('submit'=>array('trips/admin')));
                        echo "</form>";
                    }
                    else echo '<br/>Рейсов: 0';
                }
                echo "</td>";
            }
            echo "</tr>";
	    ?>
	</table>

