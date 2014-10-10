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
		$currentDate['day'] = isset($_POST['day']) && !empty($_POST['day']) ? $_POST['day'] : date('d');

//        $query = "select * from trips as t left join schedule as s on s.idDirection = t.idDirection where s.departure>='".date($currentDate['year'].'-'.$currentDate['month'].'-01')." 00:00:00'";
        $query = "select
                    t.id,
                    t.idDirection,
                    t.idBus,
                    t.departure,
                    t.arrival
                   from trips as t
                   left join directions as d on d.id = t.idDirection
                   where t.status=1 and t.departure>='".date($currentDate['year'].'-'.$currentDate['month'].'-01')." 00:00:00'";

		$tripsData = Yii::app()->db->createCommand($query)->queryAll();
		$dataProvider = new CArrayDataProvider($tripsData);

		$this->render('index',array(
			'dataProvider'=>$dataProvider,
			'currentDate'=>$currentDate,
		));
		
		
		
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
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
	protected function performAjaxValidation($model)
	{
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'trips-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}