<?php

/**
 * This is the model class for table "user_glean".
 *
 * The followings are the available columns in table 'user_glean':
 * @property integer $id
 * @property integer $user_id
 * @property string $object_type
 * @property integer $object_id
 * @property integer $ctime
 */
class UserGlean extends YsActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user_glean';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, object_type, object_id, ctime', 'required'),
			array('user_id, object_id, ctime', 'numerical', 'integerOnly'=>true),
			array('object_type', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, object_type, object_id, ctime', 'safe', 'on'=>'search'),
		);
	}

    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'user'        => array(self::BELONGS_TO, 'User', 'owner'),
            'blog'        => array(self::BELONGS_TO, 'Post', 'object_id',
                'condition' => 'object_type = :object_type ',
                'params' => array(':object_type' =>'blog')
            ),
        );
    }
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			'object_type' => 'Object Type',
			'object_id' => 'Object',
			'ctime' => 'Ctime',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('object_type',$this->object_type,true);
		$criteria->compare('object_id',$this->object_id);
		$criteria->compare('ctime',$this->ctime);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserGlean the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
