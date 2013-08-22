<?php

/**
 * This is the model class for table "Locations".
 *
 * The followings are the available columns in table 'Locations':
 * @property integer $loc_id
 * @property string $loc_name
 * @property integer $loc_status
 * @property string $loc_open_hrs
 * @property string $loc_closed_hrs
 * @property integer $loc_flag
 */
class Locations extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Locations the static model class
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
		return 'locations';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('loc_name', 'required'),
			array('loc_status, loc_flag', 'numerical', 'integerOnly'=>true),
			array('loc_open_hrs, loc_closed_hrs', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('loc_id, loc_name, loc_status, loc_open_hrs, loc_closed_hrs, loc_flag', 'safe', 'on'=>'search'),
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
			'loc_id' => 'Loc',
			'loc_name' => 'Loc Name',
			'loc_status' => 'Loc Status',
			'loc_open_hrs' => 'Loc Open Hrs',
			'loc_closed_hrs' => 'Loc Closed Hrs',
			'loc_flag' => 'Loc Flag',
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

		$criteria->compare('loc_id',$this->loc_id);
		$criteria->compare('loc_name',$this->loc_name,true);
		$criteria->compare('loc_status',$this->loc_status);
		$criteria->compare('loc_open_hrs',$this->loc_open_hrs,true);
		$criteria->compare('loc_closed_hrs',$this->loc_closed_hrs,true);
		$criteria->compare('loc_flag',$this->loc_flag);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
       
}