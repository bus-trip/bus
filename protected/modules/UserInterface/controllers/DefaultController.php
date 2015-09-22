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
	protected function getWizardTitle($step)
	{
		switch ($step) {
			case self::STEP_FIND:
				return 'Рейс';
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

	const STEP_FIND    = 'find';
	const STEP_PAYMENT = 'payment';
	const STEP_PROFILE = 'profile';
	const STEP_REVIEW  = 'review';

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
								  self::STEP_PROFILE,
								  self::STEP_REVIEW,
								  //								  self::STEP_PAYMENT,
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
		$profileModels = $userProfiles = $selPoints = [];
		$points        = ['' => '- Выберите -'];
		$trip          = false;
		$checkoutModel = new Checkout($event->getStep());
		if ($attributes = Yii::app()->getRequest()->getPost(CHtml::modelName($checkoutModel))) {
			$checkoutModel->setAttributes($attributes);

			switch ($event->getStep()) {
				case self::STEP_FIND:

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
			}
		} elseif ($event->getStep() == self::STEP_FIND) {
			$query               = Directions::model()->findAll();
			$checkoutModel->date = date("d.m.Y");
			foreach ($query as $q) {
				if (!in_array($q->startPoint, $points)) $points[$q->startPoint] = $q->startPoint;
				if (!in_array($q->endPoint, $points)) $points[$q->endPoint] = $q->endPoint;
			}
		} elseif ($event->getStep() == self::STEP_PROFILE) {
			$savedData    = $this->read(self::STEP_PROFILE);
			$userProfiles = Profiles::model()
									->findAllByAttributes(['uid' => Yii::app()->getUser()->id],
														  ['order' => 'created DESC']);
			if (!empty($savedData['profiles'])) {
				foreach ($savedData['profiles'] as $i => $item) {
					$profileModel = new Profiles();
					$profileModel->setAttributes($item);
					$profileModels[$i] = $profileModel;
				}
			} else {
				$profileModels[] = new Profiles();
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
		foreach ($event->data[self::STEP_PROFILE]['profiles'] as $placeId => $profileData) {
			$this->createOrder($tripId, ($placeId + 1), $profileData);
		}
		$event->sender->reset();
	}

	/**
	 * @param $tripId
	 * @param $profileData
	 *
	 * @return bool
	 */
	public function createOrder($tripId, $placeId, $profileData)
	{
		$Profile = new Profiles();
		list($discount) = Yii::app()->createController('discounts');
		$Profile->setAttributes($profileData);
		if ($Profile->validate()) {
			$Ticket         = new Tickets();
			$Ticket->status = 1;
			$Ticket->idTrip = $tripId;
			$Ticket->place  = $placeId;
			if ($Ticket->validate() && $Ticket->save()) {
				$Profile->tid = $Ticket->id;
				$Profile->save();
				$Ticket->price = $discount->getDiscount($Profile->id);
				return $Ticket->save();
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
}