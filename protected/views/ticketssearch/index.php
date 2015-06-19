<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 15.06.2015
 * Time: 19:49
 */

$form = $this->beginWidget('CActiveForm', array(
	'id'                   => 'searchtrip-form',
	'enableAjaxValidation' => FALSE,
));
?>
	<table style="border: 1px solid #ccc; padding: 10px; width: 400px;">
		<tr>
			<td>
				<?php
				echo CHtml::label('Откуда:', 'startPoint');
				echo '<br/>';
				echo isset($selPoints) ? CHtml::dropDownList('startPoint', $selPoints['startPoint'], $points) : CHtml::dropDownList('startPoint', '', $points);
				?>
			</td>
			<td>
				<?php
				echo CHtml::label('Отправление: ', 'departure');
				echo '<br/>';
				Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
				$this->widget('CJuiDateTimePicker', array(
					'name'      => 'departure',
					'attribute' => 'departure',
					'mode'      => 'date',
					'value'     => isset($selPoints) ? $selPoints['departure'] : '',
					'options'   => array(
						'dateFormat'  => 'dd.mm.yy',
						'changeMonth' => TRUE,
						'changeYear'  => TRUE,
						'minDate'     => 0,
					),
					'language'  => 'ru',
				));
				?>
			</td>
		</tr>
		<tr>
			<td>
				<?php
				echo CHtml::label('Куда:', 'endPoint');
				echo '<br/>';
				echo isset($selPoints) ? CHtml::dropDownList('endPoint', $selPoints['endPoint'], $points) : CHtml::dropDownList('endPoint', '', $points);
				?>
			</td>
			<td>
				<?php
				echo CHtml::submitButton('Искать');
				?>
			</td>
		</tr>
	</table>
<?php
$this->endWidget();

if (isset($trips)) {
	$form = $this->beginWidget('CActiveForm', array(
		'id'                   => 'selecttrip-form',
		'enableAjaxValidation' => FALSE,
	));
	$this->widget('zii.widgets.grid.CGridView', array(
		'id'           => 'trips-grid',
		'dataProvider' => $trips,
		'template'     => '{items}{pager}',
		'ajaxUpdate'   => FALSE,
		'columns'      => array(
			array(
				'header' => '',
				'name'   => 'id',
				'type'   => 'raw',
				'value'  => 'CHtml::radioButton("tripId_".$data["id"],false, array("value" => $data["id"], "onClick" => "document.getElementById(\'tripsSelect\').removeAttribute(\'disabled\')"))',
			),
			array(
				'name'   => 'direction',
				'header' => 'Направление',
			),
			array(
				'name'   => 'departure',
				'header' => 'Отправление',
			),
			array(
				'name'   => 'arrival',
				'header' => 'Прибытие',
			),
			array(
				'name'   => 'description',
				'header' => 'Описание',
			),
		),
	));
	echo CHtml::submitButton('Выбрать', array('id' => 'tripsSelect', 'name' => 'tripsSelect', 'disabled' => 'disabled'));
	$this->endWidget();
}
?>