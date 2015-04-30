<?php

class PDFMakeController extends Controller
{
	public function actionIndex($profileId)
	{
		if (isset($profileId)) {
			$Profile = Profiles::model()->findByPk($profileId);
			$Profile->created = date('d.m.Y', strtotime($Profile->created));
			if ($Profile) $Ticket = Tickets::model()->findByPk($Profile->tid);
			if ($Ticket) {
				$Trip = Trips::model()->findByPk($Ticket->idTrip);
			}
			if ($Trip) {
				$Direction = Directions::model()->findByPk($Trip->idDirection);
				$Bus = Buses::model()->findByPk($Trip->idBus);
				$Bus->number = $Bus->number == 'нет' ? 'Не указан' : $Bus->number;
				$Trip->departure = date('d.m.Y H:i', strtotime($Trip->departure));
				$Trip->arrival = date('d.m.Y H:i', strtotime($Trip->arrival));
			}
			$this->render(
				'index',
				array(
					'profile'   => $Profile->attributes,
					'ticket'    => $Ticket->attributes,
					'trip'      => $Trip->attributes,
					'direction' => $Direction->attributes,
					'bus'       => $Bus->attributes
				)
			);
		}
	}

	public function actionTicket($profileId)
	{
		if (isset($profileId)) {
			$Profile = Profiles::model()->findByPk($profileId);
			$Profile->created = date('d.m.Y', strtotime($Profile->created));
			if ($Profile) $Ticket = Tickets::model()->findByPk($Profile->tid);
			if ($Ticket) {
				$Trip = Trips::model()->findByPk($Ticket->idTrip);
			}
			if ($Trip) {
				$Direction = Directions::model()->findByPk($Trip->idDirection);
				$Bus = Buses::model()->findByPk($Trip->idBus);
				$Bus->number = $Bus->number == 'нет' ? 'Не указан' : $Bus->number;
				$Trip->departure = date('d.m.Y H:i', strtotime($Trip->departure));
				$Trip->arrival = date('d.m.Y H:i', strtotime($Trip->arrival));
			}

			$pdf = Yii::createComponent('application.extensions.tcpdf.ETcPdf', 'P', 'cm', 'A4', TRUE, 'UTF-8');
			$pdf->SetCreator(PDF_CREATOR);
			$pdf->SetAuthor("Trips operator");
			$pdf->SetTitle("Ticket");
			$pdf->setPrintHeader(FALSE);
			$pdf->setPrintFooter(FALSE);
			$pdf->AddPage();
			$pdf->SetFont("dejavuserif", "", 7);

			$pdfTicket = '
					<table>
						<tr>
							<td style="height: 25px;">' . $Profile->created . '</td>
							<td style="height: 25px; text-align: right;">Электронный билет, версия для печати</td>
						</tr>
					</table>
					<div style="width: 100%; height: 180px; font-size: 24px; font-weight: bold;">Спринт - тур</div>
					<br/>
					<table style="font-size: 12px;">
						<tr>
							<td style="height: 30px;">Электронный билет № <b>' . $Ticket->id . '</b></td>
							<td style="height: 30px;">Дата покупки: <b>' . $Profile->created . '</b></td>
						</tr>
					</table>
					<div style="width: 100%; float: left; height: 20px; font-size: 8px;">Перевозчик: "Спринт-Тур"</div>
					<div style="width: 100%; float: left; height: 20px; font-size: 8px;">ИНН 7463746523</div>
					<div style="width: 100%; float: left; height: 20px; font-size: 8px;">Тел. +7 909 645 3485</div>
					<div style="width: 100%; float: left; height: 40px; font-size: 12px; font-weight: bold; text-align: center;">Информация о рейсе</div>
					<div style="width: 100%; float: left; height: 40px; font-size: 12px; font-weight: bold;">Гос. номер автобуса: ' . $Bus->number . '</div>
					<br/>
					<table style="border-collapse: collapse; width: 100%; font-size: 10px;">
						<tr>
							<td style="border: 1px solid #000000; font-weight: bold;">Отправление</td>
							<td style="border: 1px solid #000000; font-weight: bold;">Прибытие</td>
						</tr>
						<tr>
							<td style="border: 1px solid #000000">' . $Direction->startPoint . '<br>' . $Ticket->address_from . '<br>' . $Trip->departure . '</td>
							<td style="border: 1px solid #000000">' . $Direction->endPoint . '<br>' . $Ticket->address_to . '<br>' . $Trip->arrival . '</td>
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
							<td style="border: 1px solid #000000">' . $Profile->name . ' ' . $Profile->middle_name . ' ' . $Profile->last_name . '</td>
							<td style="border: 1px solid #000000">Паспорт</td>
							<td style="border: 1px solid #000000">' . $Profile->passport . '</td>
							<td style="border: 1px solid #000000">' . $Ticket->place . '</td>
							<td style="border: 1px solid #000000">' . $Ticket->price . '</td>
						</tr>
						<tr>
							<td style="border-top: 1px solid #000000"></td>
							<td style="border-top: 1px solid #000000"></td>
							<td style="border-top: 1px solid #000000"></td>
							<td style="border: 1px solid #000000">Итого</td>
							<td style="border: 1px solid #000000">' . $Ticket->price . '</td>
						</tr>
					</table>
					<br/>
					<div style="width: 100%; float: left; height: 40px; font-size:10px;"><b>Статус билета:</b> Оплачен</div>
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
					<div style="width: 100%; height: 60px; font-size: 24px; font-weight: bold; background-color: #ffffff;">Спринт - тур</div>
				';
			$pdf->writeHTML($pdfTicket, TRUE, TRUE, FALSE, FALSE, '');
			$pdf->Output("ticket-" . $Ticket->id . ".pdf", "I");
		}
	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}