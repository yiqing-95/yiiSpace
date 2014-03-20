<?php

class SiteController extends Controller
{
    public $layout = '//layouts/main';

    public function beforeAction($action){
        if(!in_array($action->id,array('index'))){
            $this->layout = '//layouts/column1';
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
            /*
            'genApp' => array(
               'class'=> 'LAutoGenAppAction',
            ),
            */
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
        if ($error = Yii::app()->errorHandler->error) {

            if (Yii::app()->request->isAjaxRequest){
                echo $error['message'];
            }else{
                if(YII_DEBUG){
                    // 输出全部信息：
                        var_dump($error);
                }
                $this->render('error', $error);
            }

        }
    }

    /**
     * Displays the contact page
     */
    public function actionContact()
    {
        $this->layout = '//layouts/column1';

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


        $model = new LoginForm;

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
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
     * @return void
     * @Desc('用来测试迁移的')
     */
    public function actionMigration()
    {
        Yii::import('application.user.migrations.m110805_153437_installYiiUser');
        $doIt = new m110805_153437_installYiiUser();
        //$doIt->down();
        $doIt->up();

        /*
         $runner=new CConsoleCommandRunner();
         $runner->commands=array(
             'migrate' => array(
                 'class' => 'system.cli.commands.MigrateCommand',
                 'migrationPath' => 'application.modules.user.migrations',
                // 'migrationTable' => 'yiispace_migration',
                 'interactive' => false,
             ),
         );

         ob_start();
         $runner->run(array(
             'yiic',
             'migrate',
         ));
         echo htmlentities(ob_get_clean(), null, Yii::app()->charset);
        */
    }

    /**
     * @see https://github.com/samdark/Yeeki
     */
    public function installSample(){
            /*
            * Yeeki application installer
            */

//$yii=dirname(dirname(__FILE__)).'/framework/yii.php';
            $yii = 'yii/framework/yii.php';
            $config = dirname(dirname(__FILE__)) . '/app/config/main.php';

            require_once($yii);
            Yii::createWebApplication($config);
            $installedFileName = Yii::app()->getRuntimePath() . '/installed';

            if (file_exists($installedFileName)) {
                echo 'Already installed.';
                die();
            }

            $runner = new CConsoleCommandRunner();
            $runner->commands = array(
                'migrate' => array(
                    'class' => 'system.cli.commands.MigrateCommand',
                    'migrationPath' => 'application.modules.wiki.migrations',
                    'migrationTable' => 'wiki_migration',
                    'interactive' => false,
                ),
            );

            ob_start();
            $runner->run(array(
                'yiic',
                'migrate',
            ));
            echo htmlentities(ob_get_clean(), null, Yii::app()->charset);

            file_put_contents($installedFileName, 'remove if you need to run install.php again');
    }


    //----------------------------------------------------------------\\
    /**
     * 确保ElasticSearch中的Index存在：
     */
    public function actionEnsureEsIndex(){
        Yii::setPathOfAlias('Elastica',Yii::getPathOfAlias('application.vendors.Elastica'));

        $client = new Elastica\Client();
        $indexName = 'yii_space';
        $index = $client->getIndex($indexName);
        if(! $index->exists()){
            $index->create(array(), true);
            // Refresh index
            $index->refresh();
        }

        if($index->exists()){
            echo 'index [',$indexName,'] success created!';
        }

    }
    //----------------------------------------------------------------//
}