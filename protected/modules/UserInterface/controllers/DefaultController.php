<?php
/**
 * Part of bus 2015
 * Created by: Александр on 16.04.2015:22:44
 */

namespace UserInterface\controllers;

use UserInterface\components\Controller;

class DefaultController extends Controller
{
	public function actionIndex()
	{
		$this->render('index');
	}
}