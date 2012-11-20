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

    public function renderFriends(){

    }

    public function renderFans(){

    }

    public function renderLatestVisitors(){

    }
}
