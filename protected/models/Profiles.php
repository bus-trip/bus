<?php

/**
 * This is the model class for table "profiles".
 *
 * The followings are the available columns in table 'profiles':
 * @property integer   $id
 * @property integer   $uid
 * @property string    $last_name
 * @property string    $name
 * @property string    $middle_name
 * @property integer   $passport
 * @property integer   $phone
 * @property string    $address
 * @property integer   $sex
 * @property string    $birth
 *
 * The followings are the available model relations:
 * @property Tickets[] $tickets
 */
class Profiles extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'profiles';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('last_name, name, passport, phone', 'required'),
			array('uid, passport, sex', 'numerical', 'integerOnly' => TRUE),
			array('last_name, name, middle_name', 'length', 'max' => 255),
			array('birth', 'type', 'type' => 'date', 'message' => '{attribute}: is not a date!', 'dateFormat' => 'dd.mm.yyyy'),
			array('passport', 'length', 'max' => 10, 'min' => 10),
			array('phone', 'length', 'max' => 17),
			array('sex', 'length', 'max' => 1, 'min' => 1),
			array('birth', 'safe'),
			array('address', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, uid, last_name, name, middle_name, passport, phone, address, sex, birth, created', 'safe', 'on' => 'search'),
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
			'u'       => array(self::BELONGS_TO, 'User', 'uid'),
			'tickets' => array(self::HAS_MANY, 'Tickets', 'idProfile'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'          => 'ID',
			'uid'         => 'Uid',
			'last_name'   => 'Фамилия',
			'name'        => 'Имя',
			'middle_name' => 'Отчество',
			'passport'    => 'Серия и номер паспорта',
			'phone'       => 'Телефон',
			'address'     => 'Адрес',
			'sex'         => 'Пол',
			'birth'       => 'Дата рождения',
			'created'     => 'Created',
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
		$criteria->compare('uid', $this->uid);
		$criteria->compare('last_name', $this->last_name, TRUE);
		$criteria->compare('name', $this->name, TRUE);
		$criteria->compare('middle_name', $this->middle_name, TRUE);
		$criteria->compare('passport', $this->passport);
		$criteria->compare('phone', $this->phone);
		$criteria->compare('address', $this->address, TRUE);
		$criteria->compare('sex', $this->sex);
		$criteria->compare('birth', $this->birth, TRUE);
		$criteria->compare('created', $this->created, TRUE);

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
	 * @return Profiles the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function validate($attributes = NULL, $clearErrors = TRUE)
	{
		if ($this->sex == 'none') {
			$this->sex = NULL;
		}

		return parent::validate($attributes, $clearErrors);
	}

	protected function beforeSave()
	{
		if (parent::beforeSave()) {
			$this->birth = strtotime($this->birth);
			$this->name = mb_ucfirst($this->name);
			$this->last_name = mb_ucfirst($this->last_name);
			if ($this->middle_name)
				$this->middle_name = mb_ucfirst($this->middle_name);
		}

		return TRUE;
	}

	public function afterSave()
	{
		// Turn it back into a unix timestamp in case you want to continue working with the record
		$this->birth = date('d.m.Y', $this->birth);
		parent::afterSave();
	}

	public function afterFind()
	{
		$this->birth = date('d.m.Y', $this->birth);
		if ($this->sex !== NULL) {
			switch ($this->sex) {
				case 0:
					$this->sex = 'Мужской';
					break;
				case 1:
					$this->sex = 'Женский';
					break;
			}
		}

		return parent::afterFind();
	}

	public function shortName()
	{
		$name = $this->last_name . ' ' . mb_substr($this->name, 0, 1, 'UTF-8') . '.';
		if ($this->middle_name) {
			$name .= ' ' . mb_substr($this->middle_name, 0, 1, 'UTF-8') . '.';
		}

		return $name;
	}
}
