<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-8-13
 * Time: 下午1:35
 * To change this template use File | Settings | File Templates.
 */
class UserProfile extends CWidget
{
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
           $this->_userModel = UserModule::user($this->user);
       }
    }

    public function run()
    {
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

}
