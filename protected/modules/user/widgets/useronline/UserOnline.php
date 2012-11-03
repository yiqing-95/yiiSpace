<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-11-3
 * Time: 下午9:17
 * To change this template use File | Settings | File Templates.
 */
class UserOnline  extends HomePageBlock
{
    /**
     * @var string
     */
    public  $title = '在线用户';

    /**
     * @var string
     */
    public $activeAction = 'online';


    public function init(){
        $this->tbBoxOptions['headerIcon'] = 'icon-user' ;
        // 'headerActions'=>false, this can empty the headerActions
    }

    /**
     * @return array
     */
    protected function getHeaderActions(){
        return  array(

        );
    }

    public function actionOnline(){
        $criteria = new CDbCriteria();
        $criteria->order = 'create_at DESC';
        $criteria->limit = 15 ;

        $session = Yii::app()->getSession();
        if ($session instanceof YsDbHttpSession) {
            $sessionId = Yii::app()->session->sessionId;
            $sessionTable = Yii::app()->session->sessionTableName;
            $criteria->join = "JOIN {$sessionTable} s ON  user.id = s.user_id";
        }

        $users = User::model()->with('profile')->findAll($criteria);

        $this->render('_onlineUsers',array('users'=>$users));
    }
}
