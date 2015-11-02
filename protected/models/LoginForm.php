<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginForm extends CFormModel
{
	public $username;
	public $password;
//	public  $rememberMe = 15 * 60; // 15 min
	private $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('username, password', 'required'),
			// rememberMe needs to be a boolean
			//			array('rememberMe', 'boolean'),
			// password needs to be authenticated
			array('password', 'authenticate'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'rememberMe' => 'Запомнить',
			'username'   => 'Логин или email',
			'password'   => 'Пароль'
		);
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute, $params)
	{
		if (!$this->hasErrors()) {
			$criteria = new CDbCriteria();
			$criteria->addCondition('login=:login', 'OR');
			$criteria->addCondition('mail=:mail', 'OR');
			$criteria->params = array(
				':login' => $this->username,
				':mail'  => $this->username,
			);

			$user = User::model()->find($criteria);
			if ($user) {
				$this->_identity = new UserIdentity($user->login, $this->password);
				$this->_identity->authenticate();
				if (!$this->_identity->authenticate())
					$this->addError('password', 'Неверный пароль');
			} else {
				$this->addError('username', 'Неверные логин или email');
			}
		}
	}

	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login()
	{
		if ($this->_identity->errorCode === UserIdentity::ERROR_NONE) {
			//$duration = $this->rememberMe ? 3600 * 24 * 30 : 0; // 30 days
			$duration = 15 * 60; // 15 min
			Yii::app()->user->login($this->_identity, $duration);

			return true;
		} else
			return false;
	}
}
