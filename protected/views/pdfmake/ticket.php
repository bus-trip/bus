<?php
/* @var $this PDFMakeController */
if (isset($error)) {
	$this->breadcrumbs = array(
		'PDFmake',
	);
	echo $error;

	return false;
}

$pdf = Yii::createComponent('application.extensions.tcpdf.ETcPdf', 'P', 'cm', 'A4', true, 'UTF-8');
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor("Trips operator");
$pdf->SetTitle("Ticket");
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->AddPage();
$pdf->SetFont("dejavuserif", "", 7);

$pdfTicket = '
					<table>
						<tr>
							<td style="height: 25px;">' . $profile->created . '</td>
							<td style="height: 25px; text-align: right;">Электронный билет, версия для печати</td>
						</tr>
					</table>
					<div style="width: 100%; height: 180px; font-size: 24px; font-weight: bold; color: red;">Спринт - тур</div>
					<br/>
					<table style="font-size: 12px;">
						<tr>
							<td style="height: 30px;">Электронный билет № <b>' . $ticket->id . '</b></td>
							<td style="height: 30px;">Дата покупки: <b>' . $profile->created . '</b></td>
						</tr>
					</table>
					<div style="width: 100%; float: left; height: 20px; font-size: 8px;">Перевозчик: "Спринт-Тур"</div>
					<div style="width: 100%; float: left; height: 20px; font-size: 8px;">ИНН 7463746523</div>
					<div style="width: 100%; float: left; height: 20px; font-size: 8px;">Тел. +7 909 645 3485</div>
					<div style="width: 100%; float: left; height: 40px; font-size: 12px; font-weight: bold; text-align: center;">Информация о рейсе</div>
					<div style="width: 100%; float: left; height: 40px; font-size: 12px; font-weight: bold;">Гос. номер автобуса: ' . $bus->number . '</div>
					<br/>
					<table style="border-collapse: collapse; width: 100%; font-size: 10px;">
						<tr>
							<td style="border: 1px solid #000000; font-weight: bold;">Отправление</td>
							<td style="border: 1px solid #000000; font-weight: bold;">Прибытие</td>
						</tr>
						<tr>
							<td style="border: 1px solid #000000">' . $direction->startPoint . '<br>' . $trip->departure . '</td>
							<td style="border: 1px solid #000000">' . $direction->endPoint . '<br>' . $trip->arrival . '</td>
						</tr>
					</table>
					<br/><br/><br/><br/>
					<div style="font-size: 12px; text-align: center; font-weight: bold;">Информация о пассажирах и тарифах</div>
					<br/>
					<table style="border-collapse: collapse; width: 100%; font-size: 10px; text-align: center;">
						<tr>
							<td style="border: 1px solid #000000; font-weight: bold; width: 180px;">ФИО</td>
							<td style="border: 1px solid #000000; font-weight: bold;">Тип документа</td>
							<td style="border: 1px solid #000000; font-weight: bold;">Номер документа</td>
							<td style="border: 1px solid #000000; font-weight: bold; width: 60px;">Место</td>
							<td style="border: 1px solid #000000; font-weight: bold; width: 80px;">Стоимость, руб</td>
						</tr>
						<tr>
							<td style="border: 1px solid #000000">' . $profile->name . ' ' . $profile->middle_name . ' ' . $profile->last_name . '</td>
							<td style="border: 1px solid #000000">Паспорт</td>
							<td style="border: 1px solid #000000">' . Profiles::getDocType($profile->doc_type) . ': ' . $profile->doc_num . '</td>
							<td style="border: 1px solid #000000">' . $ticket->place . '</td>
							<td style="border: 1px solid #000000">' . $ticket->price . '</td>
						</tr>
						<tr>
							<td style="border-top: 1px solid #000000"></td>
							<td style="border-top: 1px solid #000000"></td>
							<td style="border-top: 1px solid #000000"></td>
							<td style="border: 1px solid #000000">Итого</td>
							<td style="border: 1px solid #000000">' . $ticket->price . '</td>
						</tr>
					</table>
					<br/>
					<div style="width: 100%; float: left; height: 40px; font-size:16px;"><b>Статус билета:</b> <span style="color: ' . ($ticket->status == 0 ? "red" : "green") . '">' . ($ticket->status == 0 ? "Забронировано" : "Оплачено") . '</span></div>
					<div style="width: 100%; float: left; height: 150px; font-size:10px;">
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
					<div style="width: 100%; float: left; height: 10px;">
						<hr style="height: 3px; background-color: #000000;">
					</div>
					<div style="width: 100%; height: 60px; font-size: 24px; font-weight: bold; color: red; background-color: #ffffff;">Спринт - тур</div>
				';
$pdf->writeHTML($pdfTicket, true, true, false, false, '');
ob_end_clean();
$pdf->Output("ticket-" . $ticket->id . ".pdf", "I");
?>