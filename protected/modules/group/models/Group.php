<?php

Yii::import('group.models._base.BaseGroup');

class Group extends BaseGroup
{
    public static $types = array('public', 'private', 'private-member-invite',
        'private-self-invite');

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'group_owner' => array(self::BELONGS_TO, 'User', 'creator'),
            'topics' => array(self::HAS_MANY, 'Topic', 'group_id', 'order'=>'topics.id DESC'),
            'topicCount' => array(self::STAT, 'Topic', 'group_id', ),
        );
    }

    protected function beforeSave()
    {
        if(parent::beforeSave())
        {
            if($this->isNewRecord)
            {
                //$this->create_time=$this->update_time=time();
                //$this->author_id=Yii::app()->user->id;
            } else{
                //$this->update_time=time();
            }

            return true;
        }
        else
            return false;
    }

}