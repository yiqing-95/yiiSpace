<?php
class SiteController extends Controller
{
    public $menuLabelList = array() ;



    protected function beforeAction($action){

        if($action->id == 'page'){
            $this->layout =  '//layouts/iframe';
        }
        return parent::beforeAction($action);
    }
    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
            'genApp' => array(
                'class' => 'LAutoGenAppAction',
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

            $topRoot = AdminMenu::model()->find('group_code=:group_code', array(':group_code' => 'sys_admin_menu_root'));
            // $roots = SysMenuTree::model()->roots()->with('menu')->findAll();
            $roots = $topRoot->children()->findAll();

            if(Yii::app()->theme !==null && Yii::app()->theme->getName()=='EzBoot'){
                $criteria = $topRoot->descendants()->getDbCriteria();
                $criteria->select .= ', label as name'; //ztree 用 name 作为显示！
                $command= $topRoot->getCommandBuilder()->createFindCommand($topRoot->getTableSchema(),$criteria);
                $descendants = $command->queryAll();

            }else{
                $descendants = $topRoot->descendants()->findAll();
            }

            $this->render('index', array('roots' => $roots, 'descendants' => $descendants));
        }
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
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
        $model = new ContactForm;
        if (isset($_POST['ContactForm'])) {
            $model->attributes = $_POST['ContactForm'];
            if ($model->validate()) {
                $headers = "From: {$model->email}\r\nReply-To: {$model->email}";
                mail(Yii::app()->params['adminEmail'], $model->subject, $model->body, $headers);
                Yii::app()->user->setFlash('contact', 'Thank you for contacting us. We will respond to you as soon as possible.');
                $this->refresh();
            }
        }
        $this->render('contact', array('model' => $model));
    }

    /**
     * Displays the login page
     */
    public function actionLogin()
    {

        if (!AdminWebUser::getUser()->getIsGuest()){
            $this->redirect(array('/admin'));
        }


        // 禁用theme功能 暂时不需要！
        Yii::app()->theme = null; // false 会导致问题的！ 不要赋值为false

        $this->layout = 'login';
       // $model = new LoginForm;
        $model = new AdminLoginForm;
        $modelClassName = get_class($model);

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST[$modelClassName])) {
            $model->attributes = $_POST[$modelClassName];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login())
                $this->redirect(Yii::app()->user->returnUrl);
        }
        // display the login form
        $this->render('login', array('model' => $model));
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
     *
     */
    public function actionSetTheme(){
        $request = Yii::app()->request;
        if($request->getIsAjaxRequest()){
            user()->setState('currentTheme',$request->getParam('currentTheme'));
           echo user()->getState('currentTheme','cerulean');
        }
    }
}