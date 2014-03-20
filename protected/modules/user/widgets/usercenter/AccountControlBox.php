<?php
/**
 *  
 * User: yiqing
 * Date: 13-5-6
 * Time: 上午12:07
 * To change this template use File | Settings | File Templates.
 * -------------------------------------------------------
 * -------------------------------------------------------
 */

class AccountControlBox extends YsSectionWidget
{

    /**
     * @var User
     */
    protected  $user ;

    public $template = '{profile}{menu}';

    public function init(){
        parent::init();
        $this->user  =  UserModule::user(Yii::app()->user->id);// User::model()->findByPk(Yii::app()->user->id);
    }

    /**
     *
     */
    public function renderProfile(){
        $model = $this->user ;
        $this->render('accountControl/_profileBox',array(
                'user'=>$model,
                'profile'=>$model->profile,
            )
        );
    }

    /**
     *
     */
    public function renderMenu(){
        $this->render('accountControl/_sidebarMenu',array(
            )
        );
    }

}
