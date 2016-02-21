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
		<div class="month">Рейсы
				на <?php echo  $monthNames[$currentDate['month']] . '  ' . $currentDate['year']; ?></div>
		<div class="calendar-control">
		    <span class="current-month" title="Текущий месяц">
			    <a href="/"><input class="btn-current-month" type="button" value=""/></a>
            </span>
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
							'url'    => '/admin/',
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
							'url'    => '/admin/',
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
	<tr class="weekday">
	    <td>Пн</td>
	    <td>Вт</td>
	    <td>Ср</td>
	    <td>Чт</td>
	    <td>Пт</td>
	    <td>Сб</td>
	    <td>Вс</td>
	</tr>
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
		echo "<td ";
		if ($i < $firstDOW || $i > $maxDays + $firstDOW - 1) echo "class='not-active'";
		if ($i == date("d") + $firstDOW - 1 && date("m") == $currentDate['month'] && date("Y") == $currentDate['year']) echo "class='current-date'";
		echo "'>";
		if ($i >= $firstDOW && $i <= $maxDays + $firstDOW - 1) {
			$tripsDate = $currentDate['year'] . '-' . $currentDate['month'] . '-' . ($i + 1 - $firstDOW < 10 ? '0' : '') . ($i + 1 - $firstDOW);
			echo "<div class='date'>";
			if ($i + 1 - $firstDOW < 10) echo "0";
			echo date($i + 1 - $firstDOW);
			echo "</div>";
			if (!empty($tripsParam[$i + 1 - $firstDOW]['exttrips'])) {
				echo "<div style='float: right;'>";
				$extTrips = '';
				$extOpts = array('empty' => 'Доп.рейсы');
				foreach ($tripsParam[$i + 1 - $firstDOW]['exttrips'] as $opts) {
					if (preg_match("/" . $tripsDate . "/", $opts['departure']))
						$extOpts[$opts['id']] = $opts['Direction'];
				}
				echo CHtml::dropDownList(
					'extTrips',
					$extTrips,
					$extOpts,
					array(
						'style'    => 'font-size: 12px; width: 70px',
						'on',
						//							'onchange' => 'if(document.getElementById("extTrips").value != "empty"){window.location="/trips/sheet/"+document.getElementById("extTrips").value;}',
						//							'onchange' => 'if(this.value != "empty"){window.location="/trips/sheet/"+this.value;}',
						'onchange' => 'if(this.value != "empty"){
							var form = document.createElement("FORM");
							form.method="POST";
							form.style.display = "none";
							document.body.appendChild(form);
							form.action = "/trips/sheet/" + this.value;
							input = document.createElement("INPUT");
							input.type = "hidden";
							input.name="yearSelect";
							input.value="'.$currentDate['year'].'";
							form.appendChild(input);
							input = document.createElement("INPUT");
							input.type = "hidden";
							input.name="monthSelect";
							input.value="'.$currentDate['month'].'";
							form.appendChild(input);
							form.submit();
						}',
					)
				);
				echo "</div>";
			}

			if ($tripsParam[$i + 1 - $firstDOW]['date'] == $tripsDate) {
				echo "<div class='trips-dir'>";
				echo "<form action='/trips/sheet/" . $tripsParam[$i + 1 - $firstDOW]['trip1']['id'] . "' method='POST'>";
				echo "<input type='hidden' value='" . date("Y-m-d", strtotime("+1 day", strtotime($tripsDate))) . " 05:30:00' name='trips-arrive' />";
				echo "<input type='hidden' value='" . $tripsDate . " 12:00:00' name='trips-date'>";
				echo "<input type='hidden' value='" . $tripsParam[$i + 1 - $firstDOW]['trip1']['idDirection'] . "' name='trips-dir-id' />";
				echo "<input type='hidden' value='" . $currentDate['year'] . "' name='yearSelect' />";
				echo "<input type='hidden' value='" . $currentDate['month'] . "' name='monthSelect' />";
				echo "<input type='submit' value='" . $directions[0]['direction'] . " (" . $tripsParam[$i + 1 - $firstDOW]['trip1']['count'] . ")' class='trips trips-" . $tripsParam[$i + 1 - $firstDOW]['trip1']['full'] . "' />";
				echo "</form>";
				echo "</div>";
				echo "<div class='trips-dir'>";
				echo "<form action='/trips/sheet/" . $tripsParam[$i + 1 - $firstDOW]['trip2']['id'] . "' method='POST'>";
				echo "<input type='hidden' value='" . date("Y-m-d", strtotime("+1 day", strtotime($tripsDate))) . " 14:00:00' name='trips-arrive' />";
				echo "<input type='hidden' value='" . $tripsDate . " 22:00:00' name='trips-date'>";
				echo "<input type='hidden' value='" . $tripsParam[$i + 1 - $firstDOW]['trip2']['idDirection'] . "' name='trips-dir-id' />";
				echo "<input type='hidden' value='" . $currentDate['year'] . "' name='yearSelect' />";
				echo "<input type='hidden' value='" . $currentDate['month'] . "' name='monthSelect' />";
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

