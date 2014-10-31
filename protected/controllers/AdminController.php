<?php

class AdminController extends Controller
{
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha' => array(
				'class'     => 'CCaptchaAction',
				'backColor' => 0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'    => array(
				'class' => 'CViewAction',
			),
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow', // allow all users to perform 'index' and 'view' actions
				  'actions' => array('index'),
				  'users'   => array('admin'),
			),
			array('deny', // deny all users
				  'users' => array('*'),
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		$currentDate['year'] = isset($_POST['yearSelect']) && !empty($_POST['yearSelect']) ? $_POST['yearSelect'] : date('Y');
		$currentDate['month'] = isset($_POST['monthSelect']) && !empty($_POST['monthSelect']) ? $_POST['monthSelect'] : date('m');

		$tripsParam = array();
		$maxdate = date("t", strtotime($currentDate['year'] . "-" . $currentDate['month'] . "-01"));

		$criteria = new CDbCriteria();
		$criteria->select = 'id,startPoint,endPoint';
		$criteria->condition = 'status=1';
		$data = Directions::model()->findAll($criteria);
		$directions = array();
		foreach ($data as $d) {
			$directions[] = array(
				'direction' => $d['startPoint'] . ' - ' . $d['endPoint'],
				'id'        => $d['id'],
			);
		}

		echo '<pre>';
		for ($i = 1; $i <= $maxdate; $i++) {
			$criteria = new CDbCriteria();
			$criteria->select = 'id, idDirection, departure, idBus';
			$criteria->condition = 'status=1 and departure >= "' . $currentDate['year'] . '-' . $currentDate['month'] . '-' . ($i < 10 ? '0' . $i : $i) . ' 00:00:00" and';
			$criteria->condition .= ' departure <="' . $currentDate['year'] . '-' . $currentDate['month'] . '-' . ($i < 10 ? '0' . $i : $i) . ' 23:59:59"';
			$criteria->order = 'idDirection ASC';
			$data = Trips::model()->findAll($criteria);
			if(count($data) != 0) {
//				print_r($busParam);
			}
			if (count($data) == 2) {
				for ($j = 0; $j < 2; $j++) {
					$trCountCheck = Tickets::model()->count(
						array(
							'condition' => 'idTrip=' . $data[$j]->attributes['id'] . ' and status=2'
						)
					);
					$trCountAll = Tickets::model()->count(
						array(
							'condition' => 'idTrip=' . $data[$j]->attributes['id'] . ' and (status=2 or status=1)'
						)
					);
					if ($trCountCheck == $trCountAll && $trCountAll != 0) $trFull = 'full';
					else $trFull = 'notfull';
					$trip[$j] = array(
						'id'          => $data[$j]->attributes['id'],
						'idDirection' => $data[$j]->attributes['idDirection'],
						'full'        => $trFull
					);
				}
			} elseif (count($data) == 1) {
				if ($directions[0]['id'] == $data[0]->attributes['idDirection']) {
					$trCountCheck = Tickets::model()->count(
						array(
							'condition' => 'idTrip=' . $data[0]->attributes['id'] . ' and status=2'
						)
					);
					$trCountAll = Tickets::model()->count(
						array(
							'condition' => 'idTrip=' . $data[0]->attributes['id'] . ' and (status=2 or status=1)'
						)
					);
					if ($trCountCheck == $trCountAll && $trCountAll != 0) $trFull = 'full';
					else $trFull = 'notfull';
					$trip[0] = array(
						'id'          => $data[0]->attributes['id'],
						'idDirection' => $data[0]->attributes['idDirection'],
						'full'        => $trFull
					);
					$trip[1] = array(
						'id'          => 0,
						'idDirection' => $directions[1]['id'],
						'full'        => 'notfull',
					);
				} elseif ($directions[1]['id'] == $data[0]->attributes['idDirection']) {
					$trCountCheck = Tickets::model()->count(
						array(
							'condition' => 'idTrip=' . $data[0]->attributes['id'] . ' and status=2'
						)
					);
					$trCountAll = Tickets::model()->count(
						array(
							'condition' => 'idTrip=' . $data[0]->attributes['id'] . ' and (status=2 or status=1)'
						)
					);
					if ($trCountCheck == $trCountAll && $trCountAll != 0) $trFull = 'full';
					else $trFull = 'notfull';
					$trip[0] = array(
						'id'          => 0,
						'idDirection' => $directions[0]['id'],
						'full'        => 'notfull',
					);
					$trip[1] = array(
						'id'          => $data[0]->attributes['id'],
						'idDirection' => $data[0]->attributes['idDirection'],
						'full'        => $trFull
					);
				}
			} else {
				$trip[0] = array(
					'id'          => 0,
					'idDirection' => $directions[0]['id'],
					'full'        => 'notfull',
				);
				$trip[1] = array(
					'id'          => 0,
					'idDirection' => $directions[1]['id'],
					'full'        => 'notfull',
				);
			}
			$tripsParam[$i] = array(
				'date'  => $currentDate['year'] . '-' . $currentDate['month'] . '-' . ($i < 10 ? '0' . $i : $i),
				'trip1' => $trip[0],
				'trip2' => $trip[1],
			);
		}
//		print_r($tripsParam);

		echo '</pre>';

		$this->render('index', array(
			'tripsParam'  => $tripsParam,
			//			'dataProvider' => $dataProvider,
			//			'countTrips'   => $countTrips,
			'currentDate' => $currentDate,
			'directions'  => $directions,
		));
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public
	function actionError()
	{
		if ($error = Yii::app()->errorHandler->error) {
			if (Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Performs the AJAX validation.
	 *
	 * @param Trips $model the model to be validated
	 */
	protected
	function performAjaxValidation($model)
	{
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'trips-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}