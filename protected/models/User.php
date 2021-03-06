<?php

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property integer    $id
 * @property string     $login
 * @property string     $pass
 * @property string     $mail
 *
 * The followings are the available model relations:
 * @property Profiles[] $profiles
 */
class User extends CActiveRecord
{
	public $pass2;
	public $rememberMe;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('login, pass, pass2', 'required'),
			array('mail', 'email'),
			array('pass, pass2', 'length', 'max' => 32),
			array('pass2', 'compare', 'compareAttribute' => 'pass', 'message' => "Пароль не совпадает"),
			array('login, mail', 'length', 'max' => 255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, login, pass, mail', 'safe', 'on' => 'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'profiles' => array(self::HAS_MANY, 'Profiles', 'uid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'    => 'ID',
			'login' => 'Логин',
			'pass'  => 'Пароль',
			'pass2' => 'Повторите пароль',
			'mail'  => 'E-mail',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id);
		$criteria->compare('login', $this->login, TRUE);
		$criteria->compare('pass', $this->pass, TRUE);
		$criteria->compare('mail', $this->mail, TRUE);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 *
	 * @param string $className active record class name.
	 *
	 * @return User the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	protected function beforeSave()
	{
		if (parent::beforeSave()) {
			if ($this->pass == $this->pass2) {
				$this->pass = md5('spyderman2' . $this->pass);
			}
		}

		return TRUE;
	}

	public function validate($attributes = NULL, $clearErrors = TRUE)
	{
		if ($this->isNewRecord) {
			$user_e = User::model()->find('LOWER(login)=?', array(strtolower($this->login)));
			if ($user_e !== NULL) {
				$this->addError('login', 'Логин занят');

				return FALSE;
			}
		}

		return parent::validate($attributes, $clearErrors);
	}
}
