<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-8-27
 * Time: 上午11:40
 * To change this template use File | Settings | File Templates.
 */

/**
 * 仅包含 数据相关的功能 尽量不要包含ui元素相关的东西 因为要用多皮肤！
 * Class UserHelper
 */
class UserHelper
{
    //---------------------------------------------------------------\\
    static public function spaceVisitorRecord($uid)
    {
        echo CHtml::image(Yii::app()->createUrl('/user/user/doSpaceVisitorRecord', array('u' => $uid)), 'invisible', array(
            'style' => 'display:none'
        ));
    }


    //----------------------------------------------------------------//
    /**
     * @static
     * @param int $uid
     * @return string
     */
    static public function getUserSpaceUrl($uid = 0)
    {
        return Yii::app()->createUrl('/user/space', array('u' => $uid));
    }

    /**
     * @return string
     * 返回用户中心的URL地址！
     */
    static public function getUserCenterUrl()
    {
        return Yii::app()->createUrl('/user/home');
    }

    /**
     * @return string
     * 共享布局的路径引用 跟url的创建 都需要从项目根部算起！
     * 因为布局是跨模块的所以单纯使用布局名称 会导致在不同的模块下
     * 从不同的模块path算起的！
     * 多module共享的布局 一定要用别名或者用“//” 算起
     * 还有一种情况干脆避免模块共享布局 每个module的布局最多继承main布局但是不要使用其他
     * module下的布局 本打算让内容module共享user模块下的userCenter跟userSpace布局
     * 但布局路径解析由于使用了WebApplicationEndBehavior扩展所以布局解析可能出错！
     *  YiiUtil::getAliasOfPath(Yii::app()->getTheme()->getViewPath(),'webroot').'.user.front.layouts.userCenter';
     * 上面的方式是可行的 但感觉有点丑陋！
     */
    static public function getUserCenterLayout()
    {
        return 'userCenter';
        //return  YiiUtil::getAliasOfPath(Yii::app()->getTheme()->getViewPath(),'webroot').'.user.front.layouts.userCenter';

        //return 'user.layouts.userCenter';
        /*
        if(isset(Yii::app()->endName)){
             $endName = Yii::app()->endName ;
            return "user.{$endName}.layouts.userCenter";
        }else{
            return 'user.layouts.userCenter';
        }*/

    }

    /**
     * 各个内容块使用user模块下的布局
     * @return string
     */
    static public function getUserBaseLayoutAlias($layoutName = 'userSpace')
    {

        if (isset(Yii::app()->endName)) {
            $endName = Yii::app()->endName;
            $rtn = "user.{$endName}.layouts." . $layoutName;

            if (Yii::app()->theme !== null) {
                $themeName = Yii::app()->theme->getName();
                $rtn = "webroot.themes.{$themeName}.views." . $rtn;
            }
            return $rtn;
        } else {
            $rtn = 'user.layouts.' . $layoutName;
            if (Yii::app()->theme !== null) {
                $themeName = Yii::app()->theme->getName();
                return "webroot.themes.{$themeName}." . $rtn;
            }
            return $rtn;
        }
        /*
        if ($this->backendTheme) {
            return 'webroot.themes.backend_' . $this->backendTheme . '.views.yupe.layouts.' . ($layoutName ? $layoutName : $this->backendLayout);
        } else {
            return 'application.modules.yupe.views.layouts.' . ($layoutName ? $layoutName : $this->backendLayout);
        }
        */
    }

    /**
     * @static
     * @return string
     */
    static public function getLoginUrl()
    {
        return current(Yii::app()->getModule('user')->loginUrl);
    }

    /**
     * @var array
     */
    static private $_cache = array();

    /**
     * @static
     * @return bool
     */
    static public function getIsOwnSpace()
    {
        if (!user()->getIsGuest()) {
            $loginUserId = user()->getId();
            $spaceOwnerId = self::getSpaceOwnerId();

            return $loginUserId == $spaceOwnerId;
        } else {
            return false;
        }
    }

    /**
     * @static
     * @return int|mixed
     */
    static public function getVisitorId()
    {
        if (user()->getIsGuest()) {
            return 0;
        } else {
            return user()->getId();
        }
    }

    /**
     * @static
     * @return bool
     */
    static public function getIsLoginUser()
    {
        return !user()->getIsGuest();
    }

    static public function renderUserIcon($user)
    {

        $picId = rand(1, 5);
        $userPhotoUrl = empty($user->profile->photo) ? PublicAssets::instance()->url("images/user/avatars/5.jpg") : bu($user->profile->photo);
        $userImage = <<<U_FACE
      <div align="center" style="width:120px;height:120px;float:left;overflow:hidden;">
       <img src="{$userPhotoUrl}"
            alt=""
            class=""
            />
      </div>
U_FACE;
        echo $userImage;
    }

    /**
     * @static
     * @param int $u
     * @return UserProfile
     */
    static public function getUserPublicProfile($u = 0)
    {
        if ($u !== 0) {
            $userId = isset($_GET['u']) ? $_GET['u'] : user()->getId();
        } else {
            $userId = $u;
        }

        $cacheKey = __METHOD__ . '#' . $userId;
        if (!isset(self::$_cache[$cacheKey])) {
            $controller = Yii::app()->controller;
            self::$_cache[$cacheKey] = $controller->widget('user.widgets.profile.UserProfile', array(
                'user' => $userId, //we assume when access some one 's space we will always pass the param "u" to the $_GET
                'template' => '',
            ));
        }
        return self::$_cache[$cacheKey];
    }

    /**
     * @static
     * @return UserCenterProfile
     *
     */
    static public function getUserCenterProfile()
    {
        $cacheKey = __METHOD__;
        if (!isset(self::$_cache[$cacheKey])) {
            $controller = Yii::app()->controller;
            self::$_cache[$cacheKey] = $controller->widget('user.widgets.usercenter.UserCenterProfile', array(
                'template' => '',
            ));
        }
        return self::$_cache[$cacheKey];
    }

    /**
     * @static
     * @param int $user_a
     * @param int $approved
     * @return array
     */
    public static function getFriends($user_a = 0, $approved = 1)
    {
        $sql = "SELECT r.id as id , t.name as type_name, t.plural_name as type_plural_name,
                ua.username as user_a_name, ub.username as  user_b_name
                FROM
                relationship r,
                relationship_type t,
                user ua, user ub
                 WHERE t.id = r.type AND  ua.id = r.user_a
                  AND ub.id = r.user_b
                  AND  r.accepted = {$approved} ";
        $sql .= " AND r.user_a={$user_a} ";
        $cmd = Yii::app()->db->createCommand($sql);
        return $cmd->queryAll();

    }

    /**
     * @static
     * @param int $user_a
     * @param int $approved
     * @return array
     */
    public static function getFriendIds($user_a = 0, $approved = 1)
    {
        $sql = "SELECT  ub.id
                FROM
                relationship r,
                relationship_type t,
                user ua, user ub
                 WHERE t.id = r.type AND  ua.id = r.user_a
                  AND ub.id = r.user_b
                  AND  r.accepted = {$approved} ";
        $sql .= " AND r.user_a={$user_a} ";

        $cmd = Yii::app()->db->createCommand($sql);
        return $cmd->queryColumn();

    }

    /**
     * 可以按照用户的活跃度（最后访问时间排序）只取前1000、500个好友哦！
     *
     * return the friend ids condition
     * can be used as such :  id IN ()
     * @param int $user_a
     * @param int $approved
     * @return string
     */
    public static function getFriendIdsCondition($user_a = 0, $approved = null)
    {
        $sql = "SELECT r.user_b
                FROM
                      relationship r
                WHERE
                  ";
        $sql .= " r.user_a={$user_a} ";
        if($approved !== null){
            $sql .= " AND r.accepted = {$approved}  ";
        }
      return $sql ;
    }


    /**
     * check if  userB is friend of userA
     * @static
     * @param int $userA
     * @param int $userB
     * @return bool
     */
    public static function isFriend($userA, $userB)
    {

        $sql = "SELECT 2
                FROM
                relationship r,
                relationship_type t,
                user ua, user ub
                 WHERE t.id = r.type AND  ua.id = r.user_a
                  AND ub.id = r.user_b
                  AND  r.accepted = 1 ";
        $sql .= " AND r.user_a=:userA ";
        $sql .= " AND r.user_b=:userB ";

        $cmd = Yii::app()->db->createCommand($sql);
        return $cmd->queryScalar(array(':userA' => $userA, ':userB' => $userB)) !== false;
    }

    //----------------------------------------------------------------\\

    /**
     * @var int
     */
    static protected $spaceOwnerId;

    /**
     * @static
     * @return mixed
     * @throws CException
     * 获取当前被访问空间的用户id
     */
    static public function getSpaceOwnerId()
    {
        if (isset(self::$spaceOwnerId)) {
            return self::$spaceOwnerId;
        } elseif (isset($_GET['u'])) {
            return self::$spaceOwnerId = $_GET['u'];

        } else {
            if (user()->getIsGuest()) {
                throw new CException('must pass the u  param in  $_GET variable or set the userSpaceId manually');
            } else {
                return self::$spaceOwnerId = $_GET['u'] = user()->getId();
            }

        }
    }

    /**
     * @param int $userId
     */
    static public function setSpaceOwnerId($userId = 0)
    {
        self::$spaceOwnerId = $userId;
    }

    /**
     * @var User
     */
    static protected $spaceOwnerModel;

    /**
     * @param User $userModel
     */
    static public function setSpaceOwnerModel(User $userModel)
    {
        self::$spaceOwnerModel = $userModel;
    }

    /**
     * @return User
     */
    static public function getSpaceOwnerModel()
    {
        if (empty(self::$spaceOwnerModel) && self::getSpaceOwnerId() != null) {
            self::loadSpaceOwnerModel(self::getSpaceOwnerId());
        }
        return self::$spaceOwnerModel;
    }


    /**
     * 加载用户模型 注意 跟直接调用的区别：
     * 直接加载user实例 可能出现多次调用 ！
     * 这里有场景含义 比如你加载的这个用户是用于空间显示的 那么对于profile 区域的一些信息
     * 也给你加载进来 用户的信息可以通过behavior eav ，application.components.ExtraAttribute 等手段扩展！
     * 这样用于个人空间的某些区域需要的信息可以通过这个模型一次性获取完整 ；
     * 这里明白对于同一个模型（User ） 在不同场景下需要的信息字段是不一样的（scope 通过配置select 也可以表明需要什么信息
     * 但对于非db源的数据-- 比如缓存等 获取的信息则可以单独写方法获取）。
     *
     *新型模型（可以名为superModel） 信息源可能来自各个方面 是一个混合型模型 比如来自db cache 或者第三方api调用
     * 如 对于用户的统计信息 微博数，好友数 ，登陆次数等 这些信息可能来自后台统计脚本计算得来的 不一定需要放在user表
     * 此时界面上又要显示 那么可以在一个地方合成！还有一种做法是通过ajax|bigpipe技术 加载这些不同源的信息.
     *
     * @param int $userId
     * @return array|CActiveRecord|mixed|null
     */
    static public function loadSpaceOwnerModel($userId)
    {
        // 注意这里的技巧 内部静态变量 跟声明本类的静态变量的区别
        static $user;
        if (empty($user)) {
            $user = User::model()->findByPk($userId);
        }
        self::$spaceOwnerModel = $user;
        return $user;
    }
//----------------------------------------------------------------\\


    /**
     * @return User
     */
    static public function getLoginUserModel()
    {
        return UserModule::user(user()->getId());
    }

    /**
     * @return string
     * 返回当前用户空间主人的图标
     */
    static public function getSpaceOwnerIconUrl()
    {
        return self::getUserIconUrl(self::getSpaceOwnerModel());
    }

    static public function getUserIconUrl($data)
    {
        if ($data instanceof CActiveRecord) {
            $iconUri = $data->icon_uri;
        } else {
            $iconUri = $data['icon_uri'];
        }

        if (empty($iconUri)) {
            $picId = rand(1, 5);
            return Yii::app()->getModule('user')->getAssetsUrl() . "/defaultAvatars/{$picId}.jpg";

        } else {
            return bu($iconUri);
        }
    }

    /**
     * @param User $userModel
     */
    static public function renderSimpleProfile($userModel)
    {
        $spaceOwner = $userModel;
        $iconUrl = $userModel->getIconUrl();
        $userNameLabel = $userModel->getAttributeLabel('username');
        $regTime = Yii::app()->dateFormatter->format('y-m-d', $userModel->create_at);
        $spaceUrl = self::getUserSpaceUrl($userModel->primaryKey);
        $simpleProfile = <<<SP
                     <div class="col">
                            <div class="cell " >
                                <figure class="nuremberg">
                                <a href="{$spaceUrl}">
                                    <img src="{$iconUrl}" alt="" width="100px" height="100px">
                                </a>
                                    <figcaption>Efteling</figcaption>
                                </figure>
                            </div>
                         <div class="cell">
                               <ul class="nav">
                                   <li>
                                       {$userNameLabel}:
                                       {$userModel->username}
                                   </li>
                                   <li>
                                       注册时间：{$regTime}
                                   </li>

                               </ul>
                         </div>

                     </div>
SP;

        echo $simpleProfile;
    }


    /**
     * 获取可访问的用户图像url
     * @param $iconUrl
     * @return string
     */
    static public function getIconUrl($iconUrl)
    {
        // die(__METHOD__);

        if (empty($iconUrl)) {

            $picId = rand(1, 5);
            return Yii::app()->getModule('user')->getAssetsUrl() . "/defaultAvatars/{$picId}.jpg";

        } else {
            return bu($iconUrl);
        }

    }
}
