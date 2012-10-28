<?php
/**
 * @Desc('this is a test class . you can use test/help to see all available test items')
 */
class Test1Controller extends Controller
{

    /**
     * @Desc('测试1列布局！')
     */
    public function actionLayoutColumn1(){
        $this->renderText('juiLayout');
    }

    /**
     * @Desc('测试2列布局！ 参数sidebar试着用right 看看效果！')
     */
    public function actionLayoutColumn2($sidebar='left'){
        Layout::addBlock($sidebar, array(
            'id'=>$sidebar. '_sidebar',
            'content'=>'the content you want to add to your layout', // eg the result of a partial or widget
            /*
            $this->renderPartial('/partial/aspect_index_right', array(
                    'aspects'=>$user->aspects,
                    'controller'=>$this,
                ), true)
            */
        ));
        $this->renderText('layout name : '. $this->layout);

    }

    /**
     * @Desc('测试3列布局！')
     */
    public function actionLayoutColumn3(){
        $sidebar = 'left';
        Layout::addBlock($sidebar, array(
            'id'=>$sidebar. '_sidebar',
            'content'=>'the content you want to add to your layout', // eg the result of a partial or widget
            /*
            $this->renderPartial('/partial/aspect_index_right', array(
                    'aspects'=>$user->aspects,
                    'controller'=>$this,
                ), true)
            */
        ));
        $sidebar = 'right' ;
        Layout::addBlock($sidebar, array(
            'id'=>$sidebar. '_sidebar',
            'content'=>'the content you want to add to your layout', // eg the result of a partial or widget
            /*
            $this->renderPartial('/partial/aspect_index_right', array(
                    'aspects'=>$user->aspects,
                    'controller'=>$this,
                ), true)
            */
        ));
        $this->renderText('layout name : '. $this->layout);
    }
    /**
     * @Desc('测试jqueryUiLayout')
     */
    public function actionJuiLayout(){
        $this->layout = false;
        $this->render('juiLayout');
    }

    /**
     * @Desc('测试FleetBox')
     */
    public function actionFleetBox(){
        $this->render('fleetBox');
    }

    /**
     * @Desc('测试安装')
     */
    public function actionInstaller(){
        print_r(BaseModuleInstaller::getInstallableModules());
    }

    /**
     * @Desc('测试日历')
     */
    public function actionCalendar1(){
        $this->render('calendar1');
    }

    /**
     * @Desc('测试ESeasonWidget组件 可以根据季节更换图片')
     */
    public function actionSeason(){
        $this->render('season');
    }
    /**
     * @Desc('测试php模板基本实现 replace')
     */
    public function actionTplIdea(){

        $tplStr = "Hello, {name}!";
        $data = array('name'=>'yiqing');

        echo str_replace(array_keys($data),array_values($data),$tplStr);

        echo '<br/> another: '. strtr($tplStr,$data);

    }

    /**
     * @Desc('测试smarty')
     */
    public function actionSmarty(){
        $this->renderString('Hello, {$name}!',array('name'=>'yiqing'));
        $output = $this->renderString('Hello, {$name}!',array('name'=>'yiqing'),true);
        echo '<br/>yes: ', $output ;
    }

    /**
     * @Desc('测试twig')
     */
    public function actionTwig(){
        require Yii::getPathOfAlias('application.vendors.Twig').'/Autoloader.php';
        Yii::registerAutoloader(array('Twig_Autoloader', 'autoload'), true);

        $loader = new Twig_Loader_String();
        $twig = new Twig_Environment($loader);

        echo $twig->render('Hello {{ name }}!', array('name' => 'Fabien'));

    }

    /**
     * @Desc('测试Image 组件')
     */
    public function actionImgOp()
    {
        $img =   AppComponent::image();
        $picId = rand(1,5);
        $userPhotoUrl =  PublicAssets::instance()->getBasePath(). ("/images/user/avatars/{$picId}.jpg") ;
       /*
        $image = Yii::app()->image->load('images/test.jpg');
        $image->resize(400, 100)->rotate(-45)->quality(75)->sharpen(20);
        $image->save(); // or $image->save('images/small.jpg');
       */

        Yii::import('application.extensions.image.Image');
        $image = new Image($userPhotoUrl);
        $image->resize(400, 100)->rotate(-45)->quality(75)->sharpen(20);
        $image->render();
    }

    /**
     * @Desc('测试UploadStorage')
     */
    public function actionUploadStorage()
    {
         echo YsUploadStorage::instance()->getUploadDir();
        echo "<br/>",
        Yii::app()->request->getScriptFile();
        echo "<br/>",
        Yii::app()->request->getScriptUrl();
        echo "<br/>",
        Yii::app()->request->getBaseUrl();
    }
    /**
     * @Desc('测试JSwitchable 插件')
     */
    public function actionSwitchable()
    {

        $this->render('switchable');
    }

    /**
     * @Desc('测试YsPortLet')
     */
    public function actionYsPortLet()
    {
        //Yii::app()->setModulePath();
        $this->render('ysPortlet');
    }

    /**
     * @Desc('测试获取所有位于应用程序模块目录下常规模块们')
     */
    public function actionTestGetModules()
    {
        // print_r(My::getModules());
        foreach (My::getModules() as $module) {
            echo CHtml::link($module, Yii::app()->createUrl($module)), '<br/>';
        }
    }

    /**
     * @Desc('install the SDashboard extension s database table ')
     */
    public function actionInstallDashboardTable()
    {
        Yii::import('ext.DLDatabaseHelper');

        $sqlFilePath = Yii::getPathOfAlias('application.modules.SDashboard') . DIRECTORY_SEPARATOR . 'schema.sql';
        DLDatabaseHelper::import($sqlFilePath);
        $tableName = 'tbl_dashboard';

        $tableNames = Yii::app()->getDb()->getSchema()->getTableNames();
        if (in_array($tableName, $tableNames)) {
            $tablePrefix = Yii::app()->getDb()->tablePrefix;
            $newTableName = $tablePrefix . 'dashboard';
            $easyQuery = EasyQuery::instance($tableName);
            $easyQuery->renameTable($newTableName);
            echo "rename table {$tableName} to {$newTableName} ";
        }

    }

    /**
     * @Desc('test global helper class My')
     */
    public function actionTestMy()
    {
        echo My::getHomeUrl();
        echo My::getPublicFilesUrl('hi');
    }

    /**
     * @Desc('test the yii-mail extension ')
     */
    public function actionTestSwiftMailer($to = null)
    {
        $message = new YiiMailMessage;
        $message->view = null; // we don't use the template view just set body mamually

        $message->setBody('hi baby !', 'text/html');
        $message->addTo(isset($to) ? $to : 'yiqing_95@qq.com');
        $message->from = 'yii_qing@163.com'; //必须跟申请时的一致哦
        Yii::app()->mail->send($message);
    }

    /**
     * @Desc('try the extension settings :
     * [howToCreateUrlsToOtherApp](http://www.yiiframework.com/forum/index.php/topic/27133-how-to-create-urls-to-other-applications/page__hl__+hare+config)')
     */
    public function actionTestRemoteUrl()
    {
        $route = 'test/help';
        $params = array('p1' => 'v1', 'p2' => 'v2');
        echo 'url for backend:', UrlController::getRemoteUrl($route, $params, 'backend');
        echo '<br/>url for frontend:', UrlController::getRemoteUrl($route, $params);
        die();
    }


    /**
     * @Desc('try the extension curl : [curl](http://www.yiiframework.com/extension/curl)')
     */
    public function actionTestCurl()
    {
        $curl = AppComponent::curl();
        $data = $curl->run('http://www.atropa.com.hr/index.php?', FALSE,
            array(
                'option' => 'com_content', 'view' => 'article', 'id' => 58, 'Itemid' => 2
            )
        );
        print_r($data);
    }

    /**
     * @Desc('try the extension settings : [settings](http://www.yiiframework.com/extension/settings)')
     */
    public function actionTestCmsSettings()
    {
        $categoryName = 'cate1';
        $itemName = 'key1';
        $itemValue = 'hello ' . __FUNCTION__;

        Yii::app()->settings->set($categoryName, $itemName, $itemValue, $toDatabase = true);

        // Get a database item:
        echo Yii::app()->settings->get($categoryName, $itemName);
        //.......................................................
        $itemName = 'key2';
        $itemValue = array('k'=>'v','k2'=>2);
        Yii::app()->settings->set($categoryName, $itemName, $itemValue, $toDatabase = true);
        var_dump(Yii::app()->settings->get($categoryName, $itemName));
    }

    public function actionIndex()
    {
        $this->render('index');
    }

    // Uncomment the following methods and override them if needed
    /*
     public function filters()
     {
         // return the filter configuration for this controller, e.g.:
         return array(
             'inlineFilterName',
             array(
                 'class'=>'path.to.FilterClass',
                 'propertyName'=>'propertyValue',
             ),
         );
     }

     public function actions()
     {
         // return external action classes, e.g.:
         return array(
             'action1'=>'path.to.ActionClass',
             'action2'=>array(
                 'class'=>'path.to.AnotherActionClass',
                 'propertyName'=>'propertyValue',
             ),
         );
     }
     */
}