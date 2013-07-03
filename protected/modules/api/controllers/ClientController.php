<?php
/*
* 这是一个测试客户端 
*/

class ClientController extends Controller
{
    public $callback;
	const RequestTokenName = 'keys';
	const AccessTokenName = 'last_key';

	protected $oauth;
	private $_client;

	public function init()
	{
        $user = Yii::app()->user;
        parent::init();

        Yii::import('application.modules.api.vendors.*');
        require_once("oauth-php/client/OAuth.php");
        require_once("oauth-php/client/config.php");
        require_once("oauth-php/client/lifeduoauth.php");


        $this->callback = 'api/client/callback';
        #session_start();
	}
    /*
    *
    */
    public function actionIndex()
    {

        $data = array(
            
        );
		$this->render('index',$data);
    }

	/**
	 * Displays the login page
	 */
	public function actionCallback()
	{
        if(1 OR !$this->checkAuthorize())
        {
            $this->callback();
        }

        $this->redirect('test');
	}

    public function actionWeb()
    {
        $aurl = $this->getAuthorizeURL();

        $data = array(
            'aurl'=>$aurl,
        );
		$this->render('web',$data);
    }


    public function actionApp()
    {
        $model=new LoginForm;
        // collect user input data
        if(isset($_POST['LoginForm']))
        {
            
            
		    $o = new LifeduTOAuth(OAUTH_AKEY , OAUTH_SKEY);
            //客户端方式 登录 无需登录到web验证
            $username = $_POST['LoginForm']['username'];
            $password = $_POST['LoginForm']['password'];
		    $last_key = $o->getXauthAccessToken($username, $password);
		    $rs = $this->setOAuthData(self::AccessTokenName,$last_key);
            if(!empty($last_key))
            {
                $this->redirect('test');
            }
        }

        $data = array(
            'model'=>$model,
        );

		$this->render('app',$data);
    }
	/**
	 * Displays the login page
	 */
	public function actionTest()
	{
        $c = $this->getLifeduTClient();
        
        $api = 'echo_api';
        $ms  = $c->echo_api(); // done


        $data = array(
            'api'=>$api,
            'ms'=>$ms,
        );
		$this->render('test',$data);
	}

 	 /****************************************/	
 	 
	public function getOAuthData($name)
	{
        $key = 'session'.$name;
        #return Yii::app()->cache->get($key);
		$session=new CHttpSession;
		$session->open();

		$value=$session[$name];


		return $value;
	}

	public function setOAuthData($name,$value)
	{
        $key = 'session'.$name;
        #return Yii::app()->cache->set($key,$value);

        $_SESSION[$name] = $value;
		$session=new CHttpSession;
		$session->open();

		$result = Yii::app()->session->add($name,$value);
		return $result;
	}
	/*
	* 生成前往oauth的登录页面链接地址
	*/
	public function getAuthorizeURL()
	{
		$o = new LifeduTOAuth(OAUTH_AKEY , OAUTH_SKEY);
		$keys = $o->getRequestToken();
		$callback = Yii::app()->createAbsoluteUrl($this->callback);
		$aurl = $o->getAuthorizeURL( $keys['oauth_token'] ,false , $callback);

		//保存session
		$this->setOAuthData(self::RequestTokenName,$keys);

		return $aurl;
	}

	/*
	* 检验是否已经登录
	*/
	public function checkAuthorize()
	{
		$data = $this->getOAuthData(self::AccessTokenName);
		return !empty($data)?true:false;
	}
	/*
	* 回调接口
	*/
	public function callback()
	{
		$keys = $this->getOAuthData(self::RequestTokenName);
		$o = new LifeduTOAuth( OAUTH_AKEY , OAUTH_SKEY , $keys['oauth_token'] , $keys['oauth_token_secret']  );

		$last_key = $o->getAccessToken($_REQUEST['oauth_verifier']);
		$rs = $this->setOAuthData(self::AccessTokenName,$last_key);
	}
	/*
	* 获得client 
	*/
	public function getLifeduTClient()
	{
		if( $this->_client == false)
		{
			$last_key = $this->getOAuthData(self::AccessTokenName);
			$this->_client = new LifeduTClient( OAUTH_AKEY , OAUTH_SKEY , $last_key['oauth_token'] , $last_key['oauth_token_secret']  );
		}
		return $this->_client;
	}
	/*
	* 获得登录用户的id
	*/
	public function getUserID()
	{
        $last_key = $this->getOAuthData(self::AccessTokenName);
        return $last_key['user_id'];
	}
}
