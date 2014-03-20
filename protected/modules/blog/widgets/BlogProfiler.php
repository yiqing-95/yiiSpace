<?php
/**
 * Created by PhpStorm.
 * User: yiqing
 * Date: 14-2-13
 * Time: 下午4:24
 */

class BlogProfiler extends YsWidget {

    /**
     * @param $data
     */
    public function commentSummary($data){
          // print_r($data);
         // echo __METHOD__ ;
        $blogTitleLink = CHtml::link($data['title'],
            Yii::app()->createUrl('blog/post/view',array('id'=>$data['id'],'title'=>$data['title'])));
        echo $blogTitleLink ;
    }
} 