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

    static public function getCategories4select(){
        $topRoot = NewsCategory::model()->find('group_code=:group_code', array(':group_code' => 'news_cate'));

        $roots = $topRoot->descendants()->findAll(array('order'=>'lft'));

        $categories = array();
        foreach($roots as $cate){
            $categories[] = array(
                'id'=>$cate->id,
                'name'=>'|'.str_repeat('--',$cate->level).$cate->name,
            );
        }
        // return $categories;

        return CHtml::listData($categories,'id','name');
    }

    protected function beforeDelete(){
        $continueDelete = parent::beforeDelete();
        if($continueDelete){
            // 如果删除了分类 本应该删掉所有分类下的文章 这里只是将他们置零
            NewsEntry::model()->updateAll(array('cate_id'=>0),'cate_id=:cate_id',array(':cate_id'=>$this->id));

            return true;
        }else{
            return false;
        }
    }
}