<?php

class TicketsController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = '//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			//			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return [
			['allow',  // allow all users to perform 'index' and 'view' actions
			 'actions' => ['index', 'view'],
			 'users'   => ['*'],
			],
			['allow', // allow authenticated user to perform 'create' and 'update' actions
			 'actions' => ['create', 'update'],
			 'users'   => ['@'],
			],
			['allow', // allow admin user to perform 'admin' and 'delete' actions
			 'actions' => [
				 'admin',
				 'delete',
				 'profile',
				 'confirm',
				 'blacklist',
				 'unblacklist',
				 'passengers',
				 'searchticket',
				 'profileEdit'
			 ],
			 'users'   => ['admin'],
			],
			['deny',  // deny all users
			 'users' => ['*'],
			],
		];
	}

	/**
	 * Displays a particular model.
	 *
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view', array(
			'model' => $this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model = new Tickets;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['Tickets'])) {
			$model->attributes = $_POST['Tickets'];
			if ($model->save())
				$this->redirect(array('view', 'id' => $model->id));
		}

		$this->render('create', array(
			'model' => $model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 *
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['Tickets'])) {
			$model->attributes = $_POST['Tickets'];
			if ($model->save())
				$this->redirect(array('view', 'id' => $model->id));
		}

		$this->render('update', array(
			'model' => $model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 *
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if ($id == 0)
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));

		$model = $this->loadModel($id);

		$model->status = 0;

		if ($model->save())
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if (!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Confirmation a ticket.
	 * If confirmation is successful, the browser will be redirected to the 'admin' page.
	 *
	 * @param integer $id the ID of the model to be confirm
	 */
	public function actionConfirm($id)
	{
		$model = $this->loadModel($id);
		$model->status = 2;

		if ($model->save())
			$this->redirect(array('trips/sheet/' . $model->idTrip));
	}

	public function actionBlacklist($id, $profileid, $action)
	{
		$Profile = Profiles::model()->findByPk($profileid);
		if (!$Profile)
			throw new CHttpException(404, 'The requested page does not exist.');
		switch ($action) {
			case 'add':
				if (!empty($_POST)) {
					$Profile->black_list = 1;
					$Profile->black_desc = $_POST['Profiles']['black_desc'];
					if ($Profile->validate()) $Profile->save();
					$this->redirect(array('trips/sheet/' . $id));
				} else {
					$data = array('idTrip' => $id, 'Profile' => $Profile, 'action' => $action);
					$this->render('blconfirm', array('blParam' => $data));
				}
				break;
			case 'del':
				$Profile->black_list = 0;
				$Profile->black_desc = NULL;
				if ($Profile->validate()) $Profile->save();
				$this->redirect(array('trips/sheet/' . $id));
				break;
		}
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider = new CActiveDataProvider('Tickets');
		$this->render('index', array(
			'dataProvider' => $dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model = new Tickets('search');
		$model->unsetAttributes();  // clear any default values
		if (isset($_GET['Tickets']))
			$model->attributes = $_GET['Tickets'];

		$this->render('admin', array(
			'model' => $model,
		));
	}

	protected function getSameProfiles($profile)
	{
		$criteria = new CDbCriteria();
		$criteria->addCondition('last_name=:last_name');
		$criteria->addCondition('doc_type=:doc_type');
		$criteria->addCondition('doc_num=:doc_num');
//		$criteria->addCondition('t.tid IS NOT NULL');
		$criteria->params = array(
			':last_name' => $profile->last_name,
			':doc_type'  => $profile->doc_type,
			':doc_num'   => $profile->doc_num
		);

		return Profiles::model()->findAll($criteria);
	}

	public function actionProfile($id)
	{
		$profile = Profiles::model()->findByPk($id);
		if (!$profile)
			throw new CHttpException(404, 'The requested page does not exist.');

		$tickets = [];

		$sameProfiles = $this->getSameProfiles($profile);
		foreach ($sameProfiles as $itemProfile) {
			$criteria_tickets = new CDbCriteria();
			$criteria_tickets->condition = 't.id=:id';
			$criteria_tickets->params = array(':id' => $itemProfile->tid);
			$ticketObj = Tickets::model()->with(['idTrip0', 'idDirection0' => 'idTrip0'])
								->find($criteria_tickets);
			if ($ticketObj) {
				$directionObj = Directions::model()->findByPk($ticketObj->idDirection);
				$tickets[] = array(
					'id'           => $ticketObj->id,
					'address_from' => $ticketObj->address_from,
					'address_to'   => $ticketObj->address_to,
					'place'        => $ticketObj->place,
					'price'        => $ticketObj->price,
					'remark'       => $ticketObj->remark,
					'departure'    => $ticketObj->idTrip0->departure,
					'direction'    => $ticketObj->idTrip0->idDirection0->startPoint . '-' .
						$ticketObj->idTrip0->idDirection0->endPoint,
					'dir_part'     => $directionObj->startPoint . '-' . $directionObj->endPoint,
					'status'       => $ticketObj->status,
				);
			}
		}

		usort($tickets, '_sort_by_departure');
		$dataProvider = new CArrayDataProvider($tickets, array('pagination' => array('pageSize' => 50)));

		$this->pageTitle = 'Просмотр билетов пассажира';

		$this->breadcrumbs = array(
			'Рейсы' => array('trips/admin/status/actual'),
			$this->pageTitle
		);

		$this->render('profile', array(
			'model'        => $profile,
			'dataProvider' => $dataProvider,
			//			'tripId'       => isset($_POST) ? $_POST : '',
		));
	}

	public function actionProfileEdit($id)
	{
		$profile = Profiles::model()->findByPk($id);
		if (!$profile)
			throw new CHttpException(404, 'The requested page does not exist.');

		if ($attributes = Yii::app()->getRequest()->getPost(CHtml::modelName($profile))) {
			$sameProfiles = $this->getSameProfiles($profile);
			$profile->setAttributes($attributes);
			if ($profile->save()) {
				foreach ($sameProfiles as $itemProfile) {
					$itemProfile->setAttributes($attributes);
					if ($itemProfile->validate()) {
						$itemProfile->save();
					}
				}
				Yii::app()->user->setFlash('success', 'Профиль сохранен');
				$this->redirect(['tickets/profile/' . $id]);
			}
		}

		$this->pageTitle = 'Редактирование профиля пассажира';

		$this->breadcrumbs = [
			'Рейсы'                      => ['trips/admin/status/actual'],
			'Просмотр билетов пассажира' => ['tickets/profile/' . $id],
			$this->pageTitle
		];

		return $this->render('profile_edit', ['model' => $profile]);
	}

	function actionPassengers()
	{
		$this->layout = '//layouts/column1';
		$this->pageTitle = 'Все пассажиры';
		$this->breadcrumbs = array($this->pageTitle);

		$model = new Profiles('search');
		$model->unsetAttributes(); // clear any default values
		if (isset($_GET['Profiles']))
			$model->attributes = $_GET['Profiles'];

		$this->render('all_profiles', array('model' => $model));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 *
	 * @param integer $id the ID of the model to be loaded
	 *
	 * @return Tickets the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model = Tickets::model()->findByPk($id);
		if ($model === NULL)
			throw new CHttpException(404, 'The requested page does not exist.');

		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 *
	 * @param Tickets $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'tickets-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
