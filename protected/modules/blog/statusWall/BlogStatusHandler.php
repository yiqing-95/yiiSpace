<?php
/**
 *  
 * User: yiqing
 * Date: 13-5-17
 * Time: 上午9:51
 * To change this template use File | Settings | File Templates.
 * -------------------------------------------------------
 * 需要设计接口 然后实现 可以使用widget技巧 渲染复杂数据
 * -------------------------------------------------------
 */

class BlogStatusHandler {


    /**
     * @var string
     */
    public $actorLink ;
    /**
     * @var User|array
     */
    public $actor ;

    /**
     * @var array
     */
    public $data ;


    public function init(){
        $this->data = CJSON::decode($this->data['update']); ;
    }

    /**
     * @return mixed
     */
    public function renderTitle(){

        $data = $this->data ;
        //$blogTitleLink = CHtml::link($data['title'],Yii::app()->createUrl('blog/post/view',array('id'=>$data['id'],'title'=>$data['title'])));
        echo " {$this->actorLink} 发布博文  ";
    }

    /**
     * @return mixed
     */
    public function renderBody(){

        $data = $this->data ;

        $blogTitleLink = CHtml::link($data['title'],Yii::app()->createUrl('blog/post/view',array('id'=>$data['id'],'title'=>$data['title'])));
        $blogTeaser =  '';
        if(isset($data['teaser'])){
            $blogTeaser = $data['teaser'];
        }
        $bodyTpl = <<<BODY
     <h3> {$blogTitleLink} </h3>
     <p>
     {$blogTeaser}
     </p>
BODY;
       echo $bodyTpl;

    }
}