<?php
/**
 * Part of bus 2015
 * Created by: Александр on 16.04.2015:22:44
 */

namespace UserInterface\controllers;

use Buses;
use CArrayDataProvider;
use CDbCriteria;
use CException;
use CJavaScript;
use Directions;
use Profiles;
use TempReserve;
use Tickets;
use Trips;
use UserInterface\components\Controller;
use CHtml;
use UserInterface\models\Checkout;
use WizardBehavior;
use WizardEvent;
use Yii;

class DefaultController extends Controller
{
	const STEP_FIND    = 'find';
	const STEP_PLACE   = 'place';
	const STEP_PAYMENT = 'payment';
	const STEP_PROFILE = 'profile';
	const STEP_REVIEW  = 'review';

	protected function getWizardTitle($step)
	{
		switch ($step) {
			case self::STEP_FIND:
				return 'Рейс';
			case self::STEP_PLACE:
				return 'Места';
			case self::STEP_PROFILE:
				return 'Профиль';
			case self::STEP_REVIEW:
				return 'Проверка';
			case self::STEP_PAYMENT:
				return 'Оплата';
			default:
				return '';
		}
	}

	private function getDirections($startPoint, $endPoint = '')
	{
		$directions          = [];
		$criteria            = new CDbCriteria();
		$criteria->condition = 'status = ' . DIRTRIP_MAIN . ' or status = ' . DIRTRIP_EXTEND;
		$criteria->group     = 'parentId';
		$dirsAll             = Directions::model()->findAllByAttributes(['startPoint' => $startPoint], $criteria);
		$dirsByStart         = null;
		foreach ($dirsAll as $ds) {
			if ($ds->attributes['parentId'] != 0) $dirsByStart[] = Directions::model()
																			 ->findByPk($ds->attributes['parentId'])->attributes;
			else $dirsByStart[] = Directions::model()->findByPk($ds->attributes['id'])->attributes;
		}
		if ($dirsByStart) {
			foreach ($dirsByStart as $ds) {
				if (isset($endPoint)) {
					$points = $this->getStationsByDirectionId($ds['id']);
					if (in_array($startPoint, $points) && in_array($endPoint, $points) && array_search($startPoint, $points) < array_search($endPoint, $points)) $directions[] = $ds;
				} else $directions[] = $ds;
			}
		}
		return array_unique($directions);
	}

	private function getStationsByDirectionId($id)
	{
		$dir      = Directions::model()->findByPk($id);
		$sPoint   = $dir->startPoint;
		$points   = [];
		$points[] = $dir->startPoint;
		while (($point = Directions::model()
								   ->findByAttributes(['startPoint' => $sPoint, 'parentId' => $dir->id]))) {
			$points[] = $point->startPoint;
			$points[] = $point->endPoint;
			$sPoint   = $point->endPoint;
		}
		$points[] = $dir->endPoint;
		$points   = array_unique($points);

		return $points;
	}

	public function behaviors()
	{
		return [
			'wizard' => [
				'class'       => 'ext.Wizard.WizardBehavior',
				'steps'       => [self::STEP_FIND,
								  self::STEP_PLACE,
								  self::STEP_PROFILE,
								  self::STEP_REVIEW,
				],
				'autoAdvance' => false,
				'finishedUrl' => '/UserInterface/default/complete',
			]
		];
	}

	public function init()
	{
		$this->layout = 'wizard';
		parent::init();
	}

	/**
	 * @param WizardEvent $event
	 *
	 * @return bool
	 */
	public function wizardStart(WizardEvent $event)
	{
		return $event->handled = true;
	}

	/**
	 * @param \WizardEvent $event
	 *
	 * @throws \HttpException
	 */
	public function wizardProcessStep($event)
	{
		$profileModels = $userProfiles = $selPoints = $places = [];
		$points        = ['' => '- Выберите -'];
		$trip          = false;
		$checkoutModel = new Checkout($event->getStep());
		if ($attributes = Yii::app()->getRequest()->getPost(CHtml::modelName($checkoutModel))) {
			$checkoutModel->setAttributes($attributes);
			switch ($event->getStep()) {
				case self::STEP_FIND:
					unset($_SESSION['temp_reserve']);
					break;
				case self::STEP_PLACE:
					$savedData             = $this->read(self::STEP_FIND);
					$checkoutModel->tripId = $savedData['tripId'];
					break;
				case self::STEP_PROFILE:
					$profilesData  = Yii::app()->getRequest()->getPost(CHtml::modelName(new Profiles()));
					$profilesSaved = [];
					if ($profilesData) {
						foreach ($profilesData as $id => $item) {
							$profileModel = new Profiles();
							$profileModel->setAttributes($item);
							if ($profileModel->validate()) {
								$profilesSaved[$id] = $item;
							}
							$profileModels[] = $profileModel;
						}
					}

					if (count($profilesSaved) == count($profilesData)) {
						$checkoutModel->profiles = $profilesSaved;
					}
					break;
				case self::STEP_REVIEW:
					$savedData = $this->read(self::STEP_FIND);
					$trip      = Trips::model()->with('idBus0', 'idDirection0')->findByPk($savedData['tripId']);
					break;
			}

			if ($checkoutModel->validate()) {
				$event->handled = true;

				$saving = $checkoutModel->attributes;
				$event->sender->save($saving);

				if ($event->getStep() == self::STEP_PLACE) {
					$savedData = $this->read(self::STEP_PLACE);
					$this->reservedTicket($savedData['tripId'], $savedData['places']);
				}
			}
		}

		if ($event->getStep() == self::STEP_FIND) {
			$query               = Directions::model()->findAll();
			$checkoutModel->date = date("d.m.Y");
			foreach ($query as $q) {
				if (!in_array($q->startPoint, $points)) $points[$q->startPoint] = $q->startPoint;
				if (!in_array($q->endPoint, $points)) $points[$q->endPoint] = $q->endPoint;
			}
		} elseif ($event->getStep() == self::STEP_PLACE) {
			$savedData = $this->read();
			$trip      = Trips::model()->with('idBus0')->findByPk($savedData[self::STEP_FIND]['tripId']);
			$places    = $trip ? self::getAvailablePlaces($trip) : [];
			if (isset($_SESSION['temp_reserve'][$trip->id]) &&
				!empty($savedData[self::STEP_PLACE]['places'])
			) {
				$checkoutModel->places = $savedData[self::STEP_PLACE]['places'];
			}
		} elseif ($event->getStep() == self::STEP_PROFILE) {
			$savedDataProfile = $this->read(self::STEP_PROFILE);
			$savedDataPlaces  = $this->read(self::STEP_PLACE);

			foreach ($savedDataPlaces['places'] as $num) {
				$profileModels[] = new Profiles();
			}

			$userProfiles = Profiles::model()
									->findAllByAttributes(['uid' => Yii::app()->getUser()->id],
														  ['order' => 'created DESC']);
			if (!empty($savedDataProfile['profiles'])) {
				foreach ($savedDataProfile['profiles'] as $i => $item) {
					$profileModel = new Profiles();
					$profileModel->setAttributes($item);
					$profileModels[$i] = $profileModel;
				}
			}
		} elseif ($event->getStep() == self::STEP_REVIEW) {
			$savedData = $this->read(self::STEP_FIND);
			$trip      = Trips::model()->with('idBus0', 'idDirection0')->findByPk($savedData['tripId']);
		}

		if (!$event->handled) {
			$this->render('wizard', ['event'         => $event,
									 'checkoutModel' => $checkoutModel,
									 'profileModels' => $profileModels,
									 'userProfiles'  => $userProfiles,
									 'trip'          => $trip,
									 'points'        => $points,
									 'places'        => $places,
									 'back'          => $this->backButton(),
									 'saved'         => $this->read()]);
		}
	}

	/**
	 * @param WizardEvent $event
	 */
	public function wizardFinished($event)
	{
		$tripId = $event->data[self::STEP_FIND]['tripId'];
		foreach ($event->data[self::STEP_PLACE]['places'] as $id => $placeId) {
			$profileData = $event->data[self::STEP_PROFILE]['profiles'][$id];
			$this->createOrder($tripId, $placeId, $profileData);
		}
		$event->sender->reset();
	}

	protected function reservedTicket($tripId, $placeIds)
	{
		foreach ($placeIds as $placeId) {
			TempReserve::model()->deleteAllByAttributes(['tripId' => $tripId, 'placeId' => $placeId]);

			$tempReserve          = new TempReserve();
			$tempReserve->tripId  = $tripId;
			$tempReserve->placeId = $placeId;
			$tempReserve->created = time();
			$tempReserve->save();
		}
		$_SESSION['temp_reserve'][$tripId] = $placeIds;
	}

	/**
	 * @param $tripId
	 * @param $profileData
	 *
	 * @return bool
	 */
	public function createOrder($tripId, $placeId, $profileData)
	{
		$tempReserve = TempReserve::model()->findAllByAttributes(['tripId' => $tripId, 'placeId' => $placeId]);
		if(!empty($tempReserve)) {
			$profile = new Profiles();
			list($discount) = Yii::app()->createController('discounts');
			$profile->setAttributes($profileData);
			if ($profile->validate()) {
				$ticket         = new Tickets();
				$ticket->status = TICKET_RESERVED;
				$ticket->idTrip = $tripId;
				$ticket->place  = $placeId;
				if ($ticket->save()) {
					$profile->tid = $ticket->id;
					$profile->save();
					$ticket->price = $discount->getDiscount($profile->id);
					TempReserve::model()->deleteAllByAttributes(['tripId' => $tripId, 'placeId' => $placeId]);
					return $ticket->save();
				}
			}
			if (isset($_SESSION['temp_reserve'][$tripId])) {
				unset($_SESSION['temp_reserve'][$tripId]);
			}
		}
	}

	/**
	 * @param WizardEvent $event
	 */
	public function wizardInvalidStep($event)
	{
		Yii::app()->getUser()->setFlash('notice', $event->getStep() . ' is not a vaild step in this wizard');
	}

	public function actionIndex($step = null)
	{
		$this->pageTitle = $this->getWizardTitle($step);
		$this->process($step);
	}

	public function backButton()
	{
		/** @var WizardBehavior $this */
		$CurrentStep = $this->getCurrentStep() - 1;
		if ($CurrentStep > 0) {
			$previousStep = $this->steps[$CurrentStep - 1];
			$url          = '/' . $this->getModule()->getId() .
				'/' . $this->getId() .
				'/' . $this->getAction()->getId() .
				'/' . $this->queryParam .
				'/' . $previousStep;
		} else {
			$url = '/UserInterface/default/index';
		}

		$output = $this->createUrl($url);

		return $output;
	}

	/**
	 * @throws CException
	 */
	public function actionComplete()
	{
		$this->layout = '//layouts/column1';
		$this->render('complete');
	}

	public function actionSearch()
	{
		$output        = [];
		$checkoutModel = new Checkout(self::STEP_FIND);
		if ($attributes = Yii::app()->getRequest()->getPost(CHtml::modelName($checkoutModel))) {
			$checkoutModel->setAttributes($attributes);
			if ($checkoutModel->validate()) {

				$directions = $this->getDirections($checkoutModel->pointFrom, $checkoutModel->pointTo);
				$tripsAttr  = [];
				foreach ($directions as $d) {
					$criteria            = new CDbCriteria();
					$criteria->condition = "idDirection=" . $d['id'] . " and departure between '" . date('Y-m-d', strtotime($checkoutModel->date)) . " 00:00:00' and '" . date('Y-m-d', strtotime($checkoutModel->date)) . " 23:59:59'";
					$trips               = Trips::model()
												->findAllByAttributes(array('idDirection' => $d['id']), $criteria);
					foreach ($trips as $t) {
						$criteria->condition = "idTrip=" . $t->attributes['id'] . " and status in (" . TICKET_CONFIRMED . "," . TICKET_RESERVED . ")";
						$tickets             = Tickets::model()->count($criteria);
						$bus                 = Buses::model()->findByPk($t->attributes['idBus']);

						if ($bus->places > $tickets) {
							$tripsAttr[] = array(
								'id'          => $t->attributes['id'],
								'direction'   => $d['startPoint'] . ' - ' . $d['endPoint'],
								'departure'   => $t->attributes['departure'],
								'arrival'     => $t->attributes['arrival'],
								'description' => $t->attributes['description']
							);
						}
					}
				}

				$output['success'] = $this->renderPartial('trips',
														  ['trips' => new CArrayDataProvider($tripsAttr),
														   'model' => $checkoutModel], true);
			} else {
				$output['errors'] = $checkoutModel->getErrors();
			}
		}

		header('Content-type: application/json');
		echo CJavaScript::jsonEncode($output);
		Yii::app()->end();
	}

	/**
	 * @param Trips      $trip
	 * @param bool|false $onlyValues
	 *
	 * @return array
	 */
	public static function getAvailablePlaces(Trips $trip, $onlyValues = false)
	{
		$criteria            = new CDbCriteria();
		$criteria->condition = 'idTrip=:idTrip';
		$criteria->params    = array(':idTrip' => $trip->id);
		$criteria->addInCondition('t.status', [TICKET_RESERVED, TICKET_CONFIRMED]);

		$tickets       = Tickets::model()->findAll($criteria);
		$notAvailPlace = [];
		foreach ($tickets as $ticket) {
			$notAvailPlace[] = $ticket->place;
		}

		$tempReserve = TempReserve::model()->findAllByAttributes(['tripId' => $trip->id]);
		if (!empty($tempReserve)) {
			foreach ($tempReserve as $item) {
				if (isset($_SESSION['temp_reserve'][$trip->id]) && !in_array($item->placeId, $_SESSION['temp_reserve'][$trip->id]))
					$notAvailPlace[] = $item->placeId;
			}
		}

		$places = [];
		for ($i = 1; $i <= $trip->idBus0->places; $i++) {
			if (!in_array($i, $notAvailPlace) || $onlyValues)
				$places[$i] = $i;
			else
				$places['not-' . $i] = $i . ' - занято';
		}

		return $places;
	}
}