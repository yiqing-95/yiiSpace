<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-11-3
 * Time: 下午7:41
 * To change this template use File | Settings | File Templates.
 */
class YsWebUser extends CWebUser
{

    /**
     * Changes the current user with the specified identity information.
     * This method is called by {@link login} and {@link restoreFromCookie}
     * when the current user needs to be populated with the corresponding
     * identity information. Derived classes may override this method
     * by retrieving additional user-related information. Make sure the
     * parent implementation is called first.
     * @param mixed $id a unique identifier for the user
     * @param string $name the display name for the user
     * @param array $states identity states
     */
    protected function changeIdentity($id, $name, $states)
    {
        parent::changeIdentity($id, $name, $states);

        $session = Yii::app()->getSession();
        if ($session instanceof YsDbHttpSession) {
            $sessionId = Yii::app()->session->sessionId;
            $sessionTable = Yii::app()->session->sessionTableName;
            $sql = "UPDATE {$sessionTable} SET `user_id` = '$id' WHERE `id` = '{$sessionId}'";
            Yii::app()->db->createCommand($sql)->execute();
        }
        //  i add a field in user table  which is a indicator for user is online
        $user = User::model()->findByPk($id);
        if($user->hasAttribute('is_online')){
            $user->is_online = true ;
            $user->save(false);
        }
    }


    public function logout($destroySession = true)
    {
        //  i add a field in user table  which is a indicator for user is online
        $user = User::model()->findByPk($this->getId());
        if($user->hasAttribute('is_online')){
            $user->is_online = false ;
            $user->save(false);
        }
        parent::logout($destroySession);
        if (!$destroySession) {
            $session = Yii::app()->getSession();
            if ($session instanceof YsDbHttpSession) {
                $sessionId = Yii::app()->session->sessionId;
                $sessionTable = Yii::app()->session->sessionTableName;
                $sql = "UPDATE {$sessionTable} SET `user_id` = NULL WHERE `id` = '{$sessionId}'";
                Yii::app()->db->createCommand($sql)->execute();
            }
        }
    }
}
