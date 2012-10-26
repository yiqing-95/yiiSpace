<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-8-13
 * Time: 上午9:52
 * To change this template use File | Settings | File Templates.
 */
class UserSidebar extends CWidget
{

    /**
     * @var int
     */
    public $uid = 1;

    public function run(){
        $model = User::model()->findByPk(Yii::app()->user->id);
        $this->render('userSidebar',array(
            'model'=>$model,
            'profile'=>$model->profile,
        )
        );
    }

}
