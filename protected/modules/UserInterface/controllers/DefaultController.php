<?php
/**
 * Part of bus 2015
 * Created by: Александр on 16.04.2015:22:44
 */

namespace UserInterface\controllers;

use CArrayDataProvider;
use CDbCriteria;
use CException;
use CHtml;
use CJavaScript;
use Directions;
use Dirpoints;
use Invoice;
use Profiles;
use TempReserve;
use Tickets;
use Trips;
use User;
use UserInterface\components\Controller;
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
				return 'Найти билеты';
			case self::STEP_PLACE:
				return 'Выберите места';
			case self::STEP_PROFILE:
				return 'Данные билетов';
			case self::STEP_REVIEW:
				return 'Проверка';
			case self::STEP_PAYMENT:
				return 'Оплата';
			default:
				return '';
		}
	}

	private function getPartDirections($startPoint, $endPoint)
	{
		$directions = [];
		$dirsAll    = Directions::model()->findAll(
			[
				'condition' => 'status != ' . DIRTRIP_CANCELED . ' and parentId !=0 and startPoint = "' . $startPoint . '" and endPoint="' . $endPoint . '"',
				'group'     => 'parentId'
			]
		);
		foreach ($dirsAll as $ds) {
			$directions[] = $ds;
		}

		return $directions;
	}

	private function getStationsByDirectionId($id)
	{
		$points   = [];
		$point    = Dirpoints::model()->findByAttributes(
			[
				'directionId' => $id,
				'prevId'      => 0
			]
		);
		$points[] = $point->name;
		while ($point = Dirpoints::model()->find(['condition' => 'directionId=' . $id . ' and prevId=' . $point->id])) {
			$points[] = $point->name;
		}

		return $points;
	}

	private function getFreePlaces($tripId, $startPoint, $endPoint)
	{
		$trip      = Trips::model()->findByPk($tripId);
		$points    = $this->getStationsByDirectionId($trip->idDirection);
		$dirPoints = [];
		foreach ($points as $p) {
			$dirPoints[$p] = 0;
		}

		$allDirs = Directions::model()
							 ->findAll(['condition' => 'parentId=' . $trip->idDirection . ' and status!=' . DIRTRIP_CANCELED]);
		$dirArr  = [];
		foreach ($allDirs as $d) {
			$tickets  = Tickets::model()
							   ->count(['condition' => 'idTrip=' . $tripId . ' and idDirection=' . $d['id'] . ' and status!=' . Tickets::STATUS_CANCELED]);
			$dirArr[] = [
				'startPoint' => $d['startPoint'],
				'endPoint'   => $d['endPoint'],
				'tickets'    => $tickets
			];
		}

		foreach ($dirArr as $d) {
			if ($d['tickets'] > 0) {
				$i = false;
				foreach ($dirPoints as $k => $p) {
					if ($k == $d['startPoint']) {
						$i = true;
					}
					if ($i && $k != $d['endPoint']) {
						$dirPoints[$k] += $d['tickets'];
					}
					if ($k == $d['endPoint']) {
						$i = false;
					}
				}
			}
		}

		$allPlaces    = $trip->idBus0->places;
		$i            = false;
		$maxFreePlace = 0;
		foreach ($dirPoints as $k => $p) {
			if ($k == $startPoint) {
				$i = true;
			}
			if ($i && $k != $endPoint) {
				if ($p > $maxFreePlace) $maxFreePlace = $p;
			}
			if ($k == $endPoint) {
				$i = false;
			}
		}

		return $allPlaces - $maxFreePlace;
	}

	public function behaviors()
	{
		$steps = [self::STEP_FIND,
				  self::STEP_PLACE,
				  self::STEP_PROFILE,
				  self::STEP_REVIEW,
		];
		if ($this->robokassa()) {
			array_push($steps, self::STEP_PAYMENT);
		}
		return [
			'wizard' => [
				'class'       => 'ext.Wizard.WizardBehavior',
				'steps'       => $steps,
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
		$profileModels = $userProfiles = $selPoints = $places = $prices = [];
		$points        = ['' => '- Выберите -'];
		$trip          = false;
		$checkoutModel = new Checkout($event->getStep());
		$invoice       = false;
		if ($attributes = Yii::app()->getRequest()->getPost(CHtml::modelName($checkoutModel))) {
			$checkoutModel->setAttributes($attributes);
			switch ($event->getStep()) {
				case self::STEP_FIND:
					unset($_SESSION['temp_reserve']);
					break;
				case self::STEP_PLACE:
					$savedData                  = $this->read(self::STEP_FIND);
					$checkoutModel->tripId      = $savedData['tripId'];
					$checkoutModel->directionId = $savedData['directionId'];
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
					$this->reservedTicket($savedData['tripId'], $savedData['directionId'], $savedData['places']);
				}
			}
		}

		switch ($event->getStep()) {
			case self::STEP_FIND:
				$query               = Dirpoints::model()->findAll();
				$checkoutModel->date = date("d.m.Y");
				foreach ($query as $q) {
					if ($q->direction->status != DIRTRIP_CANCELED) {
						$points[$q->name] = $q->name;
					}
				}
				ksort($points);

				break;
			case self::STEP_PLACE:
				if (Yii::app()->user->isGuest)
					$this->redirect($this->createUrl('/user/login'));

				$savedData            = $this->read();
				$trip                 = Trips::model()->with('idBus0')->findByPk($savedData[self::STEP_FIND]['tripId']);
				$direction            = Directions::model()->findByPk($savedData[self::STEP_FIND]['directionId']);
				$places               = $trip ? self::getAvailablePlaces($trip, $direction) : [];
				$checkoutModel->plane = $trip->idBus0->plane;
				if (isset($_SESSION['temp_reserve'][$trip->id . "|" . $savedData[self::STEP_FIND]['directionId']]) &&
					!empty($savedData[self::STEP_PLACE]['places'])
				) {
					$checkoutModel->places = $savedData[self::STEP_PLACE]['places'];
				}

				break;
			case self::STEP_PROFILE:
				$savedDataProfile = $this->read(self::STEP_PROFILE);
				$savedDataPlaces  = $this->read(self::STEP_PLACE);

				foreach ($savedDataPlaces['places'] as $num) {
					$profileModels[] = new Profiles();
				}

				$userProfilesModels = Profiles::model()
											  ->findAllByAttributes(['uid' => Yii::app()->getUser()->id],
																	['order' => 'created DESC']);
				foreach ($userProfilesModels as $p) {
					$key                = md5($p->doc_type . '::' . $p->doc_num . '::' . $p->last_name . '::' . $p->black_list);
					$userProfiles[$key] = $p;
				}

				if (!empty($savedDataProfile['profiles'])) {
					foreach ($savedDataProfile['profiles'] as $i => $item) {
						$profileModel = new Profiles();
						$profileModel->setAttributes($item);
						$profileModels[$i] = $profileModel;
					}
				}

				break;
			case self::STEP_REVIEW:
				$savedData = $this->read();
				$trip      = Trips::model()->with('idBus0', 'idDirection0')
								  ->findByPk($savedData[self::STEP_FIND]['tripId']);

				$savedDataDirection = $this->read(self::STEP_FIND);
				$selDir             = Directions::model()->findByPk($savedDataDirection['directionId']);
				$savedDataPlaces    = $this->read(self::STEP_PLACE);
				$savedDataProfile   = $this->read(self::STEP_PROFILE);
				foreach ($savedDataPlaces['places'] as $i => $num) {
					/** @var \DiscountsController $discount */
					list($discount) = Yii::app()->createController('discounts');
					$prices[$num] = $discount->getDiscountAmount($savedDataProfile['profiles'][$i]['birth'], $num, $selDir->price);
				}

				break;
			case self::STEP_PAYMENT:
				$invoice              = new Invoice();
				$invoice->amount      = 2000;
				$invoice->user_id     = Yii::app()->user->id;
				$invoice->description = 'оплата посадочных мест №1,2 на рейс Элиста-Москва 12.04.2016';

				if ($attributes = Yii::app()->getRequest()->getPost(CHtml::modelName($invoice))) {
					$invoice->created_at = new CDbExpression('NOW()');
					if ($invoice->save()) {
						$user = User::model()->findByPk(Yii::app()->user->id);
						// Компонент переадресует пользователя в свой интерфейс оплаты
						Yii::app()->robokassa->pay(
							$invoice->amount,
							$invoice->id,
							$invoice->description,
							$user->mail
						);
					}
				}

				break;
		}

		if (!$event->handled) {
			$this->render('wizard', ['event'         => $event,
									 'checkoutModel' => $checkoutModel,
									 'profileModels' => $profileModels,
									 'userProfiles'  => $userProfiles,
									 'trip'          => $trip,
									 'points'        => $points,
									 'places'        => $places,
									 'prices'        => $prices,
									 'invoice'       => $invoice,
									 'back'          => $this->backButton(),
									 'saved'         => $this->read()]);
		}
	}

	/**
	 * @param WizardEvent $event
	 */
	public function wizardFinished($event)
	{
		$tripId       = $event->data[self::STEP_FIND]['tripId'];
		$directionId  = $event->data[self::STEP_FIND]['directionId'];
		$address_from = $event->data[self::STEP_PROFILE]['address_from'];
		$address_to   = $event->data[self::STEP_PROFILE]['address_to'];
		foreach ($event->data[self::STEP_PLACE]['places'] as $id => $placeId) {
			$profileData = $event->data[self::STEP_PROFILE]['profiles'][$id];
			$this->createOrder($tripId, $directionId, $placeId, $profileData, $address_from, $address_to);
		}
		$event->sender->reset();
	}

	protected function reservedTicket($tripId, $directionId, $placeIds)
	{
		foreach ($placeIds as $placeId) {
			TempReserve::model()
					   ->deleteAllByAttributes(['tripId' => $tripId, 'directionId' => $directionId, 'placeId' => $placeId]);

			$tempReserve              = new TempReserve();
			$tempReserve->tripId      = $tripId;
			$tempReserve->directionId = $directionId;
			$tempReserve->placeId     = $placeId;
			$tempReserve->created     = time();
			$tempReserve->save();
		}
		$_SESSION['temp_reserve'][$tripId . "|" . $directionId] = $placeIds;
	}

	/**
	 * @param $tripId
	 * @param $profileData
	 *
	 * @return bool
	 */
	public function createOrder($tripId, $directionId, $placeId, $profileData, $address_from, $address_to)
	{
		$tempReserve = TempReserve::model()
								  ->findAllByAttributes(['tripId' => $tripId, 'placeId' => $placeId, 'directionId' => $directionId]);
		if (!empty($tempReserve)) {
			$profile = new Profiles();
			list($discount) = Yii::app()->createController('discounts');
			$profile->setAttributes($profileData);
			if ($profile->validate()) {
				$ticket               = new Tickets();
				$ticket->status       = Tickets::STATUS_RESERVED;
				$ticket->idTrip       = $tripId;
				$ticket->idDirection  = $directionId;
				$ticket->place        = $placeId;
				$ticket->address_from = $address_from;
				$ticket->address_to   = $address_to;
				if ($ticket->save()) {
					$profile->tid = $ticket->id;
					$profile->uid = Yii::app()->getUser()->id;
					$profile->save();
					$ticket->price = $discount->getDiscount($profile->id, $directionId);
					TempReserve::model()
							   ->deleteAllByAttributes(['tripId' => $tripId, 'directionId' => $directionId, 'placeId' => $placeId]);

					return $ticket->save();
				}
			}
			if (isset($_SESSION['temp_reserve'][$tripId . "|" . $directionId])) {
				unset($_SESSION['temp_reserve'][$tripId . "|" . $directionId]);
			}
		}
	}

	/**
	 * @param WizardEvent $event
	 */
	public function wizardInvalidStep($event)
	{
//		Yii::app()->getUser()->setFlash('notice', $event->getStep() . ' is not a valid step in this wizard');
	}

	public function actionIndex($step = null)
	{
		if ($step === null && isset($_SESSION['temp_reserve'])) {
			unset($_SESSION['temp_reserve']);
		} elseif (isset($_SESSION['temp_reserve'])) {
			foreach ($_SESSION['temp_reserve'] as $id => $placeIds) {
				$k           = explode('|', $id);
				$tripId      = $k[0];
				$directionId = $k[1];
				$criteria    = new CDbCriteria();
				$criteria->addCondition('tripId=:tripId AND directionId=:directionId');
				$criteria->params = [':tripId' => $tripId, ':directionId' => $directionId];
				$criteria->addInCondition('placeId', array_values($placeIds));
				$tempReserve = TempReserve::model()->findAll($criteria);

				if (!$tempReserve) {
					unset($_SESSION['temp_reserve']);
					$this->resetWizard($step);
					$this->redirect('/UserInterface/default/timeout');
				}
			}
		}

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

	public function actionTimeout()
	{
		$this->layout    = '//layouts/column1';
		$this->pageTitle = 'Время оформления вышло';
		return $this->render('error');
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
				$directions = $this->getPartDirections($checkoutModel->pointFrom, $checkoutModel->pointTo);
				$tripsAttr  = [];
				foreach ($directions as $d) {
					$criteria = new CDbCriteria();
//					$criteria->condition = "idDirection=" . $d['id'] . " and departure between '" . date('Y-m-d', strtotime($checkoutModel->date)) . " 00:00:00' and '" . date('Y-m-d', strtotime($checkoutModel->date)) . " 23:59:59'";
					$criteria->condition = "idDirection=" . $d['parentId'] . " and departure between '" . date('Y-m-d', strtotime($checkoutModel->date)) . " 00:00:00' and '" . date('Y-m-d', strtotime($checkoutModel->date)) . " 23:59:59'";
					$trips               = Trips::model()
												->findAllByAttributes(['idDirection' => $d['parentId']], $criteria);
					$parent              = Directions::model()->findByPk($d['parentId']);
					foreach ($trips as $t) {
						$freePlaces = $this->getFreePlaces($t->id, $checkoutModel->pointFrom, $checkoutModel->pointTo);
						if ($freePlaces) {
							$tripsAttr[] = [
								'id'          => $t->attributes['id'],
								'trip'        => $checkoutModel->pointFrom . ' - ' . $checkoutModel->pointTo,
								'direction'   => $parent->startPoint . ' - ' . $parent->endPoint,
								'directionId' => $d['id'],
								'departure'   => $t->attributes['departure'],
								'arrival'     => $t->attributes['arrival'],
								'price'       => $d['price'],
								'places'      => $freePlaces
							];
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
	public static function getAvailablePlaces(Trips $trip, Directions $direction, $onlyValues = false)
	{
		$criteria = new CDbCriteria();
//		$criteria->condition = 'idTrip=:idTrip and idDirection=:idDirection';
		$criteria->condition = 'idTrip=:idTrip';
//		$criteria->params = [':idTrip' => $trip->id, ':idDirection' => $direction->id];
		$criteria->params = [':idTrip' => $trip->id];
		$criteria->addInCondition('t.status', [Tickets::STATUS_RESERVED, Tickets::STATUS_CONFIRMED]);

		$tickets = Tickets::model()->findAll($criteria);

		$notAvailPlace = [];
		foreach ($tickets as $ticket) {
			$dirs = Directions::model()->find(['condition' => 'id=' . $ticket->idDirection]);
			unset($dPoints);
			$dPoints[] = Dirpoints::model()
								  ->find(['condition' => 'name="' . $dirs->startPoint . '" and directionId=' . $direction->parentId])->attributes;
			$p         = $dPoints[0];
			while (($p = Dirpoints::model()
								  ->find(['condition' => 'directionId=' . $direction->parentId . ' and prevId=' . $p['id']])) && $p['name'] != $dirs->endPoint) {
				$dPoints[] = $p->attributes;
				$p         = $p->attributes;
			}
			unset($tDirs);
			foreach ($dPoints as $p) {
				$tDirs[$ticket->place][] = $p['name'];
			}
			unset($dPoints);
			$dPoints[] = Dirpoints::model()
								  ->find(['condition' => 'name="' . $direction->startPoint . '" and directionId=' . $direction->parentId])->attributes;
			$p         = $dPoints[0];
			while (($p = Dirpoints::model()
								  ->find(['condition' => 'directionId=' . $direction->parentId . ' and prevId=' . $p['id']])) && $p['name'] != $direction->endPoint) {
				$dPoints[] = $p->attributes;
				$p         = $p->attributes;
			}
			unset($rDir);
			foreach ($dPoints as $p) {
				$rDir[] = $p['name'];
			}
			if (isset($tDirs) && is_array($tDirs) && isset($rDir) && is_array($rDir)) {
				foreach ($tDirs as $p => $t) {
					foreach ($rDir as $r) {
						if (in_array($r, $t) && !in_array($p, $notAvailPlace)) $notAvailPlace[] = $p;
					}
				}
			}
		}

		$tempReserve = TempReserve::model()
								  ->findAllByAttributes(['tripId' => $trip->id, 'directionId' => $direction->id]);
		if (!empty($tempReserve)) {
			foreach ($tempReserve as $item) {
				if (isset($_SESSION['temp_reserve'][$trip->id . "|" . $direction->id]) && !in_array($item->placeId, $_SESSION['temp_reserve'][$trip->id . "|" . $direction->id]))
					$notAvailPlace[] = $item->placeId;
			}
		}

		/** @var \DiscountsController $discount */
		list($discount) = Yii::app()->createController('discounts');

		$places = [];
		for ($i = 1; $i <= $trip->idBus0->places; $i++) {
			if ($onlyValues)
				$places[$i] = $i;
			elseif (!in_array($i, $notAvailPlace)) {
				$price      = $discount->getDiscountByPlace($i, $direction->price);
				$places[$i] = '№' . $i . ': <b>' . $price . '</b> руб.';
			} else
				$places['not-' . $i] = $i . ' <b class="color-red">Занято</b>';
		}

		return $places;
	}
}