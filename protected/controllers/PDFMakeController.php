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