<?php


Yii::import('backend.models._base.BaseAdminMenu');
class AdminMenu extends BaseAdminMenu
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return array(
            // 'nestedInterval'=>'ext.nestedInterval.NestedIntervalBehavior',
            'nestedSet' => array(
                //'class' => 'ext.yiiext.behaviors.model.trees.NestedSetBehavior',
                'class' => 'application.components.YsNestedSetBehavior',
                'leftAttribute' => 'lft',
                'rightAttribute' => 'rgt',
                'levelAttribute' => 'level',
                'hasManyRoots' => true,
            )
        );
    }


    public function scopes()
    {
        return array();
    }

    public function calcUrl()
    {

        $url = '';
        if (!is_null($this->url)) {
            $newParams = array();
            if (!empty($this->params)) {
                parse_str($this->params, $newParams);
            }

            $newParams = array('menuId' => $this->id);
            //array_walk_recursive($this->params,array($this,'evalParam'));
            if (is_array($newParams)) {
                $url = controller()->createUrl($this->url, $newParams);
            } else {
                $url = controller()->createUrl($this->url);
            }
        }
        return $url;
    }

    /**
     * -----------------------------
     * 可以以json格式 动态传递参数 但 只可以双引号
     * 参考：
     * http://braincast.nl/samples/jsoneditor/
     * 竟然真的存在这样的编辑器
     * 必须双引号
     * -----------------------------
     * @param  $data
     * @return mixed
     */
    protected function parseParams($data)
    {
        $data = html_entity_decode($data);
        parse_str($data, $rtn);
        return $rtn;
    }

    /**
     * @param  $item
     * @return void
     * 尝试计算参数
     */
    protected function evalParam($item)
    {
        try {
            $newItem = $this->evaluateExpression($item);
            return $newItem;
        } catch (Exception $e) {
            //尝试计算而已
            return $item;
        }
    }

    public function beforeDelete()
    {
        if (!$this->isLeaf()) {
            //不是叶子 那么要递归删除孩子了
            $children = $this->children();
            foreach ($children as $child) {
                $child->deleteNode();
            }
        }
        return parent::beforeDelete();
    }

    //---------------------------------------------------------




    /**
     * @param array $collection
     * @return array
     */
    public static function  toHierarchy($collection = array())
    {
        // Trees mapped
        $trees = array();
        $l = 0;

        if (count($collection) > 0) {
            // Node Stack. Used to help building the hierarchy
            $stack = array();

            foreach ($collection as $node) {
                // 如果url 未提前转换 那么 转换为AR先
                $tmpAr = AdminMenu::model()->populateRecord($node);
                $node['_url'] = $tmpAr->calcUrl();
                unset($node['url']); // 不能用url 不然树点击后 跳转问题不好解决

                $item = $node;
                $item['children'] = array();

                // Number of stack items
                $l = count($stack);

                // Check if we're dealing with different levels
                while ($l > 0 && $stack[$l - 1]['level'] >= $item['level']) {
                    array_pop($stack);
                    $l--;
                }

                // Stack is empty (we are inspecting the root)
                if ($l == 0) {
                    // Assigning the root node
                    $i = count($trees);
                    $trees[$i] = $item;
                    $stack[] = & $trees[$i];
                } else {
                    // Add node to parent
                    $i = count($stack[$l - 1]['children']);
                    $stack[$l - 1]['children'][$i] = $item;
                    $stack[] = & $stack[$l - 1]['children'][$i];
                }
            }
        }

        return $trees;
    }


    public static function  toHierarchy2($collection = array())
    {
        // Trees mapped
        $trees = array();
        $l = 0;

        if (count($collection) > 0) {
            // Node Stack. Used to help building the hierarchy
            $stack = array();

            foreach ($collection as $node) {
                $item = $node;
                $item['children'] = array();

                // Number of stack items
                $l = count($stack);

                // Check if we're dealing with different levels
                while ($l > 0 && $stack[$l - 1]['level'] >= $item['level']) {
                    array_pop($stack);
                    $l--;
                }

                // Stack is empty (we are inspecting the root)
                if ($l == 0) {
                    // Assigning the root node
                    //$i = count($trees);
                    $i = $item['id'];
                    $trees[$i] = $item;
                    $stack[] = & $trees[$i];
                } else {
                    // Add node to parent
                    $i = $item['id']; //count($stack[$l - 1]['children']);
                    $stack[$l - 1]['children'][$i] = $item;
                    $stack[] = & $stack[$l - 1]['children'][$i];
                }
            }
        }

        return $trees;
    }


    //---------------------------------------------------------

    /**
     * @return array
     * 可以直接用于ztree 只需要json编码下就行了
     */
    public static function getAdminMenuTreeArray()
    {

        $topRoot = AdminMenu::model()->find('group_code=:group_code', array(':group_code' => 'sys_admin_menu_root'));
        // $roots = SysMenuTree::model()->roots()->with('menu')->findAll();
        $roots = $topRoot->children()->findAll();

        $criteria = $topRoot->descendants()->getDbCriteria();
        $criteria->select .= ', label as name'; //ztree 用 name 作为显示！
        $command = $topRoot->getCommandBuilder()->createFindCommand($topRoot->getTableSchema(), $criteria);
        $descendants = $command->queryAll();

       return AdminMenu::toHierarchy($descendants);
    }

    public static function getAdminMenuTreeArray4role(AdminRole $adminRole){
        $topRoot = AdminMenu::model()->find('group_code=:group_code', array(':group_code' => 'sys_admin_menu_root'));
        // $roots = SysMenuTree::model()->roots()->with('menu')->findAll();
        $roots = $topRoot->children()->findAll();

        $criteria = $topRoot->descendants()->getDbCriteria();
        $criteria->select .= ', label as name'; //ztree 用 name 作为显示！
        $command = $topRoot->getCommandBuilder()->createFindCommand($topRoot->getTableSchema(), $criteria);
        //$descendants = $command->queryAll();
        $reader = $command->query() ;
        $descendants = array();
        foreach($reader as $row){
            $descendants[$row['id']] = $row ;
        }
        //特定角色对应的菜单ID们
        $menuIds = $adminRole->getMenuIds();
        foreach($menuIds as $menuId){
            if(isset($descendants[$menuId])){
                $descendants[$menuId]['checked'] = true ;
                $descendants[$menuId]['open'] = true ;
            }
        }
        return AdminMenu::toHierarchy($descendants);
    }

    //------------------------------------------------------------------\\
    /**
     * @param string $groupCode
     * @return AdminMenu
     * 确保须根存在
     */
    static public function ensureRootNode($groupCode  = 'sys_admin_menu_root'){
        if (($topRoot = AdminMenu::model()->roots()->find('group_code=:group_code', array(':group_code' => $groupCode))) == null) {
            $topRoot = new AdminMenu();
            $topRoot->label = 'top_virtual_root';
            $topRoot->group_code = $groupCode;
            $topRoot->saveNode();
        }
        return $topRoot ;
    }

    static public function addAdminMenu($menuConfig=array()){



    }

    static public function addTempAdminMenu($menuConfig=array()){
        $topRoot = self::ensureRootNode();
        $rootMenuLabel = '临时菜单 用来安装应用的';
        $root = self::model()->findByAttributes(array(
           'label'=> $rootMenuLabel,
        ));

        if(empty($root)){
            $root = new AdminMenu();
            $root->label = $rootMenuLabel;
            //$root->prependto($topRoot);
            $root->appendto($topRoot);
        }

        $currentMenu = new AdminMenu();
        $currentMenu->attributes = $menuConfig;

        $currentMenu->appendTo($root);
    }

    //------------------------------------------------------------------//
}