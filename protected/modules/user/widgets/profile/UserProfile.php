<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-8-13
 * Time: 下午1:35
 * To change this template use File | Settings | File Templates.
 */
class UserProfile extends YsSectionWidget
{

    /**
     * @var string
     */
    public $template = '{top}';

    /**
     * @var User|int
     * User model or user_id
     */
    public $user ;
    /**
     * @var User
     */
    protected $_userModel ;

    /**
     * @return User
     */
    public function getUserModel(){
        return $this->_userModel ;
    }

    public function init(){
        if(empty($this->user)){
            $this->user = (isset($_GET['u'])) ? $_GET['u'] : user()->getId();
        }
       if($this->user instanceof User){
         $this->_userModel = $this->user;
       } elseif($this->user){
          // $this->_userModel = User::model()->findByPk($this->user);
           Yii::import('user.UserModule');
           //  用下面这个方法 可以保证同一个请求域只有一个个user实例
           // 这个方法 一般可以用来返回当前用户（session对应的那个） 和当前访问的谁$_GET['u']
           // 即 空间的主人
           $this->_userModel = UserModule::user($this->user);
       }
    }


    public function renderTop(){
        $model = $this->_userModel;
        $this->render('_top', array(
                'model' => $model,
                'profile' => $model->profile,
            )
        );
    }

    public function renderSidebar(){
        $model = $this->_userModel;
     $this->render('_sidebar', array(
            'model' => $model,
            'profile' => $model->profile,
        ));
    }

    /**
     * @param int $uid
     */
    public function renderUserTopMenus($uid){
        $this->render('_topMenus',array('uid'=>$uid));
    }
    public function renderFriends(){

    }

    public function renderFans(){

    }

    public function renderLatestVisitors(){

    }

    /**
     * 小图标：32px*32px （评论区域的用户头像）
     *  中图标: 48px*48px （首页的用户头像）
     * 大图标：96*96px（首页的登录头像）
     *  这个是TS 的存储方式 ：http://t.thinksns.com/data/uploads/avatar/10235/middle.jpg
     * bootstrap的评论图像是64*64
     */
    public function renderMiniUserIcon(){
        $userModel = $this->_userModel;

        $profile = $userModel->profile;
        $picId = rand(1, 5);
        $userPhotoUrl = empty($profile->photo) ? PublicAssets::instance()->url("images/user/avatars/{$picId}.jpg")
            : Ys::thumbUrl($profile->photo,32,32);
        $userSpaceUrl = UserHelper::getUserSpaceUrl($userModel->id);

        $userMiniIcon = <<<EOD
<div class="user-mini-icon ">
    <a class="user-face" target="_self" uid="10235" rel="face" href="{$userSpaceUrl}">
        <img class="thumbnail" src="{$userPhotoUrl}">
    </a>
</div>
EOD;

               echo $userMiniIcon;
    }

    public function renderSmallUserIcon(){
        $userModel = $this->_userModel;

        $profile = $userModel->profile;
        $picId = rand(1, 5);
        $userPhotoUrl = empty($profile->photo) ? PublicAssets::instance()->url("images/user/avatars/{$picId}.jpg")
            : Ys::thumbUrl($profile->photo,48,48);

        $userSpaceUrl = UserHelper::getUserSpaceUrl($userModel->id);

        $userMiniIcon = <<<EOD
<div class="user-mini-icon">
    <a class="user-face" target="_self" uid="10235" rel="face" href="{$userSpaceUrl}">
        <img class="thumbnail" src="{$userPhotoUrl}">
    </a>
</div>
EOD;
        echo $userMiniIcon;
    }
    public function renderMediumUserIcon(){
        $userModel = $this->_userModel;

        $profile = $userModel->profile;
        $picId = rand(1, 5);
        $userPhotoUrl = empty($profile->photo) ? PublicAssets::instance()->url("images/user/avatars/{$picId}.jpg")
            : Ys::thumbUrl($profile->photo,64,64);

        $userSpaceUrl = UserHelper::getUserSpaceUrl($userModel->id);

        $userMiniIcon = <<<EOD
<div class="user-mini-icon pull-left" align="center">
    <a class="user-face thumbnail" target="_self" uid="{$userModel->id}" rel="face" href="{$userSpaceUrl}">
        <img class="" src="{$userPhotoUrl}">
    </a>
    <span>{$userModel->username}</span>
</div>
EOD;
        echo $userMiniIcon;
    }

    public function renderLargeUserIcon(){
        $userModel = $this->_userModel;

        $profile = $userModel->profile;
        $picId = rand(1, 5);
        $userPhotoUrl = empty($profile->photo) ? PublicAssets::instance()->url("images/user/avatars/{$picId}.jpg")
            : Ys::thumbUrl($profile->photo,96,96);

        $userSpaceUrl = UserHelper::getUserSpaceUrl($userModel->id);

        $userMiniIcon = <<<EOD
<div class="user-mini-icon ">
    <a class="user-face" target="_self" uid="10235" rel="face" href="{$userSpaceUrl}">
        <img class="thumbnail" src="{$userPhotoUrl}">
    </a>
</div>
EOD;
        echo $userMiniIcon;
    }


}
