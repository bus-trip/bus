<?php

/**
 * This is the model class for table "passengers".
 *
 * The followings are the available columns in table 'passengers':
 * @property integer $id
 * @property string $name
 * @property string $birthdate
 * @property string $sex
 * @property string $passport
 * @property string $phone
 * @property string $email
 *
 * The followings are the available model relations:
 * @property Tickets[] $tickets
 */
class Passengers extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'passengers';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, birthdate, sex, passport, phone, email', 'required'),
			array('sex', 'length', 'max'=>3),
			array('name, email', 'length', 'max'=>255),
			array('passport, phone', 'length', 'max'=>15),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, birthdate, sex, passport, phone, email', 'safe', 'on'=>'search'),
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
			'tickets' => array(self::HAS_MANY, 'Tickets', 'idPassenger'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'birthdate' => 'Birthdate',
			'sex' => 'Sex',
			'passport' => 'Passport',
			'phone' => 'Phone',
			'email' => 'Email',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('birthdate',$this->birthdate,true);
		$criteria->compare('sex',$this->sex);
		$criteria->compare('passport',$this->passport,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('email',$this->email,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Passengers the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
