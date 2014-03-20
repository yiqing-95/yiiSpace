<?php
/**
 *  
 * User: yiqing
 * Date: 13-4-15
 * Time: 下午2:17
 * To change this template use File | Settings | File Templates.
 * -------------------------------------------------------
 * -------------------------------------------------------
 */
Yii::app()->getModule('news');

class NewsPageBox extends YsPageBox{


    public function init(){
        parent::init();

        $this->header = '<div>News(最近新闻)<span class="float-right"> >> </span></div>';

        $criteria = new CDbCriteria( );
        $criteria->addCondition('deleted = 0 ');

        $criteria->order = 'id DESC';
        $criteria->limit = 10 ;
        $latestNews = NewsEntry::model()->findAll($criteria);

       $body = '<ul>';
        foreach($latestNews as $news){
            $body .= CHtml::tag('li',array(),
              CHtml::link($news->title,array('/news/NewsEntry/view','id'=>$news->primaryKey))
            );
        }

        $body .= '</ul>';

        $this->body = $body ;
    }


}