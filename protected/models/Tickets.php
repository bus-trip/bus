<?php

/**
 * This is the model class for table "tickets".
 *
 * The followings are the available columns in table 'tickets':
 * @property integer    $id
 * @property integer    $idTrip
 * @property integer    $place
 * @property integer    $price
 * @property string     $address_from
 * @property string     $address_to
 * @property string     $remark
 * @property integer    $status
 *
 * The followings are the available model relations:
 * @property Profiles[] $profiles
 * @property Trips      $idTrip0
 */
class Tickets extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tickets';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('idTrip', 'required'),
			array('idTrip, place, price, status', 'numerical', 'integerOnly' => TRUE),
			array('address_from, address_to, remark', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, idTrip, place, price, address_from, address_to, remark, status', 'safe', 'on' => 'search'),
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
			'profiles' => array(self::HAS_MANY, 'Profiles', 'tid'),
			'idTrip0'  => array(self::BELONGS_TO, 'Trips', 'idTrip'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'           => 'ID',
			'idTrip'       => 'Id Trip',
			'place'        => 'Место',
			'price'        => 'Цена билета',
			'address_from' => 'Адрес от',
			'address_to'   => 'Адрес до',
			'remark'       => 'Примечание',
			'status'       => 'Статус',
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
		$criteria->compare('idTrip', $this->idTrip);
		$criteria->compare('place', $this->place);
		$criteria->compare('price', $this->price);
		$criteria->compare('address_from', $this->address_from, TRUE);
		$criteria->compare('address_to', $this->address_to, TRUE);
		$criteria->compare('remark', $this->remark, TRUE);
		$criteria->compare('status', $this->status);

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
	 * @return Tickets the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getStatuses()
	{
		return self::statuses();
	}

	public static function statuses()
	{
		return array(
			TICKET_CANCELED  => 'Отменен',
			TICKET_RESERVED  => 'Забронирован',
			TICKET_CONFIRMED => 'Подтвержден',
		);
	}
}
