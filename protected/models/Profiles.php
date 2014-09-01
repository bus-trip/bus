<?php

/**
 * This is the model class for table "profiles".
 *
 * The followings are the available columns in table 'profiles':
 * @property integer $id
 * @property string $last_name
 * @property string $name
 * @property string $middle_name
 * @property integer $passport
 * @property integer $phone
 * @property integer $sex
 * @property string $birth
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
			array('passport, phone, sex', 'numerical', 'integerOnly'=>true),
			array('last_name, name, middle_name', 'length', 'max'=>255),
			array('birth', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, last_name, name, middle_name, passport, phone, sex, birth', 'safe', 'on'=>'search'),
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
			'tickets' => array(self::HAS_MANY, 'Tickets', 'idProfile'),
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
			'phone' => 'Телефон',
			'sex' => 'Пол',
			'birth' => 'Дата рождения',
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
		$criteria->compare('phone',$this->phone);
		$criteria->compare('sex',$this->sex);
		$criteria->compare('birth',$this->birth,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Profiles the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
