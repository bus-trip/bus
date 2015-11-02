<?php

/**
 * Part of bus 2015
 * Created by: Alexander Sumarokov on 02.11.2015:23:47
 */
class UserRecover extends CFormModel
{
	public $login;
	/** @var User */
	public $user;

	public function attributeLabels()
	{
		return [
			'login' => 'Логин или email',
		];
	}

	public function rules()
	{
		return [
			['login', 'required'],
			['login', 'userIsset']
		];
	}

	/**
	 * @param $attribute
	 */
	public function userIsset($attribute)
	{
		$criteria = new CDbCriteria();
		$criteria->addCondition('login=:login', 'OR');
		$criteria->addCondition('mail=:mail', 'OR');
		$criteria->params = array(
			':login' => $this->$attribute,
			':mail'  => $this->$attribute,
		);
		$this->user       = User::model()->find($criteria);

		if (!$this->user)
			$this->addError($attribute, 'Пользователя с введенным логином или email не существует');
	}
}