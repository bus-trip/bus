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
 * @property string  $doc_num
 * @property integer $doc_type
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
	const DOC_PASSPORT          = 1;
	const DOC_BIRTH_CERTIFICATE = 2;

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
			array('last_name, name, middle_name, doc_num, doc_type, phone, birth', 'required'),
			array('uid, tid, sex, birth, black_list, doc_type', 'numerical', 'integerOnly' => true),
			array('last_name, name, middle_name, black_desc', 'length', 'max' => 255),
			array('doc_num', 'length', 'max' => 64),
			array('phone', 'length', 'max' => 17),
			array('sex, black_list', 'length', 'max' => 1, 'min' => 1),
			array('birth', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, uid, tid, last_name, name, middle_name, doc_num, doc_type, phone, sex, birth, black_list, black_desc, created', 'safe', 'on' => 'search'),
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
			'doc_num'     => 'Номер документа',
			'doc_type'    => 'Тип документа',
			'phone'       => 'Телефон',
			'sex'         => 'Пол',
			'birth'       => 'Дата рождения',
			'black_list'  => 'Черный список',
			'black_desc'  => 'Причина',
			'created'     => 'Created',
		);
	}

	public function getAttributeLabel($attribute)
	{
		if ($attribute == 'doc_type') {
			switch ($this->doc_type) {
				case self::DOC_PASSPORT:
					return 'Паспорт';
				case self::DOC_BIRTH_CERTIFICATE:
					return 'Свидетельство о рождении';
				default:
					return null;
			}
		}

		return parent::getAttributeLabel($attribute);
	}

	public static function getDocType($id)
	{
		switch ($id) {
			case self::DOC_PASSPORT:
				return 'Паспорт';
			case self::DOC_BIRTH_CERTIFICATE:
				return 'Свидетельство о рождении';
			default:
				return '';
		}
	}

	public static function getBlackList($id){
		switch ($id) {
			case 1:
				return 'Да';
			default:
				return 'Нет';
		}
	}

	protected function beforeValidate()
	{
		switch ($this->sex) {
			case 'Мужской':
				$this->sex = 1;
				break;
			case 'Женский':
				$this->sex = 0;
				break;
		}
		return parent::beforeValidate();
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
		$criteria->compare('doc_num', $this->doc_num);
		$criteria->compare('doc_type', $this->doc_type);
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

		if (preg_match('#\.#', $this->birth)) {
			$this->birth = strtotime($this->birth);
		}

		return parent::validate($attributes, $clearErrors);
	}

	protected function beforeSave()
	{
		if (parent::beforeSave()) {
			if ($this->birth == '')
				$this->birth = null;

			$this->name      = mb_ucfirst($this->name);
			$this->last_name = mb_ucfirst($this->last_name);
			if ($this->middle_name)
				$this->middle_name = mb_ucfirst($this->middle_name);
		}

		return true;
	}

	public function afterSave()
	{
		// Turn it back into a unix timestamp in case you want to continue working with the record
		if ($this->birth)
			$this->birth = date('d.m.Y', $this->birth);

		// сохраняем значение черного листа всем профилям с одной фамилией и с одним номером паспорта
		$criteria            = new CDbCriteria;
		$criteria->condition = 'doc_type=:doc_type AND doc_num=:doc_num AND last_name=:last_name';
		$criteria->params    = array(':doc_type' => $this->doc_type, ':doc_num' => $this->doc_num, ':last_name' => $this->last_name);
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

	public function afterFind()
	{
		if ($this->birth)
			$this->birth = date('d.m.Y', $this->birth);

		if ($this->sex !== null) {
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
//		$name = $this->last_name . ' ' . mb_substr($this->name, 0, 1, 'UTF-8') . '.';
//		if ($this->middle_name) {
//			$name .= ' ' . mb_substr($this->middle_name, 0, 1, 'UTF-8') . '.';
//		}

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
		$criteria->compare('doc_num', $this->doc_num);
		$criteria->compare('doc_type', $this->doc_type);
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



