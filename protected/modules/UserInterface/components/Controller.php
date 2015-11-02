<?php
/**
 * Part of bus 2015
 * Created by: Александр on 16.04.2015:22:41
 */

namespace UserInterface\components;

use Controller as MainController;
use CMenu;
use Yii;

class Controller extends MainController
{
	/**
	 * @var object The menu.
	 */
	private $_menu;

	/**
	 * @return CMenu
	 */
	public function getMenu()
	{
		/** @var \WizardBehavior $this */
		if (null === $this->getOwner()->_menu) {
			$properties = $this->menuProperties;
			unset($properties['previousItemCssClass']);
			$this->getOwner()->_menu = $this->getOwner()->createWidget('zii.widgets.CMenu', $properties);
		}
		$this->getOwner()->generateMenuItems();
		return $this->getOwner()->_menu;
	}

	private function generateMenuItems()
	{
		/** @var \WizardBehavior $this */
		$previous = true;
		$items    = [];
		$url      = [$this->getOwner()->id . '/' . $this->getOwner()->getAction()->getId()];

		foreach ($this->steps as $id => $step) {
			if ($step == 'completed' || $step == 'test_completed') continue;

			$item          = [];
			$item['step']  = $step;
			$item['label'] = $this->getOwner()->getWizardTitle($step);
			$CurrentStep   = $this->getCurrentStep() - 1;
			if (($previous && !$this->forwardOnly) || ($id === $CurrentStep) || $this->isValidStep($step)) {
				$item['url']  = $url + [$this->queryParam => $step];
				$item['data'] = $this->read($step);
				if ($id === $CurrentStep) {
					$previous = false;
				}
			}
			$item['active'] = $id === $CurrentStep;
			if ($previous && !empty($this->menuProperties['previousItemCssClass']))
				$item['itemOptions'] = ['class' => $this->menuProperties['previousItemCssClass']];

			$items[] = $item;
		}
		if (!empty($this->menuLastItem))
			$items[] = [
				'label'  => $this->menuLastItem,
				'active' => false
			];

		$this->getOwner()->_menu->items = $items;
	}

	public function init()
	{
		parent::init();

		if (Yii::app()->user->isGuest)
			$this->redirect($this->createUrl('/user/login'));
	}
}