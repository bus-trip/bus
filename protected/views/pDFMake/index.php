<?php
/* @var $this PDFMakeController */

$this->breadcrumbs=array(
	'PDFmake',
);
?>

<div style="width: 99%; padding: 1%;">
	<div style="height: 40px;">
		<div style="width: 40%; float: left;"><?php echo $profile['created'];?></div><div style="width: 60%; float: left;">Электронный билет, версия для печати</div>
	</div>
	<div style="width: 100%; height: 60px; font-size: 32px; font-weight: bold; font-family: sans-serif;">Спринт - тур</div>
	<div style="font-size: 18px; text-align: center; height: 50px;">
		<div style="width: 40%; float: left;">Электронный билет № <b><?php echo $ticket['id'] ?></b></div><div style="width: 60%; float: left;">Дата покупки: <b><?php echo $profile['created'];?></b></div>
	</div>
	<div style="width: 100%; float: left; height: 30px;">Перевозчик: "Спринт-Тур"</div>
	<div style="width: 100%; float: left; height: 30px;">ИНН 7463746523</div>
	<div style="width: 100%; float: left; height: 30px;">Тел. +7 909 645 3485</div>
	<div style="width: 100%; float: left; height: 40px; font-size: 18px; font-weight: bold; text-align: center;">Информация о рейсе</div>
	<div style="width: 100%; float: left; height: 40px; font-size: 16px; font-weight: bold;">Гос. номер автобуса: <?php echo $bus['number']; ?></div>
	<div style="width: 100%; float: left; height: 100px;">
		<table style="border-collapse: collapse; width: 100%;">
			<tr>
				<td style="border: 1px solid #000000; font-weight: bold;">Отправление</td>
				<td style="border: 1px solid #000000; font-weight: bold;">Прибытие</td>
			</tr>
			<tr>
				<td style="border: 1px solid #000000"><?php echo $direction['startPoint']; ?><br><?php echo $ticket['address_from']; ?><br><?php echo $trip['departure']; ?></td>
				<td style="border: 1px solid #000000"><?php echo $direction['endPoint']; ?><br><?php echo $ticket['address_to']; ?><br><?php echo $trip['arrival']; ?></td>
			</tr>
		</table>
	</div>
	<div style="width: 100%; float: left; height: 140px;">
		<table style="border-collapse: collapse; width: 100%;">
			<caption style="text-align: center; font-weight: bold;">Информация о пассажирах и тарифах</caption>
			<tr>
				<td style="border: 1px solid #000000; font-weight: bold;">ФИО</td>
				<td style="border: 1px solid #000000; font-weight: bold;">Тип документа</td>
				<td style="border: 1px solid #000000; font-weight: bold;">Номер документа</td>
				<td style="border: 1px solid #000000; font-weight: bold;">Место</td>
				<td style="border: 1px solid #000000; font-weight: bold;">Стоимость, руб</td>
			</tr>
			<tr>
				<td style="border: 1px solid #000000"><?php echo $profile['name']. ' ' . $profile['middle_name'] .' ' . $profile['last_name']; ?></td>
				<td style="border: 1px solid #000000">Паспорт</td>
				<td style="border: 1px solid #000000"><?php echo $profile['passport']; ?></td>
				<td style="border: 1px solid #000000"><?php echo $ticket['place']; ?></td>
				<td style="border: 1px solid #000000"><?php echo $ticket['price']; ?></td>
			</tr>
			<tr>
				<td style="border-top: 1px solid #000000"></td>
				<td style="border-top: 1px solid #000000"></td>
				<td style="border-top: 1px solid #000000"></td>
				<td style="border: 1px solid #000000">Итого</td>
				<td style="border: 1px solid #000000"><?php echo $ticket['price']; ?></td>
			</tr>
		</table>
	</div>
	<div style="width: 100%; float: left; height: 40px;"><b>Статус билета:</b> Оплачен</div>
	<div style="width: 100%; float: left; height: 150px;">
		Примечание:
		<ul>
			<li>Время отправления и прибытия осуществляется по местному времени;</li>
			<li>Посадка на автобус осуществляется за 15 до отправления;</li>
			<li>При наличии оплаченного электронного билета, пассажир, минуя кассу, занимает своё посадочное место в автобусе;</li>
			<li>За операцию оформления возврата стоимости Электронного билета взимается комиссия в размере 25% от стоимости билета;</li>
			<li>Ограничение по багажу устанавливается Перевозчиком. Дополнительное багажное место оплачивается;</li>
			<li>Перевозка животных разрешена только при наличии ветеринарной справки.</li>
		</ul>
	</div>
	<div style="width: 100%; float: left; height: 40px; font-size: 18px; text-align: center;">Счастливого пути!</div>
	<div style="width: 100%; float: left; height: 10px;"><hr style="height: 5px; background-color: #000000;"></div>
	<div style="width: 100%; height: 60px; font-size: 32px; font-weight: bold; font-family: sans-serif;">Спринт - тур</div>
</div>