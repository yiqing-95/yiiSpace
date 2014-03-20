<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-10-31
 * Time: 上午10:45
 * To change this template use File | Settings | File Templates.
 */
class MemberBlock extends CWidget implements IRunnableWidget
{
    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     * @param string $form
     * @return void
     * @internal param \the $CModel model to be validated
     */
    protected function performAjaxValidation($model,$form='login-form')
    {
        if(isset($_POST['ajax']) && $_POST['ajax']===$form)
        {
            $validateResult = CActiveForm::validate($model);
            echo $validateResult;
           /*
            $validateResult = CJSON::decode($validateResult);
            if(empty($validateResult)){
                // 验证通过 然后记录最后登录时间：

            }*/
            Yii::app()->end();
        }
    }

    public function run(){

        // collect user input data
        if(isset($_POST['UserLogin']))
        {
            $model=new UserLogin;
            $this->performAjaxValidation($model);

            $model->attributes=$_POST['UserLogin'];
            // validate user input and redirect to previous page if valid
            // 跳过验证 已经验证了
            if($model->validate(array())) {
                $this->infoBox();
            }
            Yii::app()->end();
        }

       if(Yii::app()->user->getIsGuest()){

           $this->loginBox();
       }else{
         $this->infoBox();
       }

    }

    protected function loginBox(){
        $model=new UserLogin;

        // display the login form
        $this->render('/member/login',array('model'=>$model));
    }
    protected function infoBox(){
       $user = User::model()->with('profile')->findByPk(Yii::app()->user->getId());
        $this->render('/member/infoBox',array('model'=>$user));
    }
}
