<?php

Yii::import('application.models._base.BaseSysMenu');

class SysMenu extends BaseSysMenu
{
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

    public function behaviors()
    {
        return array(
            // 'nestedInterval'=>'ext.nestedInterval.NestedIntervalBehavior',
            'NestedSetBehavior' => array(
                'class' => 'ext.yiiext.behaviors.model.trees.NestedSetBehavior',
                'leftAttribute' => 'lft',
                'rightAttribute' => 'rgt',
                'levelAttribute' => 'level',
                'hasManyRoots' => true,
            ),
        );
    }


    /**
     * @return string
     * 虚拟属性 只读
     */
    public function getIndentName()
    {
        return '|' . str_repeat('--', $this->level) . ' ' . $this->label;
    }

    /**
     * @static
     * @param $data
     * @param int $row
     * @return string
     */
    static public function renderIndentName($data, $row = 0)
    {
        return '|' . str_repeat('--', $data->level) . ' ' . $data->label;
    }
}