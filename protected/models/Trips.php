<?php

/**
 * This is the model class for table "trips".
 *
 * The followings are the available columns in table 'trips':
 * @property integer $id
 * @property integer $idDirection
 * @property integer $idBus
 * @property string $departure
 * @property string $arrival
 * @property string $description
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property Schedule[] $schedules
 * @property Tickets[] $tickets
 * @property Directions $idDirection0
 * @property Buses $idBus0
 */
class Trips extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'trips';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('idDirection, idBus, departure, arrival, status', 'required'),
			array('idDirection, idBus, status', 'numerical', 'integerOnly'=>true),
//			array('departure', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, idDirection, idBus, departure, arrival, description, status', 'safe', 'on'=>'search'),
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
			'schedules' => array(self::HAS_MANY, 'Schedule', 'idTrip'),
			'tickets' => array(self::HAS_MANY, 'Tickets', 'idTrip'),
			'idDirection0' => array(self::BELONGS_TO, 'Directions', 'idDirection'),
			'idBus0' => array(self::BELONGS_TO, 'Buses', 'idBus'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'idDirection' => 'Id направления',
			'idBus' => 'Id автобуса',
			'departure' => 'Время отправления',
            'arrival' => 'Время прибытия',
			'description' => 'Описание рейса',
            'status' => 'Статус рейса',
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
		$criteria->compare('idDirection',$this->idDirection);
		$criteria->compare('idBus',$this->idBus);
		$criteria->compare('departure',$this->departure,true);
        $criteria->compare('arrival',$this->arrival,true);
		$criteria->compare('description',$this->description,true);
        $criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Trips the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
