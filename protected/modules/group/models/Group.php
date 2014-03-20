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
                $this->created =  time();
            } else{
                //$this->update_time=time();
            }

            return true;
        }
        else
            return false;
    }


    //----------------------------------------------------\\
    /**
     * 获取用户的分组
     *
     * 一个问题有多种方案，某用户的分组涉及三类：
     * 我创建的；我参与的； 全部分组（含上面两种情况）
     *
     * 用表链接或者单表多次查询都可以解决：
     * 全部分组： 查询group表中创建者是uid的分组 或者+group_id 在group_member 表中
     *      uid 存在且通过的小组ids
     * 我创建的:  查询group表中创建者id 是uid的小组
     * 我参与的   查询group_member 表中存在uid且通过 内联group表on group.id = group_member.group_id 的分组
     *
     * 不用表连接的解决方案：
     * 先单表搞到group_ids  然后用ids来获取group
     *
     * @param int $userId
     * @return CActiveDataProvider
     */
    static  public function getUserGroupsDataProvider($userId=0){
        $criteria = new CDbCriteria();
        $criteria->addColumnCondition(array(
            'creator_id'=>$userId,
        ),'AND','OR');

        $criteria->addCondition(' t.id IN (SELECT m.group_id FROM group_member m WHERE m.user_id=:userId
                              and m.approved=1) ','OR');

        $criteria->params = $criteria->params + array(
            ':userId'=>$userId,
        );
        return new CActiveDataProvider('Group',array(
            'criteria'=>$criteria ,
        ));
    }

    //----------------------------------------------------//
}