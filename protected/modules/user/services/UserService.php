<?php
/**
 *
 * User: yiqing
 * Date: 13-3-24
 * Time: 下午6:17
 * To change this template use File | Settings | File Templates.
 * -------------------------------------------------------
 * -------------------------------------------------------
 */
Yii::import('user.services.interfaces.*');
class UserService implements IUser
{

    /**
     * @param $username
     * @param $password
     * @return array
     * {error:0|1,}
     */
    public function register($username, $password)
    {
        $user = new User();
        $user->username = $username;
        $user->password = $password;

        $user->email = 'ceshi@qq.com';

        if($user->save()){
            return array(
              'status'=>true,
                'data'=>$user->attributes,
            );
        }else{
            return array(
                'status'=>true,
               // 'errorCode'=>1001,
                'data'=>$user->getErrors(),
            );
        }

    }
}
