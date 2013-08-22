<?php

/**
 * This is the model class for table "location_change_tracking".
 *
 * The followings are the available columns in table 'location_change_tracking':
 * @property integer $lct_id
 * @property string $lct_location
 * @property string $lct_user
 * @property string $lct_action
 * @property integer $lct_on_time
 * @property string $lct_timestamp
 */
class LocationChangeTracking extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return LocationChangeTracking the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'location_change_tracking';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('lct_on_time', 'numerical', 'integerOnly'=>true),
			array('lct_location, lct_user, lct_action', 'length', 'max'=>45),
			array('lct_timestamp', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('lct_id, lct_location, lct_user, lct_action, lct_on_time, lct_timestamp', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'lct_id' => 'Lct',
			'lct_location' => 'Lct Location',
			'lct_user' => 'Lct User',
			'lct_action' => 'Lct Action',
			'lct_on_time' => 'Lct On Time',
			'lct_timestamp' => 'Lct Timestamp',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('lct_id',$this->lct_id);
		$criteria->compare('lct_location',$this->lct_location,true);
		$criteria->compare('lct_user',$this->lct_user,true);
		$criteria->compare('lct_action',$this->lct_action,true);
		$criteria->compare('lct_on_time',$this->lct_on_time);
		$criteria->compare('lct_timestamp',$this->lct_timestamp,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}