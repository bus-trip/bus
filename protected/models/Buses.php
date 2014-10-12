<?php

/**
 * This is the model class for table "buses".
 *
 * The followings are the available columns in table 'buses':
 * @property integer   $id
 * @property string    $model
 * @property string    $number
 * @property integer   $places
 * @property string    $description
 * @property integer   $status
 *
 * The followings are the available model relations:
 * @property Tickets[] $tickets
 * @property Trips[]   $trips
 */
class Buses extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'buses';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('model, number, places, status', 'required'),
			array('places, status', 'numerical', 'integerOnly' => TRUE),
			array('model', 'length', 'max' => 100),
			array('number', 'length', 'max' => 20),
			array('description', 'length', 'max' => '1000'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, model, number, places, description, status', 'safe', 'on' => 'search'),
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
			'tickets' => array(self::HAS_MANY, 'Tickets', 'idBus'),
			'trips'   => array(self::HAS_MANY, 'Trips', 'idBus'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'          => 'ID',
			'model'       => 'Model',
			'number'      => 'Number',
			'places'      => 'Places',
			'description' => 'Description',
			'status'      => 'Status',
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
		$criteria->compare('model', $this->model, TRUE);
		$criteria->compare('number', $this->number, TRUE);
		$criteria->compare('places', $this->places);
		$criteria->compare('description', $this->description, TRUE);
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
	 * @return Buses the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
}
