<?php
/**
 * Created by PhpStorm.
 * User: yiqing
 * Date: 14-3-11
 * Time: 下午3:00
 */

class SeoSaver {


    public static function save($seoAttributes = array()){
       if(empty($seoAttributes)){
          $seoAttributes = isset($_POST['Seo'])? $_POST['Seo']: array() ;
       }

        /**
         * var Seo
         */
        $seoModel = null;
        // 如果有id传递表示是更新哦！
        if(!empty($seoAttributes['id'])){
            $seoModel = Seo::model()->findByPk($seoAttributes['id']);
        }else{
            $seoModel = new Seo() ;
        }

        $seoModel->attributes = $seoAttributes ;

         //  print_r($seoAttributes) ;
        //  die(__METHOD__);
        // 至少有一个不为空再存储 ！
        if($seoModel->title || $seoModel->keywords || $seoModel->description){
            return $seoModel->save();
        }
        return false ;
    }
} 