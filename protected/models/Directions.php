<?php

/**
 * This is the model class for table "directions".
 *
 * The followings are the available columns in table 'directions':
 * @property integer $id
 * @property integer $parentId
 * @property string $startPoint
 * @property string $endPoint
 * @property double $price
 *
 * The followings are the available model relations:
 * @property Tickets[] $tickets
 * @property Trips[] $trips
 */
class Directions extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'directions';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('parentId, startPoint, endPoint, price', 'required'),
			array('parentId', 'numerical', 'integerOnly'=>true),
			array('price', 'numerical'),
			array('startPoint, endPoint', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, parentId, startPoint, endPoint, price', 'safe', 'on'=>'search'),
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
			'tickets' => array(self::HAS_MANY, 'Tickets', 'idDirection'),
			'trips' => array(self::HAS_MANY, 'Trips', 'idDirection'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'parentId' => 'Parent',
			'startPoint' => 'Start Point',
			'endPoint' => 'End Point',
			'price' => 'Price',
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
		$criteria->compare('parentId',$this->parentId);
		$criteria->compare('startPoint',$this->startPoint,true);
		$criteria->compare('endPoint',$this->endPoint,true);
		$criteria->compare('price',$this->price);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Directions the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
