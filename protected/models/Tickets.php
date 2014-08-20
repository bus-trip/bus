<?php

/**
 * This is the model class for table "tickets".
 *
 * The followings are the available columns in table 'tickets':
 * @property integer $id
 * @property integer $idPassenger
 * @property integer $idDirection
 * @property integer $idTrip
 * @property integer $idBus
 * @property integer $place
 *
 * The followings are the available model relations:
 * @property Buses $idBus0
 * @property Passengers $idPassenger0
 * @property Directions $idDirection0
 * @property Trips $idTrip0
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
			array('idPassenger, idDirection, idTrip, idBus, place', 'required'),
			array('idPassenger, idDirection, idTrip, idBus, place', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, idPassenger, idDirection, idTrip, idBus, place', 'safe', 'on'=>'search'),
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
			'idBus0' => array(self::BELONGS_TO, 'Buses', 'idBus'),
			'idPassenger0' => array(self::BELONGS_TO, 'Passengers', 'idPassenger'),
			'idDirection0' => array(self::BELONGS_TO, 'Directions', 'idDirection'),
			'idTrip0' => array(self::BELONGS_TO, 'Trips', 'idTrip'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'idPassenger' => 'Id Passenger',
			'idDirection' => 'Id Direction',
			'idTrip' => 'Id Trip',
			'idBus' => 'Id Bus',
			'place' => 'Place',
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
		$criteria->compare('idPassenger',$this->idPassenger);
		$criteria->compare('idDirection',$this->idDirection);
		$criteria->compare('idTrip',$this->idTrip);
		$criteria->compare('idBus',$this->idBus);
		$criteria->compare('place',$this->place);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Tickets the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
