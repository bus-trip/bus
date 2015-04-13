<?php

class UserController extends Controller
{
	protected $username;
	protected $password;
	protected $rememberMe;

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model = new LoginForm();
		if ($attributes = Yii::app()->getRequest()->getPost(CHtml::modelName($model))) {
			$model->setAttributes($attributes);
			// validate user input and redirect to the previous page if valid
			if ($model->validate() && $model->login()) {
				$url = $this->createUrl('/account');
				$this->redirect($url);
			}
		}
		// display the login form
		$this->render('login', array('model' => $model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

	/**
	 * Displays the registration page
	 */
	public function actionRegister()
	{
		$model = new User();
		if ($attributes = Yii::app()->getRequest()->getPost(CHtml::modelName($model))) {
			$model->setAttributes($attributes);
			if ($model->validate() && $model->save()) {
				$this->username   = $model->login;
				$this->password   = $attributes['pass'];
				$this->rememberMe = $model->rememberMe;
				if ($this->login()) {
					$url = $this->createUrl('/account');
					$this->redirect($url);
				}

				return;
			}
		}

		$this->render('register', array('model' => $model));
	}

	protected function login()
	{
		$identity = new UserIdentity($this->username, $this->password);
		$identity->authenticate();
		if ($identity->errorCode === UserIdentity::ERROR_NONE) {
			$duration = $this->rememberMe ? 3600 * 24 * 30 : 0; // 30 days
			Yii::app()->user->login($identity, $duration);

			return true;
		} else
			return false;
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
			array('deny',
				  'actions' => array('login', 'register'),
				  'users'   => array('@'),
			),
			array('deny',
				  'actions' => array('logout'),
				  'users'   => array('?'),
			),
		);
	}
}