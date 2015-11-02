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
			if ($model->save()) {
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

	public function actionRecover()
	{
		$this->pageTitle = 'Восстановление пароля';
		$userRecover     = new UserRecover();
		if ($attributes = Yii::app()->getRequest()->getPost(CHtml::modelName($userRecover))) {
			$userRecover->setAttributes($attributes);
			if ($userRecover->validate()) {

				$body = $this->renderPartial('application.views.mail.recover', array(
					'user' => $userRecover->user->login,
					'link' => $this->createAbsoluteUrl('user/new_password', array('id' => $userRecover->user->id, 't' => md5('superman' . $userRecover->user->pass))),
				), true);

				if ($userRecover->user->mail) {
					$this->mail($userRecover->user->mail, 'Восстановление пароля', $body);
					Yii::app()->user->setFlash('success', 'Инструкции по восстановлению пароля направлены на эл.адрес <b>' . $userRecover->user->mail . '</b>');
				}
			}
		}

		$this->render('recover', ['model' => $userRecover]);
	}

	public function mail($to, $title, $body)
	{
		$mail = new YiiMailer();
		$mail->setView('common');
		$mail->setFrom(Yii::app()->params['paramName']['siteEmail'], Yii::app()->name);
		$mail->setData(array('title' => $title, 'message' => $body));
		$mail->setTo($to);
		$mail->setSubject($title);

		if (!$mail->send()) {
			//error log
		}
	}

	public function actionNew_password($id, $t)
	{
		$users = User::model()->findAllByPk($id);
		if (empty($users))
			throw new CHttpException(404, 'Страница не найдена');

		$user = $users[0];

		if (md5('superman' . $user->pass) != $t)
			throw new CHttpException(500, 'Неправильная ссылка');

		$new_pass   = substr(md5(rand()), 0, 7);
		$user->pass = md5('spyderman2' . $new_pass);
		$user->save(false);

		$identity = new UserIdentity($user->login, $new_pass);
		if ($identity->authenticate())
			Yii::app()->user->login($identity);

		return $this->redirect($this->createUrl('/account'));
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
				  'actions' => array('login', 'register', 'recover'),
				  'users'   => array('@'),
			),
			array('deny',
				  'actions' => array('logout'),
				  'users'   => array('?'),
			),
		);
	}
}