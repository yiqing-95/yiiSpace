<?php

Yii::import('friend.models._base.BaseRelationshipCategory');

class RelationshipCategory extends BaseRelationshipCategory
{
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

    public static function CategoryList($uid = 0)
    {
        $category = self::model()->findAll(array(
            'select'=>'id,name',
            'condition'=>'user_id=:uid',
            'params'=>array(
                ':uid'=>$uid
            ),
        ));
        return CHtml::listData($category, 'id', 'name');
    }
}