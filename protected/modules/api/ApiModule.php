<?php

class ApiModule extends CWebModule
{

    private $_version = "0.1beta";
    //oauth 机制中当前验证通过的 uid，如果你想取得当前用户的id 使用ApiModule::getUid();
    static private $_uid;
    static private $_oauth;
    private $_debug = false;
    public $db_dsn;

	public $connectionString,$username,$password;
	public function init()
	{
        Yii::app()->homeUrl = array('/api');

        $api_url = Yii::app()->createAbsoluteUrl('/api');

        define('SUB_DOMAIN_api',$api_url);



		// 设置 CHttpException 的处理 action
        Yii::app()->errorHandler->errorAction = "api/default/error";

		// import the module-level models and components
		$this->setImport(array(
			'api.models.*',
			'api.components.*',
		));
	}
    /*
    * 初始化 oauth 包
    */
    public function oauth_init()
    {
        Yii::import('application.modules.api.vendors.*');

		require_once("oauth-php/library/OAuthServer.php");
		require_once("oauth-php/library/OAuthStore.php");
		#require_once("oauth-php/init.php");

		$options = array(
			'dsn'=>$this->connectionString,
			'username'=>$this->username,
			'password'=>$this->password
		);

        OAuthStore::instance('PDO', $options);
    }

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			$array = array('default','client');
			if(!in_array($controller->id,$array))
			{
                $this->oauth_init();
                $oauth_version = $this->getParam('oauth_version');
                //oauth 不需要验证
				if($controller->id != 'oauth')
    				$this->authorization();
			}
            else
            {

            }
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}

    public function authorization()
    {
        if (OAuthRequestVerifier::requestIsSigned())
        {
                try
                {
                        $req = new OAuthRequestVerifier();
                        $user_id = $req->verify();

                        // If we have an user_id, then login as that user (for this request)
                        if ($user_id)
                        {
                            self::setUid($user_id);
                            //这是 oauth 访问
                            self::$_oauth = true;
                                // **** Add your own code here ****
                        }
                }
                catch (OAuthException $e)
                {
                        $msg = $e->getMessage();
						throw new CHttpException(401,$msg);
                        exit();
                }
        }
        else
        {
            $msg = "Can't verify request, missing oauth_consumer_key or oauth_token";
			throw new CHttpException(401,$msg);
            exit();

        }
    }
    /*
    * api 输出数据, 输出为json格式。
    */
	public static function d($data)
	{
		echo CJSON::encode($data);
        exit;
	}

    public static function setUid($uid)
    {
        if(empty($uid))
        {
            $msg =  "authorization failed, missing login user id.";
			throw new CHttpException(401,$msg);
            exit();
        }
        //登录为yii user 
        self::$_uid = $uid;
    }

    public static function getUid()
    {
        return self::$_uid;
    }

    public function getParam($name,$default = '')
    {
		$req = new OAuthRequestVerifier();
		$value = $req->getParam($name);

		if(empty($value))
		{
			$value = Yii::app()->request->getParam($name,$default); 
		}
        return $value;
    }
}
