<?php
/**
 * Part of bus 2015
 * Created by: Александр on 16.04.2015:22:44
 */

namespace UserInterface\controllers;

use CException;
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
				return 'Поиск рейса';
				break;
			case self::STEP_PROFILE:
				return 'Заполнение профиля';
				break;
			case self::STEP_REVIEW:
				return 'Проверка';
				break;
			case self::STEP_PAYMENT:
				return 'Оплата билета';
				break;

			default:
				return '';
		}
	}

	const STEP_FIND    = 'find';
	const STEP_PAYMENT = 'payment';
	const STEP_PROFILE = 'profile';
	const STEP_REVIEW  = 'review';

	public function behaviors()
	{
		return [
			'wizard' => [
				'class'       => 'ext.Wizard.WizardBehavior',
				'steps'       => [self::STEP_FIND,
								  self::STEP_PROFILE,
								  self::STEP_REVIEW,
								  self::STEP_PAYMENT,
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
		$checkout = new Checkout($event->getStep());
		if ($attributes = Yii::app()->getRequest()->getPost(CHtml::modelName($checkout))) {
			$checkout->setAttributes($attributes);
			if ($checkout->validate()) {
				$event->handled = true;
			}
		}

		if (!$event->handled) {
			$this->render('wizard', ['event' => $event,
									 'model' => $checkout,
									 'back'  => $this->backButton(),
									 'saved' => $this->read()]);
		}
	}

	/**
	 * @param WizardEvent $event
	 */
	public function wizardFinished($event)
	{
		$event->sender->reset();
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
			$url = '/Start/default/index';
		}

		$output = $this->createUrl($url);

		return $output;
	}

	/**
	 * @throws CException
	 */
	public function actionComplete()
	{
		$this->render('complete');
	}
}