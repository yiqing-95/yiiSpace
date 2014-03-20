<?php

Yii::import('blog.models._base.BaseBlogSysCategory');

class BlogSysCategory extends BaseBlogSysCategory
{
	public static function model($className=__CLASS__) {
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
            'blogs'=>array(self::MANY_MANY,'Post','blog_sys_category2post(sys_cate_id,post_id)'),
        );
    }
}