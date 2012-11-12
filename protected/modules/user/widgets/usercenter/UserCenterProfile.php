<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-8-13
 * Time: 上午9:52
 * To change this template use File | Settings | File Templates.
 */
class UserCenterProfile extends YsSectionWidget
{

    /**
     * @var User
     */
    protected  $user ;

    public $template = '{userBox}';

   public function init(){
       parent::init();
       $this->user  =  UserModule::user(Yii::app()->user->id);// User::model()->findByPk(Yii::app()->user->id);
   }

    /**
     *
     */
    public function renderUserBox(){
        $model = $this->user ;
        $this->render('_profileBox',array(
            'model'=>$model,
            'profile'=>$model->profile,
        )
        );
    }

    /**
     *
     */
    public function renderSidebarMenu(){
        $this->render('_sidebarMenu',array(
            )
        );
    }

}
