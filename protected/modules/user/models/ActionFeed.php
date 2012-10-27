<?php

Yii::import('user.models._base.BaseActionFeed');

class ActionFeed extends BaseActionFeed
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
       return array(
         'actor'=>array(self::BELONGS_TO,'User','uid')
       );
    }
}