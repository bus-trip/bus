<?php

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property integer $id
 * @property string $last_name
 * @property string $name
 * @property string $middle_name
 * @property integer $passport
 * @property string $mail
 * @property string $pass
 * @property integer $phone
 * @property string $birth
 * @property integer $sex
 */
class Users extends CActiveRecord
{
	public $pass2;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('last_name, name, passport, mail, pass, pass2, phone', 'required'),
			array('passport, phone, sex', 'numerical', 'integerOnly'=>true),
			array('last_name, name, middle_name, mail', 'length', 'max'=>255),
			array('mail', 'email'),
			array('birth', 'type', 'type' => 'date', 'message' => '{attribute}: is not a date!', 'dateFormat' => 'dd.mm.yyyy'),
			array('pass, pass2', 'length', 'max' => 32),
			array('pass2', 'compare', 'compareAttribute' => 'pass', 'message' => "Пароль не совпадает"),
			array('passport, phone', 'length', 'max' => 10, 'min' => 10),
			array('sex', 'length', 'max' => 1, 'min' => 1),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, last_name, name, middle_name, passport, mail, pass, phone, birth, sex', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'last_name' => 'Фамилия',
			'name' => 'Имя',
			'middle_name' => 'Отчество',
			'passport' => 'Серия и номер паспорта',
			'mail' => 'E-mail',
			'pass' => 'Пароль',
			'pass2' => 'Повторите пароль',
			'phone' => 'Телефон',
			'birth' => 'Дата рождения',
			'sex' => 'Пол',
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

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('middle_name',$this->middle_name,true);
		$criteria->compare('passport',$this->passport);
		$criteria->compare('mail',$this->mail,true);
		$criteria->compare('pass',$this->pass,true);
		$criteria->compare('phone',$this->phone);
		$criteria->compare('birth',$this->birth,true);
		$criteria->compare('sex',$this->sex);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Users the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	protected function beforeSave()
	{
		if (parent::beforeSave()) {
			if ($this->isNewRecord) {
				$this->pass = md5('spyderman2' . $this->pass);
			}

			$this->last_name = mb_ucfirst($this->last_name);
			$this->name = mb_ucfirst($this->name);
			$this->middle_name = mb_ucfirst($this->middle_name);

			if($this->birth){
				$date = explode('.', $this->birth);
				$this->birth = $date[2] . '-' . $date[1] . '-' . $date[0];
			}
		}

		return TRUE;
	}
}
