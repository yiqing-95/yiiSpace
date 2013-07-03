<?php

class SiteController extends BaseBlogController
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('index');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('error', $error);
	    }
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$headers="From: {$model->email}\r\nReply-To: {$model->email}";
				mail(Yii::app()->params['adminEmail'],$model->subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
    public function actionWblogin()
    {
        if(isset($_REQUEST['state'])==Yii::app()->session['sina_state']){
            if(isset($_REQUEST['code'])){
                Yii::import('ext.oauthLogin.sina.sinaWeibo',true);
                $keys = array();
                $keys['code'] = $_REQUEST['code'];
                $keys['redirect_uri'] = WB_CALLBACK_URL;
                try {
                    $weibo = new SaeTOAuthV2(WB_AKEY,WB_SKEY);
                    $sinaToken = $weibo->getAccessToken('code',$keys);
                } catch (CHttpException $e) {

                }
                //获取认证
                 if (isset($sinaToken)) {
                    Yii::app()->session->add('sinaToken',$sinaToken);
                    //查询微博的账号信息
                    $c = new SaeTClientV2( WB_AKEY , WB_SKEY ,Yii::app()->session['sinaToken']['access_token']);
                    $userShow  = $c->getUserShow(Yii::app()->session['sinaToken']); // done
                    //查询是否有绑定账号   
                    $user = UserBinding::model()->with('user')->find('user_bind_type = :bind_type AND user_access_token = :access_token AND user_openid=:openid',array(':bind_type' =>'sina',':access_token' =>Yii::app()->session['sinaToken']['access_token'],':openid' =>Yii::app()->session['sinaToken']['uid']));
                    //如果没有存在则创建账号及绑定
                    if (!isset($user)){
                        $userBingding = array();
                        $userBingding['access_token'] = Yii::app()->session['sinaToken']['access_token'];
                        $userBingding['openid'] = Yii::app()->session['sinaToken']['uid'];
                        $userBingding['username'] = $userShow['screen_name'];
                        $userBingding['bind_type'] = 'sina';
                        $userBingding['avatar'] = $userShow['profile_image_url']; 
                        $userBind = UserBinding::addBinding($userBingding, $_REQUEST['state']);
                    }else{
                        Yii::app()->user->id = $user->user_id;
                        Yii::app()->user->name = $user->user->username;
                    }
                        
                    $this->redirect(Yii::app()->session['back_url']);
                 }  else {
                     echo '认证失败';
                 }
            }
        }
    }
    
    public function actionQqlogin()
    {
        if(isset($_REQUEST['state'])==Yii::app()->session['qq_state']){
          if(isset($_REQUEST['code'])){
                Yii::import('ext.oauthLogin.qq.qqConnect',true);
                $keys = array();
                $keys['code'] = $_REQUEST['code'];
                $keys['state'] = Yii::app()->session['qq_state'];
                $keys['redirect_uri'] = QQ_CALLBACK_URL;
                try {
                    $qqConnect = new qqConnectAuthV2(QQ_APPID,QQ_APPKEY);
                    $qqToken = $qqConnect->getAccessToken('code',$keys);
                } catch (CHttpException $e) {

                }
                
                if (isset($qqToken)) {
                    Yii::app()->session->add('qqToken',$qqToken);
                    Yii::import('ext.oauthLogin.qq.qqConnect',true);
                    $c = new qqConnectAuthV2(QQ_APPID,QQ_APPKEY);
                    $userInfo = $c->getUserInfo(Yii::app()->session['qqToken']);
                    $userShow= array();
                    $userShow['screen_name'] = $userInfo['nickname'];
                    $userShow['profile_image_url'] = $userInfo['figureurl_2'];
                    //查询是否有绑定账号   
                    $user = UserBinding::model()->with('user')->find('user_bind_type = :bind_type and user_openid=:openid',array(':bind_type' =>'qq',':openid' =>Yii::app()->session['qqToken']['openid']));
                    
                    //如果没有存在则创建账号及绑定
                    if (!isset($user)){
                        $userBingding = array();
                        $userBingding['access_token'] = Yii::app()->session['qqToken']['access_token'];
                        $userBingding['openid'] = Yii::app()->session['qqToken']['openid'];
                        $userBingding['username'] = $userShow['screen_name'];
                        $userBingding['bind_type'] = 'qq';
                        $userBingding['avatar'] = $userShow['profile_image_url']; 
                        $userBind = UserBinding::addBinding($userBingding, $_REQUEST['state']);
                    }else{
                        Yii::app()->user->id = $user->user_id;
                        Yii::app()->user->name = $user->user->username;
                    }
                    $this->redirect(Yii::app()->session['back_url']);
                }  else {
                    echo '认证失败';
                }
          }
       }
    }

    public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}