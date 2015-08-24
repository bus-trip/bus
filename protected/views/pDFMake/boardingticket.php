<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 24.08.2015
 * Time: 17:12
 */
?>
<div style="border: 1px solid #000000; width: 320px; float: left;">
	<caption>Билет № <?php echo $ticketId; ?></caption>
	<table border="1px" cellspacing="0" cellpadding="0" style="padding: 5px;">
		<tr>
			<td>Ф.И.О.</td>
			<td><?php echo $name; ?></td>
		</tr>
		<tr>
			<td>Дата рождения</td>
			<td><?php echo $birthDate; ?></td>
		</tr>
		<tr>
			<td>Наименование документа</td>
			<td></td>
		</tr>
		<tr>
			<td>Серия, номер</td>
			<td><?php echo $passport; ?></td>
		</tr>
		<tr>
			<td>Направление</td>
			<td><?php echo $direction; ?></td>
		</tr>
		<tr>
			<td>Отправление</td>
			<td><?php echo $startPoint; ?></td>
		</tr>
		<tr>
			<td>Прибытие</td>
			<td><?php echo $endPoint; ?></td>
		</tr>
		<tr>
			<td>Время отправления</td>
			<td><?php echo $departure; ?></td>
		</tr>
		<tr>
			<td>Время прибытия</td>
			<td><?php echo $arrival; ?></td>
		</tr>
		<tr>
			<td>Тип, № автобуса</td>
			<td><?php echo $bus; ?></td>
		</tr>
		<tr>
			<td>Место</td>
			<td><?php echo $place; ?></td>
		</tr>
		<tr>
			<td>Стоимость билета</td>
			<td><?php echo $price; ?></td>
		</tr>
	</table>
</div>