<?php

/**
 * This is the model class for table "location_hours_tracking".
 *
 * The followings are the available columns in table 'location_hours_tracking':
 * @property integer $lht_id
 * @property string $lht_username
 * @property string $lht_loc_name
 * @property string $lht_mon_open_hrs
 * @property string $lht_mon_closed_hrs
 * @property string $lht_tue_open_hrs
 * @property string $lht_tue_closed_hrs
 * @property string $lht_wed_open_hrs
 * @property string $lht_wed_closed_hrs
 * @property string $lht_thu_open_hrs
 * @property string $lht_thu_closed_hrs
 * @property string $lht_fri_open_hrs
 * @property string $lht_fri_closed_hrs
 * @property string $lht_sat_open_hrs
 * @property string $lht_sat_closed_hrs
 * @property string $lht_sun_open_hrs
 * @property string $lht_sun_closed_hrs
 * @property string $lht_timestamp
 */
class LocationHoursTracking extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return LocationHoursTracking the static model class
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
		return 'location_hours_tracking';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('lht_id, lht_username, lht_loc_name, lht_mon_open_hrs, lht_mon_closed_hrs, lht_tue_open_hrs, lht_tue_closed_hrs, lht_wed_open_hrs, lht_wed_closed_hrs, lht_thu_open_hrs, lht_thu_closed_hrs, lht_fri_open_hrs, lht_fri_closed_hrs, lht_sat_open_hrs, lht_sat_closed_hrs, lht_sun_open_hrs, lht_sun_closed_hrs, lht_timestamp', 'required'),
			array('lht_id', 'numerical', 'integerOnly'=>true),
			array('lht_username, lht_loc_name, lht_sat_closed_hrs', 'length', 'max'=>45),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('lht_id, lht_username, lht_loc_name, lht_mon_open_hrs, lht_mon_closed_hrs, lht_tue_open_hrs, lht_tue_closed_hrs, lht_wed_open_hrs, lht_wed_closed_hrs, lht_thu_open_hrs, lht_thu_closed_hrs, lht_fri_open_hrs, lht_fri_closed_hrs, lht_sat_open_hrs, lht_sat_closed_hrs, lht_sun_open_hrs, lht_sun_closed_hrs, lht_timestamp', 'safe', 'on'=>'search'),
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
			'lht_id' => 'Lht',
			'lht_username' => 'Lht Username',
			'lht_loc_name' => 'Lht Loc Name',
			'lht_mon_open_hrs' => 'Lht Mon Open Hrs',
			'lht_mon_closed_hrs' => 'Lht Mon Closed Hrs',
			'lht_tue_open_hrs' => 'Lht Tue Open Hrs',
			'lht_tue_closed_hrs' => 'Lht Tue Closed Hrs',
			'lht_wed_open_hrs' => 'Lht Wed Open Hrs',
			'lht_wed_closed_hrs' => 'Lht Wed Closed Hrs',
			'lht_thu_open_hrs' => 'Lht Thu Open Hrs',
			'lht_thu_closed_hrs' => 'Lht Thu Closed Hrs',
			'lht_fri_open_hrs' => 'Lht Fri Open Hrs',
			'lht_fri_closed_hrs' => 'Lht Fri Closed Hrs',
			'lht_sat_open_hrs' => 'Lht Sat Open Hrs',
			'lht_sat_closed_hrs' => 'Lht Sat Closed Hrs',
			'lht_sun_open_hrs' => 'Lht Sun Open Hrs',
			'lht_sun_closed_hrs' => 'Lht Sun Closed Hrs',
			'lht_timestamp' => 'Lht Timestamp',
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

		$criteria->compare('lht_id',$this->lht_id);
		$criteria->compare('lht_username',$this->lht_username,true);
		$criteria->compare('lht_loc_name',$this->lht_loc_name,true);
		$criteria->compare('lht_mon_open_hrs',$this->lht_mon_open_hrs,true);
		$criteria->compare('lht_mon_closed_hrs',$this->lht_mon_closed_hrs,true);
		$criteria->compare('lht_tue_open_hrs',$this->lht_tue_open_hrs,true);
		$criteria->compare('lht_tue_closed_hrs',$this->lht_tue_closed_hrs,true);
		$criteria->compare('lht_wed_open_hrs',$this->lht_wed_open_hrs,true);
		$criteria->compare('lht_wed_closed_hrs',$this->lht_wed_closed_hrs,true);
		$criteria->compare('lht_thu_open_hrs',$this->lht_thu_open_hrs,true);
		$criteria->compare('lht_thu_closed_hrs',$this->lht_thu_closed_hrs,true);
		$criteria->compare('lht_fri_open_hrs',$this->lht_fri_open_hrs,true);
		$criteria->compare('lht_fri_closed_hrs',$this->lht_fri_closed_hrs,true);
		$criteria->compare('lht_sat_open_hrs',$this->lht_sat_open_hrs,true);
		$criteria->compare('lht_sat_closed_hrs',$this->lht_sat_closed_hrs,true);
		$criteria->compare('lht_sun_open_hrs',$this->lht_sun_open_hrs,true);
		$criteria->compare('lht_sun_closed_hrs',$this->lht_sun_closed_hrs,true);
		$criteria->compare('lht_timestamp',$this->lht_timestamp,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}