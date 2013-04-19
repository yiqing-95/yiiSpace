<?php
/**
 * @Desc('this is a test class . you can use test/help to see all available test items')
 */
class Test1Controller extends Controller
{
    public function actionJDropDownMenu(){
        $this->render('menu/jdropdownmenu');
    }

    public function actionDcVerticalMegaMenu(){
        $this->render('menu/dcVerticalMegaMenu');
    }

    public function actionDcMegaMenu(){
        $this->render('menu/dcMegaMenu');
    }

    public function actionJMegaMenu2(){
        $this->render('menu/jmegamenu2');
    }

    public function actionTestServiceX(){
        WebUtil::printCharsetMeta();
        Yii::app()->getModule('user');
        Yii::import('user.services.UserService');

          $us = new UserService();
       $return =    $us->register('tewt','test');

        print_r($return);
    }

    /**
     * @Desc('测试右侧浮动菜单')
     */
    public function actionRightSideBar(){
        $this->render('rightSideBar');
    }

    public function actionTest52Fr(){
        $this->layout = '//layouts/test/52fr';
        $this->render('52fr');
    }

    /**
     * @Desc('测试下die方法跟exit方法的区别 exit 只是终止当前php段 ')
     */
    public function actionTestExit(){
        $this->render('testExit');
        echo "after exit";
    }
    /**
     * @Desc('测试下die方法跟exit方法的区别 ')
     */
    public function actionTestDie(){
       $this->render('testDie');
        echo "after die";

    }


    public function actionPathAlias(){
        echo Yii::getPathOfAlias('application.hello');
    }
    /**
     * @Desc('测试配置功能')
     */
    public function actionEConfig(){
        WebUtil::printCharsetMeta();
        Yii::import('application.components.sysConfig.*');
        print_r(ESysConfig::instance()->getAll());
        echo "<br/> 测试 下不存在情况下 ",
        ESysConfig::instance()->get('none',__METHOD__),
            "<br/> 测试 下不存在情况下 ",
            ESysConfig::instance()->get('fax','defaultValue');
         //Yii::t('cate','key');
    }

    /**
     * @Desc('测试 ztree扩展!')
     */
    public function actionZTree(){
        $this->render('ztree');
    }

    public function actionTestEasyUi(){
        $this->render('easyUi');
    }

    /**
     * @Desc('创建easyUi插件集！')
     */
    public function actionCreateEasyUIWidgets()
    {
        $easyUIWidgetsDir = Yii::getPathOfAlias('my.ui.easyui.widgets');
        $easyUIWidgetsClassPrefix = "EZui";

        $classTemplate = <<<CLASS_TPL
<?php
      class {$easyUIWidgetsClassPrefix}{CamelNamePluginName} extends EasyUIBaseWidget
     {

         public \$pluginName = '{lowercasePluginName}';

     }

CLASS_TPL;

        //$easyUIPluginsDir = Yii::getPathOfAlias('my.ui.easyui.assets.plugins');
        $CamelCasePlugins = array(
            'Menu',
            'Draggable',
            'Droppable',
            'Pagination'
        );
        // 加载gii模块 要使用里面的一些东东啦！
        Yii::app()->getModule('gii');

        $successClasses = $failureClasses = array();
        foreach ($CamelCasePlugins as $plugin) {
            $pluginClassPath = $easyUIWidgetsDir . DIRECTORY_SEPARATOR . $easyUIWidgetsClassPrefix.$plugin . ".php";
            $lowercasePluginName = strtolower($plugin);

            $content = str_replace(
                array('{CamelNamePluginName}', '{lowercasePluginName}'),
                array($plugin, $lowercasePluginName), $classTemplate);

            if (!is_file($pluginClassPath) && (@file_put_contents($pluginClassPath, $content) === false)) {
                   $failureClasses[$plugin] = $pluginClassPath;
            } else {
                    $successClasses[$plugin] = CHtml::encode(file_get_contents($pluginClassPath));
            }
        }
        if(count($failureClasses)>0) foreach($failureClasses as $pluginName => $pluginPath){
            echo "<div> failure to create plugin {$pluginName} to path {$pluginPath} </div>";
        }
        if(count($successClasses)>0) foreach($successClasses as $pluginName => $pluginContent){
            echo "<div> success create plugin {$pluginName} <br/> <code>{$pluginContent}</code> </div>";
        }

    }

    /**
     * @Desc('测试下 模块通讯框架2')
     */
    public function actionTestRpcServiceCall()
    {
        $serviceProviderSdk = 'application.api_vendors.yiiSpace';
        Yii::import($serviceProviderSdk . '.test.services.TestServiceHolder');

        $serviceHolder = TestServiceHolder::instance();
        //echo get_class($serviceHolder->getTest2Service()); die();
        echo $serviceHolder->getTest2Service()->helloTo(__METHOD__);

    }

    /**
     * @Desc('测试下 模块通讯框架')
     */
    public function actionTestService2()
    {
        Yii::app()->getModule('test');
        Yii::import('test.services.TestServiceHolder');

        $serviceHolder = TestServiceHolder::instance();

        echo $serviceHolder->getTest2Service()->helloTo(__METHOD__);
    }

    /**
     * @Desc('测试 Reuze css框架！')
     */
    public function actionReuze()
    {
        $this->layout = '//test1/reuze/layout';
        $this->render('reuze/content');

    }

    public function actionJVaMenu()
    {
        $this->render('jvaMenu');
    }

    public function actionPrivacyMan()
    {
        Yii::import('application.components.sysPrivacy.patternTest.chainOfResponsibility.*');
        $privacyMan = new PrivacyMan();
        $privacyMan->check(1, 1, 1);
    }

    public function actionIsFriend()
    {
        if (UserHelper::isFriend(1, 6)) {
            echo "is friend";
        } else {
            echo "no  not friend ";
        }
    }


    /**
     * @Desc('大拇指投票')
     */
    public function actionThumbVoting()
    {
        $this->render('thumbVoting');
    }


    /**
     * @Desc('测试评论配置 ')
     */
    public function actionCmtConfig()
    {
        WebUtil::printCharsetMeta();
        $system = YsCommentSystem::getAllSystems();

        //print_r($system);
        print_r(YsCommentSystem::getObjectCmtConfig('Photo'));
    }


    /**
     * @Desc('创建评论相关的表')
     */
    public function actionCreateCommentTable()
    {
        WebUtil::printCharsetMeta();
        Yii::import('application.components.sysComment.CommentMigration');
        $mig = new CommentMigration();
        $mig->up();
    }


    /**
     * @Desc('测试评论功能')
     */
    public function actionDbSchema2Migration()
    {
        YsHelper::dbSchema2migration();
    }

    /**
     * @Desc('测试评论功能')
     */
    public function actionYsComment()
    {
        $this->render('comment');
    }

    /**
     * @Desc('测试投票功')
     */
    public function actionYsVote()
    {
        $this->render('ysrating');
    }


    public function actionVote2photo()
    {
        //ob_start();
        $_POST['rate'] = 3;
        YsVotingSystem::doRating('photo', 7);

    }

    /**
     * @Desc(鉴于yiisolr不能用 所以测试这个古老的)
     */
    public function actionSolr()
    {
        Yii::import('ext.solr.*');

        $userSearchConf = array(
            'class' => 'CSolrComponent',
            'host' => 'localhost',
            'port' => 8080,
            'indexPath' => '/solr/user'
        );
        Yii::app()->setComponent('userSearch', $userSearchConf);

        $commentSearchConfig = array(
            'class' => 'CSolrComponent',
            'host' => 'localhost',
            'port' => 8080,
            'indexPath' => '/solr/comment'
        );
        Yii::app()->setComponent('commentSearch', $commentSearchConfig);

        //-------------------------------------------------
        //To add or update an entry in your index
        Yii::app()->commentSearch->updateOne(array(
                'id' => 1,
                'name' => 'tom',
                'age' => 22)
        );
        //To add or update many documents

        Yii::app()->userSearch->updateMany(array(
            '1' => array('id' => 1,
                'name' => 'tom',
                'age' => 25),
            '2' => array('id' => 2,
                'name' => 'pitt')
        ));

        //To search in your index
        $result = Yii::app()->userSearch->get('name:tom', 0, 20);
        echo "Results number is " . $result->response->numFound;
        foreach ($result->response->docs as $doc) {
            echo "{$doc->name} <br>";
        }

    }

    /**
     * @Desc('测试yiisolr扩展 注意有两个 一个不需要pecl solr扩展一个是纯php库另一个位于ext.solr下')
     */
    public function actionYiiSolr()
    {
        $solrManagerConfig = array(
            'class' => 'ext.yiisolr.YSolrConnection',
            'host' => 'localhost',
            'port' => 8983,
            'username' => '',
            'password' => '',
            'indexPath' => '/solr',
        );
        Yii::app()->setComponent('solrManager', $solrManagerConfig);

        //Adding one document to your index
        Yii::app()->solrManager->updateOne(array('id' => 1, 'title' => 'Test Title One'));
        //Adding many documents to your index at once
        $data = array(
            array('id' => 1, 'title' => 'Test Title One'),
            array('id' => 2, 'title' => 'Test Title Two'),
            array('id' => 3, 'title' => 'Test Title Three')
        );
        Yii::app()->solrManager->updateMany($data);

        //To search for these added documents
        $result = Yii::app()->solrManager->get('title:Test', 0, 20);
        //get the number of returned results
        echo "Number of results returned: " . $result->response->numFound;
        //iterate over the returned docs array to get information from each document
        foreach ($result->response->docs as $doc) {
            echo "{$doc->title} <br>";
        }
    }

    /**
     * @Desc('为指定的表名生成插入方法签名 可用的参数 tableName=user 或者 tableName/user ')
     */
    public function actionInsertMethod4table($tableName = '')
    {
        if (empty($tableName)) {
            echo "give a tableName to test this method , in pathinfo mode : tableName/user <br/>
             quering string mode : tableName=user
            ";
        } else {
            highlight_string(YiiUtil::insertMethodForTable($tableName, true));
        }

    }

    public function actionJRating()
    {
        $request = Yii::app()->request;
        if ($request->getIsAjaxRequest()) {

            $aResponse['error'] = false;
            $aResponse['message'] = '';

            // ONLY FOR THE DEMO, YOU CAN REMOVE THIS VAR
            $aResponse['server'] = '';
            // END ONLY FOR DEMO

            if (isset($_POST['action'])) {
                if (htmlentities($_POST['action'], ENT_QUOTES, 'UTF-8') == 'rating') {
                    /**
                     * vars
                     */
                    $id = intval($_POST['idBox']);
                    $rate = floatval($_POST['rate']);

                    // YOUR MYSQL REQUEST HERE or other thing :)
                    /**
                     *
                     */

                    // if request successful
                    $success = true;
                    // else $success = false;


                    // json datas send to the js file
                    if ($success) {
                        $aResponse['message'] = 'Your rate has been successfuly recorded. Thanks for your rate :)';

                        // ONLY FOR THE DEMO, YOU CAN REMOVE THE CODE UNDER
                        $aResponse['server'] = '<strong>Success answer :</strong> Success : Your rate has been recorded. Thanks for your rate :)<br />';
                        $aResponse['server'] .= '<strong>Rate received :</strong> ' . $rate . '<br />';
                        $aResponse['server'] .= '<strong>ID to update :</strong> ' . $id;
                        // END ONLY FOR DEMO

                        echo json_encode($aResponse);
                    } else {
                        $aResponse['error'] = true;
                        $aResponse['message'] = 'An error occured during the request. Please retry';

                        // ONLY FOR THE DEMO, YOU CAN REMOVE THE CODE UNDER
                        $aResponse['server'] = '<strong>ERROR :</strong> Your error if the request crash !';
                        // END ONLY FOR DEMO


                        echo json_encode($aResponse);
                    }
                } else {
                    $aResponse['error'] = true;
                    $aResponse['message'] = '"action" post data not equal to \'rating\'';

                    // ONLY FOR THE DEMO, YOU CAN REMOVE THE CODE UNDER
                    $aResponse['server'] = '<strong>ERROR :</strong> "action" post data not equal to \'rating\'';
                    // END ONLY FOR DEMO


                    echo json_encode($aResponse);
                }
            } else {
                $aResponse['error'] = true;
                $aResponse['message'] = '$_POST[\'action\'] not found';

                // ONLY FOR THE DEMO, YOU CAN REMOVE THE CODE UNDER
                $aResponse['server'] = '<strong>ERROR :</strong> $_POST[\'action\'] not found';
                // END ONLY FOR DEMO


                echo json_encode($aResponse);
            }
        } else {
            $this->render('jrating');
        }

    }

    public function actionQuickDialog()
    {
        Yii::import('ext.quickdlgs.*');
        $this->render('quickDialog');
    }

    /**
     * @Desc('测试生成相册封面')
     */
    public function actionEasyPhpThumb()
    {
        $classPath = Yii::getPathOfAlias('application.vendors.easyphpthumbnail.PHP5') . DIRECTORY_SEPARATOR . 'easyphpthumbnail.class.php';
        require_once($classPath);
        $thumb = new easyphpthumbnail;
        $thumb->Framewidth = 10;
        $thumb->Framecolor = '#FFFFFF';
        $thumb->Backgroundcolor = '#D0DEEE';
        $thumb->Shadow = true;
        $thumb->Binder = true;
        $thumb->Binderspacing = 8;
        $thumb->Clipcorner = array(2, 15, 0, 1, 1, 1, 0);
        $publicDirPath = PublicAssets::instance()->getBasePath();
        $thumb->Createthumb($publicDirPath . DIRECTORY_SEPARATOR . 'default/photo/5.jpg');
    }

    public function actionTestServiceSwitchMode()
    {
        $serviceProxy = YsService::instance();
        $serviceProxy->mode = YsService::MODE_JSON_RPC;
        echo $serviceProxy->callModuleService('test', 'sayHi');
        echo $serviceProxy->callModuleService('test', 'getServiceMode');

    }

    public function actionTestRpcService()
    {

        Yii::import('application.vendors.json_rpc.jsonRPCClient');
        $serviceRemoteProxy = new jsonRPCClient($this->createAbsoluteUrl('/api/rpc'), true);
        //$serviceRemoteProxy = new jsonRPCClient($this->createAbsoluteUrl('/api/rpc'));

        try {
            echo $serviceRemoteProxy->callModuleService('test', 'sayHi');
            echo $serviceRemoteProxy->callModuleService('test', 'getServiceMode');
        } catch (Exception $e) {
            echo nl2br($e->getMessage()) . '<br />' . "\n";
        }
    }


    public function actionTestService()
    {
        $serviceProxy = YsService::instance();
        echo $serviceProxy->callModuleService('test', 'sayHi');
    }

    public function actionGetIpLocation()
    {
        Yii::import('application.vendors.Iplocation');
        $ip = new Iplocation2(Yii::getPathOfAlias('application.vendors.ip') . DIRECTORY_SEPARATOR . 'qqwry.dat');
        $r = $ip->getlocation(WebUtil::getIp());
        WebUtil::printCharsetMeta();
        print_r($r);
        //$taoBaoIpUrl = 'http://ip.taobao.com/service/getIpInfo.php?ip=121.127.205.7';
        /*
        $taoBaoIpUrl = 'http://ip.taobao.com/service/getIpInfo.php?ip=';
        $rc = new RestClient();
        print_r($rc->get($taoBaoIpUrl));
        */
    }

    public function actionTestHookService()
    {
        YsHookService::addHook('app', 'test', 'app', 'app_onAppTest', 'yes');
    }

    /**
     * @Desc('生成php unit运行脚本')
     */
    public function actionGenPhpUnitCmd()
    {
        $testDirPathAlias = 'application.tests';
        $testDirPath = Yii::getPathOfAlias($testDirPathAlias);
        $diskRoot = $testDirPath[0];

        $eol = PHP_EOL;

        $cmdContent = <<<RUN_PHPUNIT
       {$diskRoot} {$eol}
       cd {$testDirPath} {$eol}
       phpunit unit/HelloWordTest.php {$eol}
RUN_PHPUNIT;

        $saveToPath = Yii::app()->getRuntimePath() . DIRECTORY_SEPARATOR . 'phpUnit.bat.template';
        file_put_contents($saveToPath, $cmdContent);
        echo file_get_contents($saveToPath);
    }

    public function actionYsBox()
    {
        $this->render('ysBox');
    }

    /**
     * @Desc('测试用户布局效果,layout=column2, cate=space 可以指定其他参数')
     */
    public function actionUserLayout($layout = '', $cate = 'center')
    {
        if (isset($_GET['layout'])) {
            $layout = $layout;
        }
        if (isset($_GET['cate'])) {
            $cate = $_GET['cate'];
        }
        $userLayout = ('//layouts/user/user_' . $cate . $layout);
        $this->layout = $userLayout;
        //print_r($_GET); die($layout);
        $this->renderText('test User layout! --->' . $this->layout);
    }

    /**
     * @Desc('模拟请求N次 ')
     */
    public function actionMockRequest($times = 10)
    {
        $url = $this->createUrl('', array('times' => --$times));
        $js = <<<JS
         <script type="text/javascript">
         location.href = "{$url}";
         </script>
JS;
        if ($times > 0) {
            echo $js;
        } else {
            echo "you have try enough times :) ";
        }
    }

    public function actionTestSession()
    {
        print_r(YiiUtil::getPathOfClass(Yii::app()->session));
        echo Yii::app()->session->sessionTableName;
    }

    /**
     * @Desc('测试一个页面布局效果！')
     */
    public function actionT1()
    {
        //  $this->layout = false ;
        $this->render('t1');
    }


    /**
     * @Desc('测试1列布局！')
     */
    public function actionLayoutColumn1()
    {
        $this->renderText('juiLayout');
    }

    /**
     * @Desc('测试2列布局！ 参数sidebar试着用right 看看效果！')
     */
    public function actionLayoutColumn2($sidebar = 'left')
    {
        Layout::addBlock($sidebar, array(
            'id' => $sidebar . '_sidebar',
            'content' => 'the content you want to add to your layout', // eg the result of a partial or widget
            /*
            $this->renderPartial('/partial/aspect_index_right', array(
                    'aspects'=>$user->aspects,
                    'controller'=>$this,
                ), true)
            */
        ));
        $this->renderText('layout name : ' . $this->layout);

    }

    /**
     * @Desc('测试3列布局！')
     */
    public function actionLayoutColumn3()
    {
        $sidebar = 'left';
        Layout::addBlock($sidebar, array(
            'id' => $sidebar . '_sidebar',
            'content' => 'the content you want to add to your layout', // eg the result of a partial or widget
            /*
            $this->renderPartial('/partial/aspect_index_right', array(
                    'aspects'=>$user->aspects,
                    'controller'=>$this,
                ), true)
            */
        ));
        $sidebar = 'right';
        Layout::addBlock($sidebar, array(
            'id' => $sidebar . '_sidebar',
            'content' => 'the content you want to add to your layout', // eg the result of a partial or widget
            /*
            $this->renderPartial('/partial/aspect_index_right', array(
                    'aspects'=>$user->aspects,
                    'controller'=>$this,
                ), true)
            */
        ));
        $this->renderText('layout name : ' . $this->layout);
    }

    /**
     * @Desc('测试jqueryUiLayout')
     */
    public function actionJuiLayout()
    {
        $this->layout = false;
        $this->render('juiLayout');
    }

    /**
     * @Desc('测试FleetBox')
     */
    public function actionFleetBox()
    {
        $this->render('fleetBox');
    }

    /**
     * @Desc('测试安装')
     */
    public function actionInstaller()
    {
        print_r(BaseModuleInstaller::getInstallableModules());
    }

    /**
     * @Desc('测试日历')
     */
    public function actionCalendar1()
    {
        $this->render('calendar1');
    }

    /**
     * @Desc('测试ESeasonWidget组件 可以根据季节更换图片')
     */
    public function actionSeason()
    {
        $this->render('season');
    }

    /**
     * @Desc('测试php模板基本实现 replace')
     */
    public function actionTplIdea()
    {

        $tplStr = "Hello, {name}!";
        $data = array('name' => 'yiqing');

        echo str_replace(array_keys($data), array_values($data), $tplStr);

        echo '<br/> another: ' . strtr($tplStr, $data);

    }

    /**
     * @Desc('测试smarty')
     */
    public function actionSmarty()
    {
        $this->renderString('Hello, {$name}!', array('name' => 'yiqing'));
        $output = $this->renderString('Hello, {$name}!', array('name' => 'yiqing'), true);
        echo '<br/>yes: ', $output;
    }

    /**
     * @Desc('测试twig')
     */
    public function actionTwig()
    {
        require Yii::getPathOfAlias('application.vendors.Twig') . '/Autoloader.php';
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
        $img = AppComponent::image();
        $picId = rand(1, 5);
        $userPhotoUrl = PublicAssets::instance()->getBasePath() . ("/images/user/avatars/{$picId}.jpg");
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
        $itemValue = array('k' => 'v', 'k2' => 2);
        Yii::app()->settings->set($categoryName, $itemName, $itemValue, $toDatabase = true);
        var_dump(Yii::app()->settings->get($categoryName, $itemName));
      // AppComponent::settings()->get()
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