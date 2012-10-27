<?php

class SiteController extends Controller
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
			'genApp' => array(
			   'class'=> 'LAutoGenAppAction',
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
        if (Yii::app()->user->isGuest) {
            $this->redirect(array('site/login'));
        } else {
            // renders the views file 'protected/views/site/index.php'
            // using the default layout 'protected/views/layouts/main.php'
            //该页面为直接输出，无需layout。由于继承backendcontroller，因此要设置该Layout=false,否则要在该视图嵌套一个多余的Layout
            $this->layout = false;

            $topRoot = AdminMenu::model()->find('group_code=:group_code', array(':group_code'=>'sys_admin_menu_root'));
            // $roots = SysMenuTree::model()->roots()->with('menu')->findAll();
            $roots = $topRoot->children()->findAll();

            $descendants=$topRoot->descendants()->findAll();

            $this->render('index', array('roots' => $roots,'descendants'=>$descendants));
        }
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
	
	/**
     * @Desc('ajax �첽�����ݣ�')
     * ------------------------------------------
     * [
    {
    "text": "1. Pre Lunch (120 min)",
    "expanded": true,
    "classes": "important",
    "children":
    [
    {
    "text": "1.1 The State of the Powerdome (30 min)"
    },

    {
    "text": "1.2 jQuery UI - A step to richnessy (60 min)"
    }
    ]
    },
    {
    "text": "2. Lunch  (60 min)"
    },
    {
    "text": "3. After Lunch  (120+ min)",
    "children":
    [
    {
    "text": "3.1 jQuery Calendar Success Story (20 min)"
    },
    {
    "text": "3.2 jQuery and Ruby Web Frameworks (20 min)"
    },
    {
    "text": "3.3 Hey, I Can Do That! (20 min)"
    },
    {
    "text": "3.4 Taconite and Form (20 min)"
    },
    {
    "text": "3.5 Server-side JavaScript with jQuery and AOLserver (20 min)"
    },
    {
    "text": "3.6 The Onion: How to add features without adding features (20 min)",
    "id": "36",
    "hasChildren": true
    },
    {
    "text": "3.7 Visualizations with JavaScript and Canvas (20 min)"
    },
    {
    "text": "3.8 ActiveDOM (20 min)"
    },
    {
    "text": "3.8 Growing jQuery (20 min)"
    }
    ]
    }
    ]
     * -------------------------------------------
     */
    public function actionAjaxFillTree(){
        if (!Yii::app()->request->isAjaxRequest) {
           $this->render('ajaxFillTree');
            Yii::app()->end();
           // exit();
        }
        $parent = null;//��ʼֵ
        if (isset($_GET['root']) && $_GET['root'] !== 'source') {
            $parent =  $_GET['root'];
        }
        $children = array(); //��Ϊ����ص�����
        if(is_null($parent)){
            //��һ������
            foreach(YiiUtil::getControllersFromDir(Yii::app()->controllerPath) as $controller){

                $children[] = array(

                    'id'=> 'controller_'.$controller,
                    'text'=> $controller,
                    'expanded'=>false,
                    'hasChildren'=>true,
                );
            }
        }else{
            //���ǵ�һ��������
            /**
             * ������Ҫ��ȡָ���������µ�����action ������Ҫ���ⷽ���ſ��Ի�ȡ��
             * ����Ϊ����������LController ��ƻ��ߺ��� attach һ����Ϊ
             */
             LController::$safeGetActions = true;
             if(($pos = strpos($parent,'controller_')) !== false){
                 $controllerId = substr($parent,strlen('controller_'));
                ob_start();
                $this->forward($controllerId.'/getActions',false);
                $actions = ob_get_clean();

                 $actions = CJSON::decode($actions);

                 foreach($actions as $action){
                     $children[] = array(
                         'text'=> $controllerId.'::'.$action
                     );
                 }

             }
        }

        echo CTreeView::saveDataAsJson($children);

        /*
        $parentId = "NULL";
        if (isset($_GET['root']) && $_GET['root'] !== 'source') {
            $parentId =  $_GET['root'];
        }
        $req = Yii::app()->db->createCommand(
            "SELECT m1.id, m1.name AS text, m2.id IS NOT NULL AS hasChildren "
                . "FROM tree AS m1 LEFT JOIN tree AS m2 ON m1.id=m2.parent_id "
                . "WHERE m1.parent_id <=> $parentId "
                . "GROUP BY m1.id ORDER BY m1.name ASC"
        );
        $children = $req->queryAll();
        echo str_replace(
            '"hasChildren":"0"',
            '"hasChildren":false',
            CTreeView::saveDataAsJson($children)
        );
        */
        exit();
    }

}