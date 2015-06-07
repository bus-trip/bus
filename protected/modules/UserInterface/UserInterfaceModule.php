<?php

/**
 * Part of bus 2015
 * Created by: Александр on 16.04.2015:22:39
 */
class UserInterfaceModule extends CWebModule
{
	public function init()
	{
		parent::init();
		$this->controllerNamespace = 'UserInterface\controllers';
	}
}