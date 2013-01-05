<?php

/**
 * This is the model class for table "{{user_binding}}".
 *
 * The followings are the available columns in table BlogModule::getDbTablePrefix().'user_binding}}':
 * @property string $user_id
 * @property string $user_bind_type
 * @property string $user_access_token
 * @property string $user_openid
 * @property string $other_details
 */
class UserBinding extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UserBinding the static model class
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
		return BlogModule::getDbTablePrefix().'user_binding';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, user_bind_type', 'required'),
			array('user_id', 'length', 'max'=>11),
			array('user_bind_type', 'length', 'max'=>45),
			array('user_access_token, user_openid', 'length', 'max'=>255),
			array('other_details', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('user_id, user_bind_type, user_access_token, user_openid, other_details', 'safe', 'on'=>'search'),
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
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'user_id' => 'User',
			'user_bind_type' => 'User Bind Type',
			'user_access_token' => 'User Access Token',
			'user_openid' => 'User Openid',
			'other_details' => 'Other Details',
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

		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('user_bind_type',$this->user_bind_type,true);
		$criteria->compare('user_access_token',$this->user_access_token,true);
		$criteria->compare('user_openid',$this->user_openid,true);
		$criteria->compare('other_details',$this->other_details,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    
    public static function addBinding($userBingding,$salt)
	{
        
        $pssword = md5($salt.rand(5, 10));
		$model=new User;
        $user = array();
        $user['username'] = $userBingding['username'];
        $user['nickname'] = $userBingding['username'];
        $user['avatar'] = $userBingding['avatar'];
        $user['password'] = $pssword;
        $user['salt'] = $salt;
        $user['counts'] = 1;
        $user['created'] = time();
        $user['updated'] = time();
        $model->attributes=$user;
		
        if($model->save()){
            $user_id = $model->id;
            Yii::app()->user->id = $user_id;
            Yii::app()->user->name = $userBingding['username'];
            $bind = array();
            $bind['user_id'] = $user_id;
            $bind['user_bind_type'] = $userBingding['bind_type'];
            $bind['user_access_token'] = $userBingding['access_token'];
            $bind['user_openid'] = $userBingding['openid'];
            $BindModel = new UserBinding;
            $BindModel->attributes=$bind;
            return $BindModel->save();
        }
		
	}
}