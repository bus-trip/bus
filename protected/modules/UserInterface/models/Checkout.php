<?php
/**
 * Part of bus 2015
 * Created by: Александр on 07.06.2015:22:07
 */

namespace UserInterface\models;

use CFormModel;
use UserInterface\controllers\DefaultController;

class Checkout extends CFormModel
{
	/**
	 * @var int Id рейса
	 */
	public $tripId;
	public $pointFrom;
	public $pointTo;
	public $date;
	public $profiles;
	public $profileStep = 1;
	public $reviewStep  = 1;

	public function attributeLabels()
	{
		return [
			'pointFrom' => 'Откуда',
			'pointTo'   => 'Куда',
			'date'      => 'Отправление',
		];
	}

	public function rules()
	{
		return [
			['pointFrom, pointTo', 'required', 'on' => DefaultController::STEP_FIND],
			['tripId, date', 'safe', 'on' => DefaultController::STEP_FIND],
			['profileStep, profiles', 'required', 'on' => DefaultController::STEP_PROFILE],
			['profileStep', 'in', 'range' => [1], 'on' => DefaultController::STEP_PROFILE],
			['reviewStep', 'required', 'on' => DefaultController::STEP_REVIEW],
			['reviewStep', 'in', 'range' => [1], 'on' => DefaultController::STEP_REVIEW],
		];
	}
}