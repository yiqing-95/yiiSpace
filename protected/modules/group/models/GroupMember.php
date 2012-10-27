<?php

Yii::import('group.models._base.BaseGroupMember');

class GroupMember extends BaseGroupMember
{
public static function model($className=__CLASS__) {
return parent::model($className);
}

    /**
     * Get membership information by user and group
     * @param int $user
     * @param int $group
     * @return void
     */
 static    public function getByUserAndGroup( $user, $group )
    {
     return GroupMember::model()->findByAttributes(array(
        'group'=>$group,
         'user'=>$user
     ));
    }
}