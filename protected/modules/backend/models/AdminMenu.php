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
                'class' => 'ext.yiiext.behaviors.model.trees.NestedSetBehavior',
                'leftAttribute' => 'lft',
                'rightAttribute' => 'rgt',
                'levelAttribute' => 'level',
                'hasManyRoots' => true,
            )
        );
    }


    public function scopes()
    {
        return array(

        );
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

}