<?php

/**
* This is the model base class for the table "msg_inbox".
* DO NOT MODIFY THIS FILE! It is automatically generated by giix.
* If any changes are necessary, you must set or override the required
* property or method in class "MsgInbox".
*
* Columns in table "msg_inbox" available as properties of the model,
* and there are no model relations.
*
* @property string $id
* @property integer $uid
* @property string $msg_id
* @property integer $read_time
* @property integer $delete_time
*
*/
abstract class BaseMsgInbox extends YsActiveRecord {

public static function model($className=__CLASS__) {
return parent::model($className);
}

public function tableName() {
return 'msg_inbox';
}

public static function representingColumn() {
return 'id';
}

public function rules() {
return array(
array('uid, msg_id', 'required'),
array('uid, read_time, delete_time', 'numerical', 'integerOnly'=>true),
array('msg_id', 'length', 'max'=>20),
array('read_time, delete_time', 'default', 'setOnEmpty' => true, 'value' => null),
array('id, uid, msg_id, read_time, delete_time', 'safe', 'on'=>'search'),
);
}

public function pivotModels() {
return array(
);
}

public function attributeLabels() {
return array(
'id' => Yii::t('msg_inbox', 'id'),
'uid' => Yii::t('msg_inbox', 'uid'),
'msg_id' => Yii::t('msg_inbox', 'msg_id'),
'read_time' => Yii::t('msg_inbox', 'read_time'),
'delete_time' => Yii::t('msg_inbox', 'delete_time'),
);
}

public function search() {
$criteria = new CDbCriteria;

$criteria->compare('id', $this->id, true);
$criteria->compare('uid', $this->uid);
$criteria->compare('msg_id', $this->msg_id, true);
$criteria->compare('read_time', $this->read_time);
$criteria->compare('delete_time', $this->delete_time);

return new CActiveDataProvider($this, array(
'criteria' => $criteria,
));
}
}