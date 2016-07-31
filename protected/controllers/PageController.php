<?php

/**
 * Created by PhpStorm.
 * User: mac
 * Date: 31.07.16
 * Time: 14:10
 */
class PageController extends Controller
{
	public function actionAbout()
	{

		$this->render('about');
	}

	public function actionBusSchedule()
	{

		$this->render('bus_schedule');
	}

	public function actionContacts()
	{

		$this->render('contacts');
	}

	public function actionActions()
	{

		$this->render('actions');
	}
}