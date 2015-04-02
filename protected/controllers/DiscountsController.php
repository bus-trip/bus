<?php

class DiscountsController extends Controller
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
			'postOnly + delete', // we only allow deletion via POST request
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
			array('allow',  // allow all users to perform 'index' and 'view' actions
				  'actions' => array('index', 'view'),
				  'users'   => array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				  'actions' => array('create', 'update'),
				  'users'   => array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				  'actions' => array('admin', 'delete', 'showdiscount'),
				  'users'   => array('admin'),
			),
			array('deny',  // deny all users
				  'users' => array('*'),
			),
		);
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
		$model = new Discounts;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['Discounts'])) {
			$model->attributes = $_POST['Discounts'];
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

		if (isset($_POST['Discounts'])) {
			$model->attributes = $_POST['Discounts'];
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
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if (!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider = new CActiveDataProvider('Discounts');
		$this->render('index', array(
			'dataProvider' => $dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model = new Discounts('search');
		$model->unsetAttributes();  // clear any default values
		if (isset($_GET['Discounts']))
			$model->attributes = $_GET['Discounts'];

		$this->render('admin', array(
			'model' => $model,
		));
	}

	/**
	 * Устанавливает скидку для определённого билета.
	 *
	 * @param $profileId
	 * @return integer
	 */
	public function getDiscount($profileId)
	{
		$Profile = Profiles::model()->findByPk($profileId);
		$Ticket = Tickets::model()->findByPk($Profile->tid);
		if (!$Ticket->price) {
			$criteria = new CDbCriteria();
			$criteria->join = 'join trips as tr on t.id=tr.idDirection';
			$criteria->condition = 'tr.id=' . $Ticket->idTrip;
			$Direction = Directions::model()->find($criteria);
			$Ticket->price = $Direction->price;
		}

//		Детская скидка. Не учитывал скидку по местам и поездкам.
		$Discount = Discounts::model()->findByPk("AGE");
		$bdate1 = new DateTime(date("Y-m-d",$Profile->birth));
		$bdate2 = new DateTime(date("Y-m-d"));
		$bdiff = $bdate1->diff($bdate2);
		if($Discount && ($bdiff->y < 7)) {
			$Ticket->price = $Discount->amountType == 1 ? $Ticket->price * (1 - $Discount->amount / 100) : $Ticket->price - $Discount->amount;
			return $Ticket->price;
		}

//		Скидка по месту
		$Discount = Discounts::model()->findByPk("PLACE+" . $Ticket->place);
		if ($Discount) {
			$Ticket->price = $Discount->amountType == 1 ? $Ticket->price * (1 - $Discount->amount / 100) : $Ticket->price - $Discount->amount;
		}
//		Затем скидка по поездкам
		$Discount = Discounts::model()->findByPk("TRIPSCOUNT+3");
		if ($Discount) {
			$criteria = new CDbCriteria();
			$criteria->select = 't.id';
			$criteria->join = 'left join tickets as ti on t.tid=ti.id';
			$criteria->join .= ' left join trips as tr on tr.id=ti.idTrip';
			$criteria->condition = 'ti.id != ' . $Profile->tid . ' and tr.status=1 and ti.status=2 and t.passport="' . $Profile->passport . '" and t.last_name ="' . $Profile->last_name . '"';
			$profiles = Profiles::model()->count($criteria);
			$tmp = preg_split("/\+/", $Discount->id);
			if (($profiles + 1) % $tmp[1] == 0 && $profiles != 0) {
				$Ticket->price = $Discount->amountType == 0 ? $Ticket->price - $Discount->amount : $Ticket->price * (1 - $Discount->amount / 100);
			}
		}

		return $Ticket->price;
	}

	public function actionShowdiscount($profileId)
	{
		$Profile = Profiles::model()->findByPk($profileId);
		$Ticket = Tickets::model()->findByPk($Profile->tid);
		$discountType = array('PLACE', 'DATEDIR', 'AGE', 'TRIPSCOUNT');

		$Discount = Discounts::model()->findByPk("PLACE+" . $Ticket->place);
		if ($Discount) {
			$Ticket->price = $Discount->amountType == 1 ? $Ticket->price * (1 - $Discount->amount / 100) : $Ticket->price - $Discount->amount;
		}

		$Discount = Discounts::model()->findByPk("TRIPSCOUNT+3");
		if ($Discount) {
			$criteria = new CDbCriteria();
			$criteria->select = 't.id';
			$criteria->join = 'left join tickets as ti on t.tid=ti.id';
			$criteria->join .= ' left join trips as tr on tr.id=ti.idTrip';
			$criteria->condition = 'tr.arrival<"' . date('Y-m-d H:i:s') . '" and tr.status=1 and ti.status=2 and t.passport="' . $Profile->passport . '"';
			$profiles = Profiles::model()->count($criteria);
			$tmp = preg_split("/\+/", $Discount->id);
			if (($profiles + 1) % $tmp[1] == 0 && $profiles != 0) {
				$Ticket->price = $Discount->amountType == 0 ? $Ticket->price - $Discount->amount : $Ticket->price * (1 - $Discount->amount / 100);
			}
		}

//		print $Ticket->idTrip0->idDirection0->startPoint;

//		$discounts = Discounts::model()->findAll(array());
		/*
				foreach ($discountType as $dt) {

					$criteria->condition = 'id like "' . $dt . '%" and status=1';
					$discounts = Discounts::model()->findAll($criteria);
					if (count($discounts) != 0) {
						foreach ($discounts as $d) {
							$tmp = preg_split("/\+/", $d->id);
							switch ($tmp[0]) {
								case 'PLACE':
									if ($tmp[1] == $Ticket->place) {
										$Ticket->price = $d->amountType == 1 ? $Ticket->price * (1 - $d->amount / 100) : $Ticket->price - $d->amount;
									}
									break;
								case 'DATEDIR':
									break;
								case 'AGE':
									break;
								case 'TRIPSCOUNT':
									$criteria->select = 't.id';
									$criteria->join = 'left join tickets as ti on t.tid=ti.id';
									$criteria->join .= ' left join trips as tr on tr.id=ti.idTrip';
									$criteria->condition = 'tr.arrival<"' . date('Y-m-d H:i:s') . '" and tr.status=1 and ti.status=2 and t.passport="' . $Profile->passport . '"';
									$profiles = Profiles::model()->count($criteria);
									if (($profiles + 1) % $tmp[1] == 0 && $profiles != 0) $Ticket->price = $d->amountType == 0 ? $Ticket->price - $d->amount : $Ticket->price * (1 - $d->amount / 100);

									break;
							}
						}
					}
				}
		*/
		$this->render('showdiscount', array(
			'profile'   => $Profile->attributes,
			'ticket'    => $Ticket->attributes,
			'discounts' => $discounts,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 *
	 * @param integer $id the ID of the model to be loaded
	 *
	 * @return Discounts the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model = Discounts::model()->findByPk($id);
		if ($model === NULL)
			throw new CHttpException(404, 'The requested page does not exist.');

		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 *
	 * @param Discounts $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'discounts-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
