<?php

/**
* This is the model base class for the table "group_category".
* DO NOT MODIFY THIS FILE! It is automatically generated by giix.
* If any changes are necessary, you must set or override the required
* property or method in class "GroupCategory".
*
* Columns in table "group_category" available as properties of the model,
* and there are no model relations.
*
* @property integer $id
* @property string $title
* @property integer $type
* @property integer $pid
* @property string $module
*
*/
abstract class BaseGroupCategory extends YsActiveRecord {

public static function model($className=__CLASS__) {
return parent::model($className);
}

public function tableName() {
return 'group_category';
}

public static function representingColumn() {
return 'title';
}

public function rules() {
return array(
array('title, module', 'required'),
array('type, pid', 'numerical', 'integerOnly'=>true),
array('title', 'length', 'max'=>255),
array('module', 'length', 'max'=>50),
array('type, pid', 'default', 'setOnEmpty' => true, 'value' => null),
array('id, title, type, pid, module', 'safe', 'on'=>'search'),
);
}

public function relations() {
return array(
);
}

public function pivotModels() {
return array(
);
}

public function attributeLabels() {
return array(
'id' => Yii::t('group_category', 'id'),
'title' => Yii::t('group_category', 'title'),
'type' => Yii::t('group_category', 'type'),
'pid' => Yii::t('group_category', 'pid'),
'module' => Yii::t('group_category', 'module'),
);
}

public function search() {
$criteria = new CDbCriteria;

$criteria->compare('id', $this->id);
$criteria->compare('title', $this->title, true);
$criteria->compare('type', $this->type);
$criteria->compare('pid', $this->pid);
$criteria->compare('module', $this->module, true);

return new CActiveDataProvider($this, array(
'criteria' => $criteria,
));
}
}