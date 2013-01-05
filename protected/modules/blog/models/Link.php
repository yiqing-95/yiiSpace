<?php

/**
 * This is the model class for table "dlf_link".
 *
 * The followings are the available columns in table 'dlf_link':
 * @property string $id
 * @property string $sitename
 * @property string $logo
 * @property string $siteurl
 * @property string $description
 * @property string $target
 * @property string $status
 * @property string $position
 * @property string $created
 * @property string $updated
 */
class Link extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Link the static model class
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
		return BlogModule::getDbTablePrefix().'link';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sitename, siteurl, status', 'required'),
			array('sitename, logo', 'length', 'max'=>128),
			array('siteurl, description', 'length', 'max'=>255),
			array('target', 'length', 'max'=>7),
			array('status, position, created, updated', 'length', 'max'=>11),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, sitename, logo, siteurl, description, target, status, position, created, updated', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'sitename' => 'Sitename',
			'logo' => 'Logo',
			'siteurl' => 'Siteurl',
			'description' => 'Description',
			'target' => 'Target',
			'status' => 'Status',
			'position' => 'Position',
			'created' => 'Created',
			'updated' => 'Updated',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('sitename',$this->sitename,true);
		$criteria->compare('logo',$this->logo,true);
		$criteria->compare('siteurl',$this->siteurl,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('target',$this->target,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('position',$this->position,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('updated',$this->updated,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}