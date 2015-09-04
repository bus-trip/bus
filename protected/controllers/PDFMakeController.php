<?php

class PDFMakeController extends Controller
{
//	public function actionIndex($profileId)
//	{
//		if (isset($profileId)) {
//			$Profile = Profiles::model()->findByPk($profileId);
//			$Profile->created = date('d.m.Y', strtotime($Profile->created));
//			if ($Profile) $Ticket = Tickets::model()->findByPk($Profile->tid);
//			if ($Ticket) {
//				$Trip = Trips::model()->findByPk($Ticket->idTrip);
//			}
//			if ($Trip) {
//				$Direction = Directions::model()->findByPk($Trip->idDirection);
//				$Bus = Buses::model()->findByPk($Trip->idBus);
//				$Bus->number = $Bus->number == 'нет' ? 'Не указан' : $Bus->number;
//				$Trip->departure = date('d.m.Y H:i', strtotime($Trip->departure));
//				$Trip->arrival = date('d.m.Y H:i', strtotime($Trip->arrival));
//			}
//			$this->render(
//				'index',
//				array(
//					'profile'   => $Profile->attributes,
//					'ticket'    => $Ticket->attributes,
//					'trip'      => $Trip->attributes,
//					'direction' => $Direction->attributes,
//					'bus'       => $Bus->attributes
//				)
//			);
//		}
//	}

	public function actionTicket($profileId)
	{
		if (($Profile = Profiles::model()->findByPk($profileId))) {
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
				'ticket',
				array(
					'profile'   => $Profile,
					'ticket'    => $Ticket,
					'bus'       => $Bus,
					'direction' => $Direction,
					'trip'      => $Trip
				)
			);
		}

		$this->render(
			'ticket',
			array(
				'error' => 'Профиль не найден',
			)
		);
	}

	public function actionTripTickets($tripId)
	{
		$this->render(
			'triptickets',
			array(
				'tripId' => $tripId,
			)
		);
	}

	public function actionBoardingTicketsList($id)
	{
		$criteria = new CDbCriteria();
		$criteria->condition = 'idTrip=' . $id . ' and (status=' . TICKET_CONFIRMED . ' or status=' . TICKET_RESERVED . ')';
		$tickets = Tickets::model()->findAll($criteria);
		$page = 2;
		foreach ($tickets as $t) {
			$profile = Profiles::model()->findByAttributes(array('tid' => $t->id));
			$page--;
			if ($profile) {
				if($page == 0) {
					$this->actionBoardingTicket($profile->id, TRUE);
					$page = 2;
				}
				else $this->actionBoardingTicket($profile->id);
			}
		}
	}

	public function actionBoardingTicket($profileId, $pagebreak = FALSE)
	{
		$Organization = Organization::model()->findByPk(1);
		$Profile = Profiles::model()->findByPk($profileId);
		$this->renderPartial(
			'boardingticket',
			array(
				'organization'  => array(
					'name'     => $Organization->name,
					'contacts' => $Organization->contacts,
					'site'     => $Organization->site,
					'email'    => $Organization->email,
					'info'     => $Organization->info,
				),
				'ticketId'      => $Profile->tid,
				'name'          => $Profile->last_name . " " . $Profile->name . " " . $Profile->middle_name,
				'birthDate'     => $Profile->birth,
				'passport'      => $Profile->passport,
				'direction'     => $Profile->t->idTrip0->idDirection0->startPoint . " - " . $Profile->t->idTrip0->idDirection0->endPoint,
				'departure'     => date("d.m.Y", strtotime($Profile->t->idTrip0->departure)),
				'departureTime' => date("H:i", strtotime($Profile->t->idTrip0->departure)),
				'arrival'       => date("d.m.Y", strtotime($Profile->t->idTrip0->arrival)),
				'arrivalTime'   => date("H:i", strtotime($Profile->t->idTrip0->arrival)),
				'bus'           => $Profile->t->idTrip0->idBus0->model . " " . $Profile->t->idTrip0->idBus0->number,
				'place'         => $Profile->t->place,
				'price'         => $Profile->t->price,
				'pageBreak'     => $pagebreak,
			)
		);
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