<?php

Yii::import('group.models._base.BaseGroupMember');

class GroupMember extends BaseGroupMember
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return array 模型关系规则.
     */
    public function relations() {
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
            'group' => array(self::BELONGS_TO, 'Group', 'group_id'),
        );
    }

    /**
     * Get membership information by user and group
     * @param int $user
     * @param int $group
     * @return void
     */
    static public function getByUserAndGroup($user, $group)
    {
        return GroupMember::model()->findByAttributes(array(
            'group' => $group,
            'user' => $user
        ));
    }


    }