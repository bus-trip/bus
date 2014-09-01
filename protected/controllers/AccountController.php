<?php

class AccountController extends Controller
{
	public $content = '';
	public $user;

	public function init()
	{
		$user_id = Yii::app()->user->id;
		$this->user = User::model()->findByPk($user_id);
	}

	public function actionProfile()
	{

		$account = array(
			'values' => $this->user->attributes,
			'labels' => $this->user->attributeLabels()
		);
		unset($account['values']['id'], $account['values']['pass']);

		$this->pageTitle = 'Личный кабинет';
		$this->content = $this->renderPartial('profile', array('account' => $account), TRUE);
		$this->render('index', array('content' => $this->content, 'name' => $this->user->login));
	}

	public function actionEdit()
	{
		if (isset($_POST['User'])) {
			$model = $this->user;
			if (empty($_POST['User']['pass'])) {
				$model->pass2 = $model->pass;
				unset($_POST['User']['pass'], $_POST['User']['pass2']);
			}
			foreach ($_POST['User'] as $key => $value) {
				$model->{$key} = $value;
			}
			if ($model->validate() && $model->save()) {
				Yii::app()->user->setFlash('success', "Аккаунт изменен");

				$url = $this->createUrl('/account');
				$this->redirect($url);
			}
		}

		$this->pageTitle = 'Редактирование аккаунта';
		unset($this->user->pass);
		$this->content = $this->renderPartial('edit', array('model' => $this->user), TRUE);
		$this->render('index', array('content' => $this->content, 'name' => $this->user->login));
	}

	public function actionPassengers(){


		$this->pageTitle = 'Мои профили';
		$this->content = $this->renderPartial('passengers', array(), TRUE);
		$this->render('index', array('content' => $this->content, 'name' => $this->user->login));
	}

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
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
			array('allow', // allow authenticated users to access all actions
				  'users' => array('@'),
			),
			array('deny', // deny all users
				  'users' => array('*'),
			),
		);
	}
}