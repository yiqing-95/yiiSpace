<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-11-3
 * Time: 下午7:41
 * To change this template use File | Settings | File Templates.
 */
/**
 * 可以用user() 访问到user模型下的属性哦！
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $icon_uri
 * @property string $email
 * @property string $activkey
 * @property integer $superuser
 * @property integer $status
 * @property string $create_at
 * @property string $lastvisit_at
 *
 * The followings are the available model relations:
 * @property UserProfile $userProfile
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
    }


    public function logout($destroySession = true)
    {

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

    //----------------------------------------------------------------\\
    // @see http://www.yiiframework.com/wiki/60/

    /**
     * @var User
     */
    private $model ;

    /**
     * @return CActiveRecord|null|User
     */
    public function getModel()
    {
        if(!isset($this->id)) $this->model = new User;
        if($this->model === null)
            $this->model = User::model()->findByPk($this->id);
        return $this->model;
    }

    /**
     * @param string $name
     * @return mixed|null
     * @throws CException
     * @throws Exception
     */
    public function __get($name) {
        try {
            return parent::__get($name);
        } catch (CException $e) {
            $m = $this->getModel();
            if($m->__isset($name))
                return $m->{$name};
            else throw $e;
        }
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return mixed|void
     */
    public function __set($name, $value) {
        try {
            return parent::__set($name, $value);
        } catch (CException $e) {
            $m = $this->getModel();
            $m->{$name} = $value;
        }
    }

    /**
     * @param string $name
     * @param array $parameters
     * @return mixed
     */
    public function __call($name, $parameters) {
        try {
            return parent::__call($name, $parameters);
        } catch (CException $e) {
            $m = $this->getModel();
            return call_user_func_array(array($m,$name), $parameters);
        }
    }

    //----------------------------------------------------------------//

    /**
     * @param bool $fromCookie
     */
    protected function afterLogin($fromCookie)
    {
        parent::afterLogin($fromCookie);
        $this->updateSession();
    }

    /**
     * 需要写入session中的用户变量
     * 比如 email asa 等不常变化 但应用各处都会用到的 密码不要存 当cookie开启时可能暴露用户信息！
     * TODO 这些变量可以暴露给外部 用配置来做哦
     * note: 注意如果其他原因导致用户的这些变量修改 那么需要显式调用该方法
     *
     */
    public function updateSession() {
        $user = $this->getModel();
        /**
        $userAttributes = CMap::mergeArray(array(
            'email'=>$user->email,
            'username'=>$user->username,
            'create_at'=>$user->create_at,
            'lastvisit_at'=>$user->lastvisit_at,
        ),$user->profile->getAttributes());
         */
        $attributeNames = array(
          'username','email','icon_url',
        );
        $userAttributes  = $user->getAttributes($attributeNames);
        foreach ($userAttributes as $attrName=>$attrValue) {
            $this->setState($attrName,$attrValue);
        }
    }


}
