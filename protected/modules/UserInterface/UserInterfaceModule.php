<?php

/**
 * Part of bus 2015
 * Created by: Александр on 16.04.2015:22:39
 */
class UserInterfaceModule extends CWebModule
{
	protected $_assetsUrl;

	public function init()
	{
		parent::init();
		$this->controllerNamespace = 'UserInterface\controllers';
	}

	/**
	 * get assets public URL
	 *
	 * (perform publishing in process if necessary)
	 *
	 * @return string
	 */
	public function getAssetsUrl()
	{
		if ($this->_assetsUrl === null) {
			/* @var $am CAssetManager */
			$am               = Yii::app()->assetManager;
			$this->_assetsUrl = $am->publish(Yii::getPathOfAlias($this->getId() . '.assets'));
		}
		return $this->_assetsUrl;
	}
}