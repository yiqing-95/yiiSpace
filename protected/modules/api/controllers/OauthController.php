<?php

class OauthController extends Controller
{
    /*
    * 获得一个 requeset token
    */
	public function actionRequest_token()
	{
        $server = new OAuthServer();
        $token = $server->requestToken();
        exit();
	}

    /*
    * 获得一个 access token
    */
	public function actionAccess_token()
	{
        $server = new OAuthServer();
        /* ----------------------------------------------------------------- */
        $x_auth_mode = $this->module->getParam('x_auth_mode');
        if($x_auth_mode == 'client_auth')
        {
            $username = $this->module->getParam('x_auth_username');
            $password = $this->module->getParam('x_auth_password');
            $model=new LoginForm;
            $arr = array(
                'username'=>$username,
                'password'=>$password,
            );

            $model->attributes=$arr;
	        if($model->validate() && $model->login())
	        {
                $user_id = Yii::app()->user->id;
                $result = $server->xauthAccessToken($user_id);
                echo $result;exit();
	        }
        }
        /* ----------------------------------------------------------------- */
        $server->accessToken();
        exit();
	}

    /*
    * 用户登录验证
    */
	public function actionAuthorize()
	{
        //登陆用户  
        $user_id = Yii::app()->user->id;
        $model=new LoginForm;
		$errmsg = '';
        // 取得 oauth store 和 oauth server 对象  
        $server = new OAuthServer();  
        try  
        {  
            // 检查当前请求中是否包含一个合法的请求token  
            // 返回一个数组, 包含consumer key, consumer secret, token, token secret 和 token type.  
            $rs = $server->authorizeVerify($user_id);

            // 没有登录时不允许跳转
            if(!empty($user_id))
            {

                //当application_type 为 system 时，可以不须经过用户授权
                if($rs['application_type'] == 'system')
                {
                    $authorized = True;
                    $server->authorizeFinish($authorized, $user_id);
                }

                if ($_SERVER['REQUEST_METHOD'] == 'POST')  
                {  
                    // 判断用户是否点击了 "allow" 按钮(或者你可以自定义为其他标识)  
                    $authorized = True;  
                
                    // 设置token的认证状态(已经被认证或者尚未认证)  
                    // 如果存在 oauth_callback 参数, 重定向到客户(消费方)地址  
                    $verifier = $server->authorizeFinish($authorized, $user_id);  
                
                    // 如果没有 oauth_callback 参数, 显示认证结果  
                    // ** 你的代码 **  
                    echo $verifier;die;
                }  
                else  
                {  
                    #echo 'Error';  
                }  
            }
            else
            {

		        
		        // if it is ajax validation request
		        if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		        {
			        echo EActiveForm::validate($model);
			        Yii::app()->end();
		        }

		        // collect user input data
		        if(isset($_POST['LoginForm']))
		        {
			        $model->attributes=$_POST['LoginForm'];
			        // validate user input and redirect to the previous page if valid
			        if($model->validate() && $model->login())
			        {
                        $this->refresh();
			        }
		        }
            }
        }  
        catch (OAuthException $e)  
        {  
            $errmsg =  $e->getMessage();
            throw new CHttpException(401,$errmsg);
            // 请求中没有包含token, 显示一个使用户可以输入token以进行验证的页面  
            // ** 你的代码 **  
        }  
        catch (OAuthException2 $e)  
        {  
            $errmsg =  $e->getMessage();
            // 请求了一个错误的token 
            // ** 你的代码 **  
            throw new CHttpException(401,$errmsg);
        }  

        $data = array(
            'rs'=>$rs,
            'model'=>$model,
            'errmsg'=>$errmsg
        );
        $this->render('Authorize',$data);
	}
}
