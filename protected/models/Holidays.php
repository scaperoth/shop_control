<?php

/**
 * This is the model class for table "Holidays".
 *
 * The followings are the available columns in table 'Holidays':
 * @property integer $hol_id
 * @property string $hol_name
 * @property string $hol_start_date
 * @property string $hol_end_date
 * @property string $hol_description
 */
class Holidays extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Holidays the static model class
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
		return 'Holidays';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('hol_name, hol_start_date, hol_end_date', 'required'),
			array('hol_name, hol_end_date, hol_description', 'length', 'max'=>45),
			array('hol_start_date', 'length', 'max'=>40),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('hol_id, hol_name, hol_start_date, hol_end_date, hol_description', 'safe', 'on'=>'search'),
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
			'hol_id' => 'Hol',
			'hol_name' => 'Hol Name',
			'hol_start_date' => 'Hol Start Date',
			'hol_end_date' => 'Hol End Date',
			'hol_description' => 'Hol Description',
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

		$criteria->compare('hol_id',$this->hol_id);
		$criteria->compare('hol_name',$this->hol_name,true);
		$criteria->compare('hol_start_date',$this->hol_start_date,true);
		$criteria->compare('hol_end_date',$this->hol_end_date,true);
		$criteria->compare('hol_description',$this->hol_description,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}