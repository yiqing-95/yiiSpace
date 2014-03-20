<?php
/**
 *  
 * User: yiqing
 * Date: 13-5-8
 * Time: 上午11:14
 * To change this template use File | Settings | File Templates.
 * -------------------------------------------------------
 * -------------------------------------------------------
 */

class BlogHelper {

    /**
     * @return string
     */
    static public function getCreateBlogUrl(){

        return Yii::app()->createUrl('/blog/my/create');
    }

    /**
     * @param array|Post $post
     * @throws CException
     * @return string
     */
    public static function createBlogUrl($post){
        if(is_array($post)){
            return Yii::app()->createUrl('blog/post/view',array('id'=>$post['id'],'title'=>$post['title']));
        }elseif($post instanceof Post){
            return Yii::app()->createUrl('blog/post/view',array('id'=>$post->primaryKey,'title'=>$post->title));
        }else{
            throw new CException('you give a wrong params for creating blog url');
        }
    }

    static  protected $statusTypeId ;

    /**
     * @return mixed
     */
    static public function getStatusTypeId(){
        /*
        Yii::app()->getModule('status');
        return StatusManager::getStatusTypeId('blog_create');
        */
        if(!isset(self::$statusTypeId)){
            $statusTypeArr = require(Yii::getPathOfAlias('blog.data.statusWall'). DIRECTORY_SEPARATOR .'statusType.php');
            self::$statusTypeId = $statusTypeArr['type_id'];
        }
        return self::$statusTypeId;

    }
}