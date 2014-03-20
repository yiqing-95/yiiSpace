<?php
/**
 *  
 * User: yiqing
 * Date: 13-6-25
 * Time: 下午3:02
 * To change this template use File | Settings | File Templates.
 * -------------------------------------------------------
 * -------------------------------------------------------
 */

class SysCategoryPostsPageBox extends YsPageBox {

    /**
     * @var bool
     * 只渲染内容 不渲染外围的border和头尾
     */
    public $onlyContent = true;
    /**
     * @var BlogSysCategory
     */
    public $sysCategory ;


    public function run(){

        $cateName = $this->sysCategory->name;
        $this->header = <<<EOD
  <div> {$cateName}  <span class="float-right"> >> </span></div>
EOD;


        $this->body = $this->renderPosts();
        parent::run();
    }

    public function renderPosts(){

        $sysCateId = $this->sysCategory->primaryKey;

        $criteria = new CDbCriteria();
        $criteria->join = 'RIGHT JOIN blog_sys_category2post sc ON sc.post_id = t.id';
        $criteria->addCondition('sc.sys_cate_id=:sysCateId');
        $criteria->params = array(':sysCateId'=>$sysCateId);
        $criteria->limit = 16;

        $posts = Post::model()->findAll($criteria);

        return $this->render('sysCatePosts',array('posts'=>$posts),true) ;
    }
}