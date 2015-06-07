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
	public $tripId      = 409;
	public $profiles;
	public $profileStep = 1;

	public function rules()
	{
		return [
			['tripId', 'required', 'on' => DefaultController::STEP_FIND],
			['profileStep, profiles', 'required', 'on' => DefaultController::STEP_PROFILE],
			['profileStep', 'in', 'range' => [1], 'on' => DefaultController::STEP_PROFILE],
		];
	}
}