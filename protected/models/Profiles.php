<?php

/**
 * This is the model class for table "profiles".
 *
 * The followings are the available columns in table 'profiles':
 * @property integer $id
 * @property integer $uid
 * @property integer $tid
 * @property string  $last_name
 * @property string  $name
 * @property string  $middle_name
 * @property string  $passport
 * @property string  $phone
 * @property integer $sex
 * @property integer $birth
 * @property integer $black_list
 * @property string  $black_desc
 * @property string  $created
 *
 * The followings are the available model relations:
 * @property Tickets $t
 * @property User    $u
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
//			array('last_name, name, passport, phone', 'required'),
array('last_name, name, middle_name, passport, phone, birth', 'required'),
array('uid, tid, passport, sex, black_list', 'numerical', 'integerOnly' => true),
array('last_name, name, middle_name, black_desc', 'length', 'max' => 255),
array('passport', 'length', 'max' => 10, 'min' => 10),
array('phone', 'length', 'max' => 17),
array('sex, black_list', 'length', 'max' => 1, 'min' => 1),
array('birth', 'date', 'format' => 'dd.MM.yyyy'),
// The following rule is used by search().
// @todo Please remove those attributes that should not be searched.
array('id, uid, tid, last_name, name, middle_name, passport, phone, sex, birth, black_list, black_desc, created', 'safe', 'on' => 'search'),
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
			't' => array(self::BELONGS_TO, 'Tickets', 'tid'),
			'u' => array(self::BELONGS_TO, 'User', 'uid'),
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
			'tid'         => 'Tid',
			'last_name'   => 'Фамилия',
			'name'        => 'Имя',
			'middle_name' => 'Отчество',
			'passport'    => 'Серия и номер паспорта',
			'phone'       => 'Телефон',
			'sex'         => 'Пол',
			'birth'       => 'Дата рождения',
			'black_list'  => 'Черный список',
			'black_desc'  => 'Причина',
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
		$criteria->compare('tid', $this->tid);
		$criteria->compare('last_name', $this->last_name, true);
		$criteria->compare('name', $this->name, true);
		$criteria->compare('middle_name', $this->middle_name, true);
		$criteria->compare('passport', $this->passport);
		$criteria->compare('phone', $this->phone, true);
		$criteria->compare('sex', $this->sex);
		$criteria->compare('birth', $this->birth);
		$criteria->compare('black_list', $this->black_list);
		$criteria->compare('black_desc', $this->black_desc);
		$criteria->compare('created', $this->created, true);

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

	public function validate($attributes = null, $clearErrors = true)
	{
		if ($this->sex == 'none') {
			$this->sex = null;
		}

		return parent::validate($attributes, $clearErrors);
	}

	protected function beforeSave()
	{
		if (parent::beforeSave()) {
			if ($this->birth != '') {
				$this->birth = strtotime($this->birth);
			}

			$this->name      = mb_ucfirst($this->name);
			$this->last_name = mb_ucfirst($this->last_name);
			if ($this->middle_name)
				$this->middle_name = mb_ucfirst($this->middle_name);
		}

		return true;
	}

	public function afterSave()
	{
		// сохраняем значение черного листа всем профилям с одной фамилией и с одним номером паспорта
		$criteria            = new CDbCriteria;
		$criteria->condition = 'passport=:passport AND last_name=:last_name';
		$criteria->params    = array(':passport' => $this->passport, ':last_name' => $this->last_name);
		$Profiles            = Profiles::model()->findAll($criteria);
		if (!empty($Profiles)) {
			foreach ($Profiles as $profile) {
				if ($profile->black_list != $this->black_list) {
					$profile->black_list = $this->black_list;
					$profile->black_desc = $this->black_desc;
					$profile->save();
				}
			}
		}

		parent::afterSave();
	}

	public function shortName()
	{
		$name = $this->last_name . ' ' . $this->name;
		if ($this->middle_name) {
			$name .= ' ' . $this->middle_name;
		}

		return $name;
	}

	public function searchWithGroupBy($fields)
	{
		$criteria        = new CDbCriteria;
		$criteria->group = implode(', ', $fields);

		$criteria->compare('id', $this->id);
		$criteria->compare('uid', $this->uid);
		$criteria->compare('tid', $this->tid);
		$criteria->compare('last_name', $this->last_name, true);
		$criteria->compare('name', $this->name, true);
		$criteria->compare('middle_name', $this->middle_name, true);
		$criteria->compare('passport', $this->passport);
		$criteria->compare('phone', $this->phone, true);
		$criteria->compare('sex', $this->sex);
		$criteria->compare('birth', $this->birth);
		$criteria->compare('black_list', $this->black_list);
		$criteria->compare('black_desc', $this->black_desc);
		$criteria->compare('created', $this->created, true);

		return new CActiveDataProvider($this, array(
			'criteria'   => $criteria,
			'sort'       => array('defaultOrder' => 'last_name'),
			'pagination' => array('pageSize' => 50)
		));
	}
}



