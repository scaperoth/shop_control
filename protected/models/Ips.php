<?php

/**
 * This is the model class for table "ips".
 *
 * The followings are the available columns in table 'ips':
 * @property integer $ip_id
 * @property string $ip_address
 * @property string $ip_address2
 * @property string $ip_compname
 * @property integer $ip_loc_id
 */
class Ips extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Ips the static model class
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
		return 'ips';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ip_address, ip_loc_id', 'required'),
			array('ip_loc_id', 'numerical', 'integerOnly'=>true),
			array('ip_address, ip_address2, ip_compname', 'length', 'max'=>45),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ip_id, ip_address, ip_address2, ip_compname, ip_loc_id', 'safe', 'on'=>'search'),
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
			'ip_id' => 'Ip',
			'ip_address' => 'Ip Address',
			'ip_address2' => 'Ip Address2',
			'ip_compname' => 'Ip Compname',
			'ip_loc_id' => 'Ip Loc',
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

		$criteria->compare('ip_id',$this->ip_id);
		$criteria->compare('ip_address',$this->ip_address,true);
		$criteria->compare('ip_address2',$this->ip_address2,true);
		$criteria->compare('ip_compname',$this->ip_compname,true);
		$criteria->compare('ip_loc_id',$this->ip_loc_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}