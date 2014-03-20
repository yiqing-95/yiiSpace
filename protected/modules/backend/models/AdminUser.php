<?php

Yii::import('backend.models._base.BaseAdminUser');

class AdminUser extends BaseAdminUser
{
    /**
     * 加密明文输入的密码
     * @param string $password 明文密码
     * @param string $encrypt
     * @return string 加密后
     */
    public static function hashPassword($password, $encrypt) {
        return md5(md5($password) . $encrypt);
    }

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}



    /**
     * 验证前
     * @return bool
     */
    public function beforeValidate() {
        if($this->getIsNewRecord()){
            $this->encrypt =  StringUtil::random(6);
        }
        return parent::beforeValidate();
    }


    protected $_oldPassword ;
    public function afterFind(){
      parent::afterFind();
        $this->_oldPassword = $this->password;
    }

    /**
     * 保存前
     * @return bool
     */
    public function beforeSave() {
        if (!parent::beforeSave())
            return FALSE;
        if ($this->isNewRecord)
            $this->password = self::hashPassword($this->password,$this->encrypt);
        else {
            if($this->password != $this->_oldPassword) {
                $this->encrypt = StringUtil::random(6);
                $this->password = self::hashPassword($this->password,$this->encrypt);
            }
        }
        return TRUE;
    }


    public function rules()
    {

        return array(
            array('username, password, name,  role_id', 'required'),
            array('username', 'unique'),
            array('disabled', 'numerical', 'integerOnly' => true),
            array('username, name', 'length', 'max' => 50),
            array('password', 'length', 'max' => 40),
            array('encrypt', 'length', 'max' => 6),
            array('role_id, create_time, update_time', 'length', 'max' => 10),
            array('setting', 'safe'),
            array('disabled, setting', 'default', 'setOnEmpty' => true, 'value' => null),
            array('id, username, password, name, encrypt, role_id, roleName, disabled, setting, create_time, update_time', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @var string
     */
    public $roleName = '';

    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('username', $this->username, true);
        $criteria->compare('password', $this->password, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('encrypt', $this->encrypt, true);
        $criteria->compare('role_id', $this->role_id);
        $criteria->compare('disabled', $this->disabled);
        $criteria->compare('setting', $this->setting, true);
        $criteria->compare('create_time', $this->create_time, true);
        $criteria->compare('update_time', $this->update_time, true);

        $criteria->with = array('role');
        $criteria->compare( 'role.name', $this->roleName, true );

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}