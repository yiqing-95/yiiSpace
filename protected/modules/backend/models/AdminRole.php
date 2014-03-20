<?php

Yii::import('backend.models._base.BaseAdminRole');

class AdminRole extends BaseAdminRole
{
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

    /**
     * @return array 模型关系规则.
     */
    public function relations() {
        return array(
            'menus' => array(self::MANY_MANY, 'AdminMenu', '{{admin_role_priv}}(role_id, menu_id)','order'=>'menus.lft'),
        );
    }

    public function getMenuIds(){
        $eq = EasyQuery::instance(AdminRolePriv::model()->tableName());
        return $eq->queryColumn('menu_id',array('condition'=>'role_id=:roleId','params'=>array(':roleId'=>$this->id)));
    }

    /**
     * @var
     * 每一个角色对应于一个菜单树
     * 该树是整个菜单的子集
     * 更新时候 可以对比下原始值跟修改的值是否一样
     * 如果一样 那么可以不做处理
     * afterFind 后就可以存储原始值了
     */
    public $purviews ;

    public function afterSave() {
        parent::afterSave();
        $purviews = explode(',', $this->purviews);
        AdminRolePriv::model()->deleteAll('role_id=:id', array('id' => $this->id));
        foreach ($purviews as $menuId) {
            Yii::app()->getDb()->createCommand()->insert(AdminRolePriv::model()->tableName(), array('menu_id' => $menuId, 'role_id' => $this->id));
        }
       // AdminModule::getCache()->delete(self::MENU_CACHE_PREFIX.$this->id);
    }
    protected function afterDelete(){
        parent::afterDelete();
        // 等价于sql中的 级联删除约束！
        AdminRolePriv::model()->deleteAll('role_id=:id', array('id' => $this->id));
    }
}