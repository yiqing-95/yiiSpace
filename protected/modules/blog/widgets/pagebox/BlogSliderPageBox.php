<?php
/**
 *  
 * User: yiqing
 * Date: 13-4-15
 * Time: 下午3:20
 * To change this template use File | Settings | File Templates.
 * -------------------------------------------------------
 * -------------------------------------------------------
 */

class BlogSliderPageBox extends CWidget implements IRunnableWidget{

   public function run(){

      /*
       $cmd = Yii::app()->db->createCommand();
       $cmd->select =  '';
       $cmd->from =  '';
       $cmd->join = '';
      */
       $criteria = new CDbCriteria( );
       $criteria->join = 'INNER JOIN blog_recommend rec ON t.id=rec.object_id';

       $blogPosts = Post::model()->findAll($criteria);


       $this->render('blogSlider',array('posts'=>$blogPosts));
   }


}