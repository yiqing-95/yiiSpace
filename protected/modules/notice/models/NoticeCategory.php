<?php

Yii::import('notice.models._base.BaseNoticeCategory');

class NoticeCategory extends BaseNoticeCategory
{
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

    static public function getCategories4select(){

        return CHtml::listData(self::model()->findAllByAttributes(array(
            'enable'=>true
        )),'id','name');
    }
}