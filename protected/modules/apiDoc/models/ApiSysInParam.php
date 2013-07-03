<?php

/**
 * This is the model class for table "api_sys_in_param".
 *
 * The followings are the available columns in table 'api_sys_in_param':
 * @property string $param_id
 * @property integer $api_id
 * @property string $name
 * @property string $type
 * @property integer $is_mandatory
 * @property string $description
 * @property string $category
 */
class ApiSysInParam extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'api_sys_in_param';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('api_id, name, type, description', 'required'),
			array('api_id, is_mandatory', 'numerical', 'integerOnly'=>true),
			array('name, type, category', 'length', 'max'=>120),
			array('description', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('param_id, api_id, name, type, is_mandatory, description, category', 'safe', 'on'=>'search'),
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
			'param_id' => 'Param',
			'api_id' => 'Api',
			'name' => 'Name',
			'type' => 'Type',
			'is_mandatory' => 'Is Mandatory',
			'description' => 'Description',
			'category' => 'Category',
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

		$criteria->compare('param_id',$this->param_id,true);
		$criteria->compare('api_id',$this->api_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('is_mandatory',$this->is_mandatory);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('category',$this->category,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * @return CDbConnection the database connection used for this class
	 */
	public function getDbConnection()
	{
		return Yii::app()->db4apiDoc;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ApiSysInParam the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
