<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-8-27
 * Time: 上午11:40
 * To change this template use File | Settings | File Templates.
 */
class UserHelper
{

    /**
     * @static
     * @param int $uid
     * @return string
     */
    static public function getUserSpaceUrl($uid=0){
        return  Yii::app()->createUrl('/user/user/space',array( 'u' => $uid));
    }
    /**
     * @static
     * @return string
     */
    static public function getLoginUrl(){
        return  current(Yii::app()->getModule('user')->loginUrl) ;
    }
    /**
     * @var array
     */
    static private $_cache = array();

    /**
     * @static
     * @return bool
     */
    static public function getIsOwnSpace(){
        if(!user()->getIsGuest()){
            $loginUserId = user()->getId();
            $spaceOwnerId = self::getSpaceOwnerId();

            return $loginUserId == $spaceOwnerId ;
        }else{
            return false ;
        }
    }

    /**
     * @static
     * @return int|mixed
     */
    static public function getVisitorId(){
        if(user()->getIsGuest()){
            return 0;
        }else{
            return user()->getId();
        }
    }

    /**
     * @static
     * @return mixed
     * @throws CException
     * 获取当前被访问空间的用户id
     */
    static public function getSpaceOwnerId(){
       if(!isset($_GET['u'])){
           if(user()->getIsGuest()){
               throw new CException('must pass the u  param in  $_GET variable ');
           }else{
               return $_GET['u'] = user()->getId();
           }

       }else{
           return $_GET['u'];
       }
    }
    /**
     * @static
     * @return bool
     */
    static public function getIsLoginUser(){
        return ! user()->getIsGuest();
    }

  static public function renderUserIcon($user){

       $picId = rand(1,5);
       $userPhotoUrl = empty($user->profile->photo)? PublicAssets::instance()->url("images/user/avatars/5.jpg"): bu($user->profile->photo) ;
      $userImage = <<<U_FACE
      <div align="center" style="width:120px;height:120px;float:left;overflow:hidden;">
       <img src="{$userPhotoUrl}"
            alt=""
            class=""
            />
      </div>
U_FACE;
       echo $userImage ;
  }

    /**
     * @static
     * @param int $u
     * @return UserProfile
     */
    static public function getUserPublicProfile($u=0){
        if($u !== 0){
            $userId =  isset($_GET['u'])?$_GET['u']:user()->getId();
        }else{
            $userId = $u ;
        }

        $cacheKey = __METHOD__.'#'.$userId;
        if (!isset(self::$_cache[$cacheKey])){
            $controller = Yii::app()->controller;
            self::$_cache[$cacheKey] = $controller->widget('user.widgets.profile.UserProfile', array(
                'user' => $userId, //we assume when access some one 's space we will always pass the param "u" to the $_GET
                'template'=>'',
            ));
        }
          return self::$_cache[$cacheKey] ;
  }

    /**
     * @static
     * @return UserCenterProfile
     *
     */
    static public function getUserCenterProfile(){
        $cacheKey = __METHOD__;
        if (!isset(self::$_cache[$cacheKey])){
            $controller = Yii::app()->controller;
            self::$_cache[$cacheKey] = $controller->widget('user.widgets.usercenter.UserCenterProfile', array(
                'template'=>'',
            ));
        }
        return self::$_cache[$cacheKey] ;
  }

}
