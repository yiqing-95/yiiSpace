<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-11-25
 * Time: 下午3:54
 * To change this template use File | Settings | File Templates.
 */
class PhotoHelper
{

    /**
     * @static
     * @return string
     * 获取我的相册路由
     */
    public static function getMyAlbumRoute(){
        return '/album/member';
    }
    public static function getCreateAlbumRoute(){
       return '/album/create';
    }

    public static function getEditAlbumRoute(){
        return '/album/update';
    }

    public static function getDeleteAlbumRoute(){
        return '/album/delete';
    }

    public static function getUploadPhotoRoute(){
        return '/photo/create';
    }

    public static function getDefaultAlbumCoverUrl(){
       //return PublicAssets::instance()->url('default/photo/cover.jpg');
       return Yii::app()->getModule('photo')->getAssetsUrl() .'/photo/cover.jpg';
  }
}
