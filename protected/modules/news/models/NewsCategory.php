<?php

Yii::import('news.models._base.BaseNewsCategory');

class NewsCategory extends BaseNewsCategory
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

    static public function renderIndentName(NewsCategory $data, $row)
    {
        return '|' . str_repeat('--', $data->level) . ' ' . $data->name;
    }
}