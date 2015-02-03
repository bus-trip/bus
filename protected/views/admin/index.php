<?php
$this->pageTitle = Yii::app()->name;
$this->breadcrumbs = array(
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
$monthSelect = '';
$yearSelect = '';
?>
<table id="main">
	<caption>
		<div style="float: left;"><b>Рейсы
				на <?php echo $currentDate['year'] . '  ' . $monthNames[$currentDate['month']]; ?></b></div>
		<div style="float: right;">
			<a href="/"><input type="button" value="Текущий месяц"/></a>
			<?php
			if (isset($currentDate) && is_array($currentDate)) {
				echo CHtml::dropDownList(
					'monthSelect',
					$monthSelect,
					$monthNames,
					array(
						'empty'   => 'Выберите месяц',
						'options' => array(
							$currentDate['month'] => array(
								'selected' => TRUE
							)
						),
						'ajax'    => array(
							'type'   => 'POST',
							'url'    => '/',
							'data'   => array(
								'monthSelect' => 'js:this.value',
								'yearSelect'  => 'js:document.getElementById("yearSelect").value'
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
						date("Y") - 1 => date("Y") - 1,
						date("Y")     => date("Y"),
						date("Y") + 1 => date("Y") + 1,
					),
					array(
						'empty'   => 'Выберите год',
						'options' => array(
							$currentDate['year'] => array(
								'selected' => TRUE
							)
						),
						'ajax'    => array(
							'type'   => 'POST',
							'url'    => '/',
							'data'   => array(
								'monthSelect' => 'js:document.getElementById("monthSelect").value',
								'yearSelect'  => 'js:this.value'
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
	$firstDOW = date("w", strtotime(date("01." . $currentDate['month'] . "." . $currentDate['year'])));
	if ($firstDOW == 0) $firstDOW = 7;
	$firstDOW--;
	$maxDays = date("t", strtotime($currentDate['year'] . "-" . $currentDate['month'] . "-01"));
	$count = $firstDOW >= 5 ? 42 : 35;
	echo "<tr>";
	for ($i = 0; $i < $count; $i++) {
		if ($i % 7 == 0) {
			echo "</tr>";
			echo "<tr>";
		}
		echo "<td style='border: 1px solid black; width: 113px; height: 80px; vertical-align: top;";
		if ($i < $firstDOW || $i > $maxDays + $firstDOW - 1) echo "background-color: lightgray;";
		if ($i == date("d") + $firstDOW - 1 && date("m") == $currentDate['month'] && date("Y") == $currentDate['year']) echo "background-color: lightgreen;";
		echo "'>";
		if ($i >= $firstDOW && $i <= $maxDays + $firstDOW - 1) {
			$tripsDate = $currentDate['year'] . '-' . $currentDate['month'] . '-' . ($i + 1 - $firstDOW < 10 ? '0' : '') . ($i + 1 - $firstDOW);
			echo "<div style='width: 70px; float: left; font-size: 16px;'>";
			if ($i + 1 - $firstDOW < 10) echo "0";
			echo date($i + 1 - $firstDOW . "." . $currentDate['month'] . "." . $currentDate['year']);
			echo "</div>";
			if (isset($tripsParam[$i + 1 - $firstDOW]['exttrips']) && preg_match("/" . $tripsDate . "/", $tripsParam[$i + 1 - $firstDOW]['exttrips']['departure'])) {
				echo "<div style='float: right;'>";
				echo CHtml::dropDownList(
					'extTrips',
					$extTrips,
					array(
						'empty'                                           => '',
						$tripsParam[$i + 1 - $firstDOW]['exttrips']['id'] => $tripsParam[$i + 1 - $firstDOW]['exttrips']['Direction'],
					),
					array(
						'style' => 'width: 30px',
						'on',
						'onchange' => 'window.location="/trips/sheet/'.$tripsParam[$i + 1 - $firstDOW]['exttrips']['id'].'"',
					)
				);
				echo "</div>";
			}

			if ($tripsParam[$i + 1 - $firstDOW]['date'] == $tripsDate) {
				echo "<div class='trips-dir'>";
				echo "<form action='trips/sheet/" . $tripsParam[$i + 1 - $firstDOW]['trip1']['id'] . "' method='POST'>";
				echo "<input type='hidden' value='" . date("Y-m-d", strtotime("+1 day", strtotime($tripsDate))) . " 05:30:00' name='trips-arrive' />";
				echo "<input type='hidden' value='" . $tripsDate . " 12:00:00' name='trips-date'>";
				echo "<input type='hidden' value='" . $tripsParam[$i + 1 - $firstDOW]['trip1']['idDirection'] . "' name='trips-dir-id' />";
				echo "<input type='submit' value='" . $directions[0]['direction'] . " (" . $tripsParam[$i + 1 - $firstDOW]['trip1']['count'] . ")' class='trips trips-" . $tripsParam[$i + 1 - $firstDOW]['trip1']['full'] . "' />";
				echo "</form>";
				echo "</div>";
				echo "<div class='trips-dir'>";
				echo "<form action='trips/sheet/" . $tripsParam[$i + 1 - $firstDOW]['trip2']['id'] . "' method='POST'>";
				echo "<input type='hidden' value='" . date("Y-m-d", strtotime("+1 day", strtotime($tripsDate))) . " 14:00:00' name='trips-arrive' />";
				echo "<input type='hidden' value='" . $tripsDate . " 22:00:00' name='trips-date'>";
				echo "<input type='hidden' value='" . $tripsParam[$i + 1 - $firstDOW]['trip2']['idDirection'] . "' name='trips-dir-id' />";
				echo "<input type='submit' value='" . $directions[1]['direction'] . " (" . $tripsParam[$i + 1 - $firstDOW]['trip2']['count'] . ")' class='trips trips-" . $tripsParam[$i + 1 - $firstDOW]['trip2']['full'] . "' />";
				echo "</form>";
				echo "</div>";
			}
		}
		echo "</td>";
	}
	echo "</tr>";
	?>
</table>

