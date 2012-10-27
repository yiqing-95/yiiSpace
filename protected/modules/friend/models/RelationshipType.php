<?php

Yii::import('friend.models._base.BaseRelationshipType');

class RelationshipType extends BaseRelationshipType
{
public static function model($className=__CLASS__) {
return parent::model($className);
}
}