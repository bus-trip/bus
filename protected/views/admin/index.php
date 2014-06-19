<?php
$this->pageTitle=Yii::app()->name;
$this->breadcrumbs=array(
	'Administration',
);
?>
<table id="main">
	    <caption><b>Рейсы на ближайшие семь дней</b></caption>
		<tr>
	    <?php
			$tr_date = array();
			for($i=0; $i<7; $i++){
				print "<td>".date("d.m.Y", strtotime(date("d.m.Y"). " + $i day"))."</td>";
				$tr_date[$i] = date("Y-m-d", strtotime(date("Y-m-d"). " + $i day")). ' 00:00:00';
			}
	    ?>
		</tr>
		<?php
			foreach($dataProvider as $d){
				print_r($d->attributes);
			}
		?>
		<tr>
			<?php
				for($i=0; $i<7; $i++){
					echo "<td>";
					
					$form = $this->beginWidget('CActiveForm', array(
						'action'=>array('trips/create'),
						'enableAjaxValidation'=>false,
					));
					//$form->hiddenField()
					echo CHtml::hiddenField('trips-date',$tr_date[$i]);
					echo CHtml::submitButton('Добавить рейс', array('submit'=>array('trips/create')));
					$this->endWidget();
					
					echo "</td>";
				}
			?>
		</tr>
	</table>

