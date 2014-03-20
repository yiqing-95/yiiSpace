<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 13-1-27
 * Time: 下午4:01
 * To change this template use File | Settings | File Templates.
 * @see http://www.yiiframework.com/forum/index.php/topic/9819-nested-set/page__st__20
 */
Yii::import('ext.yiiext.behaviors.model.trees.NestedSetBehavior');
class YsNestedSetBehavior extends NestedSetBehavior
{
    /**
     * Communicate with id of its parent.
     * Id of parent will be stored in ->parent_id
     */
    public function withParent()
    {
        $owner=$this->getOwner();
        $db=$owner->getDbConnection();
        $criteria = $owner->getDbCriteria();

        $criteria->select .= ', `parent`.ID as `parent_id`';

        $select =
            ' SELECT * from ' . $db->quoteColumnName($owner->tableName()) .
                ' WHERE ' . $db->quoteColumnName($owner->tableName()) . '.ROOT = ' . $owner->root .
                ' ORDER BY ' . $db->quoteColumnName($owner->tableName()) . '.LFT DESC';

        $criteria->join .=
            'LEFT JOIN ('.$select.') `parent` ' .
                'ON (`parent`.LFT < `t`.LFT AND `parent`.RGT > `t`.RGT)';

        $criteria->group = '`t`.ID';

        return $owner;
    }

}
