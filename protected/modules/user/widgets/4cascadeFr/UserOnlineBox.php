<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-11-3
 * Time: 下午9:17
 * To change this template use File | Settings | File Templates.
 */
class UserOnlineBox extends YsPageBox
{

    public function init()
    {
        parent::init();

        $this->header = '当前在线用户';
        $this->body = $this->getOnlineUsers() ;
    }


    public function getOnlineUsers()
    {
        $criteria = new CDbCriteria();
        $criteria->order = 'create_at DESC';
        $criteria->limit = 15;

        $session = Yii::app()->getSession();
        if ($session instanceof YsDbHttpSession) {
            $sessionId = Yii::app()->session->sessionId;
            $sessionTable = Yii::app()->session->sessionTableName;
            $criteria->join = "JOIN {$sessionTable} s ON  user.id = s.user_id";
        }

        $users = User::model()->with('profile')->findAll($criteria);

        return $this->render('_onlineUsers', array('users' => $users), true);
    }
}
