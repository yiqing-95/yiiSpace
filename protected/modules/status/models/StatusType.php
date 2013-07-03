<?php

Yii::import('status.models._base.BaseStatusType');

class StatusType extends BaseStatusType
{
public static function model($className=__CLASS__) {
return parent::model($className);
}
}