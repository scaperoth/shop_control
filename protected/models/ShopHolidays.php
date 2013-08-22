<?php

/**
 * This is the model class for table "Shop_Holidays".
 *
 * The followings are the available columns in table 'Shop_Holidays':
 * @property integer $hol_id
 * @property integer $loc_id
 */
class ShopHolidays extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ShopHolidays the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * 
     * returns array of holidays with loc_name, hol_name, hol_description, hol_date
     */
    public function get_holidays($id = '*') {
        $loc_holdidays = Yii::app()->db->createCommand()
                ->select('l.loc_name, h.hol_name, h.hol_description, h.hol_date')
                ->from('shop_control.shop_holidays sh, locations l, holidays h')
                ->where('l.loc_id = sh.loc_id and sh.hol_id = h.hol_id')
                ->queryAll();

        return $loc_holdidays;
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'shop_holidays';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('hol_id, loc_id', 'required'),
            array('hol_id, loc_id', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('hol_id, loc_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'hol_id' => 'Hol',
            'loc_id' => 'Loc',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('hol_id', $this->hol_id);
        $criteria->compare('loc_id', $this->loc_id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}