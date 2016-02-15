<?php
/**
 * Part of bus 2015
 * Created by: Александр on 07.06.2015:22:07
 */

namespace UserInterface\models;

use CFormModel;
use Directions;
use Trips;
use UserInterface\controllers\DefaultController;

class Checkout extends CFormModel
{
	/**
	 * @var int Id рейса
	 */
	public $tripId;
	public $directionId;
	public $pointFrom;
	public $pointTo;
	public $date;
	public $profiles;
	public $profileStep = 1;
	public $places;
	public $placesStep  = 1;
	public $reviewStep  = 1;
	public $address_from;
	public $address_to;
	public $plane;

	public function attributeLabels()
	{
		return [
			'pointFrom'    => 'Откуда',
			'pointTo'      => 'Куда',
			'date'         => 'Отправление',
			'places'       => 'Место',
			'address_from' => 'Посадка (адрес)',
			'address_to'   => 'Высадка (адрес)',
		];
	}

	public function rules()
	{
		return [
			['pointFrom, pointTo', 'required', 'on' => DefaultController::STEP_FIND],
			['tripId, directionId, date', 'safe', 'on' => DefaultController::STEP_FIND],
			['profileStep, profiles', 'required', 'on' => DefaultController::STEP_PROFILE],
			['profileStep', 'in', 'range' => [1], 'on' => DefaultController::STEP_PROFILE],
			['address_from, address_to', 'safe', 'on' => DefaultController::STEP_PROFILE],
			['reviewStep', 'required', 'on' => DefaultController::STEP_REVIEW],
			['reviewStep', 'in', 'range' => [1], 'on' => DefaultController::STEP_REVIEW],
			['placesStep', 'required', 'on' => DefaultController::STEP_PLACE],
			['placesStep', 'in', 'range' => [1], 'on' => DefaultController::STEP_PLACE],
			['places', 'placesValidate', 'on' => DefaultController::STEP_PLACE],
			['plane', 'safe', 'on' => DefaultController::STEP_PLACE],

		];
	}

	public function placesValidate($attribute)
	{
		if (empty($this->places)) {
			$this->addError($attribute, 'Необходимо выбрать места');
			return;
		}

		$trip   = Trips::model()->with('idBus0')->findByPk($this->tripId);
		$direction = Directions::model()->findByPk($this->directionId);
		$places = $trip ? DefaultController::getAvailablePlaces($trip, $direction, true) : [];
		foreach ($this->places as $place) {
			if (!isset($places[$place]))
				$this->addError($attribute, $place);
		}
	}
}