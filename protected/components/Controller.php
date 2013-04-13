<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout = '//layouts/main';
    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu = array();
    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs = array();


    public function actions()
    {
        return array(
            'help' => array(
                'class' => 'my.actions.HelpAction',
            ),
            //  it suitable for test
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }
    /**
     * @Desc('use this action to list the all available actions of this controller ')
     */
    public function actionHelp()
    {
        if(! YS_CONTROLLER_HELP ){

            return ;

        }
        $controller = $this;
        require(Yii::getPathOfAlias('application.vendors.addendum') . DS . 'annotations.php');
        //延迟导入注解类 其他地方又不用 导入是浪费
        Yii::import('my.annotation.Desc');

        $actions = YiiUtil::getActionsOfController($controller);
        $actionCount = count($actions);
        //$colors = array('#dd9988', '#eeccff');
        $random_color1 = ' #62e00 '; // '#' . base_convert(rand(0, 16777215), 10, 16);
        $random_color2 = ' #60800'; //'#' . base_convert(rand(0, 16777215), 10, 16);
        $colors = array($random_color1, $random_color2);
        WebUtil::printCharsetMeta();
        ob_start();
        ?>
    <p>
    <h2>
        controller descriptions：
        <?php
        $rc = new ReflectionAnnotatedClass($controller);
        $descAnnotation = $rc->getAnnotation('Desc'); //如果没有就返回false
        if ($descAnnotation !== false) {
            echo $descAnnotation->value;
        } else {
            echo 'this controller have no description now!';
        }
        ?>
    </h2>
    <h3>
        you could use the following link to access the action
    </h3>
    </p>
    <table width="80%">
        <thead>
        <tr style="background-color: #009933; ">
            <th>
                action id :
            </th>
            <th>
                action access route
            </th>
            <th>
                action description
            </th>
        </tr>
        </thead>
        <tbody style="text-align: center;">
            <?php for ($i = 0; $i < $actionCount; $i++): ?>

            <?php
            /**
             * 通过controller的 actions()方法返回的动作无法用反射处理的
             */
            if (method_exists($controller, 'action' . $actions[$i])) {
                //用annotation解析 action的描述
                $ram = new ReflectionAnnotatedMethod(get_class($controller), 'action' . $actions[$i]);
                /*
                if($ram->hasAnnotation('ActionDesc')){
                    $ram->getAnnotation('ActionDesc');
                  }
                */

                if (($decsAnnotation = $ram->getAnnotation('Desc')) !== false) {
                    $actionDesc = $decsAnnotation->value;
                } else {
                    $actionDesc = 'no action description now';
                }
            } elseif($action = $controller->createAction($actions[$i])){
                if(! ($action instanceof CInlineAction)){
                    //not inline action , we think it to be a object which extends the CAction
                    $ram = new ReflectionAnnotatedMethod($action, 'run');
                    if (($decsAnnotation = $ram->getAnnotation('Desc')) !== false) {
                        $actionDesc = $decsAnnotation->value;
                    } else {
                        $actionDesc = 'no action description now';
                    }
                }
            }else{
                $actionDesc = 'no action description now';
            }
            ?>

        <tr style="background-color: <?php echo $colors[$i % 2]; ?> ">
            <td><?php echo $actions[$i]; ?></td>
            <td> try to access：
                <?php
                if (YS_CONTROLLER_HELP) {
                    echo CHtml::link($actions[$i], $controller->createUrl($actions[$i]));
                } else {
                    echo "this link will be display only in debug mode ! ";
                } ?>
            </td>
            <td>
                <?php
                //echo $actionDesc;
                $markdown = new CMarkdown;
                $markdown->purifyOutput = true;
                echo $markdown->transform($actionDesc);
                ?>
            </td>
        </tr>
            <?php endfor
            ; ?>
        </tbody>
    </table>

    <?php
        /*
           $controller->layout = 'clear';
           $controller->render('//public/null');
        */
        $bufferText = ob_get_clean();

        $controller->renderText($bufferText);
        /*
        $scrollToText = $controller->widget('widgets.KScrollToWidget', array(
                'label' => 'to top',
                'speed' => 'slow',
                'linkOptions'=>array(
                    'class'=>'[radius, round] [secondary, alert, success] label'
                ),
                'cssSettings' => array(
                    'background-color' => '#78901f',
                    'width' => '200px'
                )
            ), true

        );
        $controller->renderText($bufferText . $scrollToText);
        */

    }
  //........................<introduce smarty for string template render.............................................................................................

    public function renderString($tplString='',$data=null,$return=false)
    {
        if(empty($this->smarty)){
          $this->getSmarty();
        }
        //assign data
        $this->smarty->assign($data);
        //render or return
        if($return){
            return $this->smarty->fetch("string:{$tplString}");
        } else{
            $this->smarty->display("string:{$tplString}");
        }
    }

    /**
     * @var Smarty
     */
    protected $smarty ;
    public function getSmarty(){
        if($this->smarty !== null) {
            return $this->smarty ;
        }else{
            Yii::import('application.vendors.*');
            // need this since Yii autoload handler raises an error if class is not found
            spl_autoload_unregister(array('YiiBase','autoload'));
            // including Smarty class and registering autoload handler
            require_once('Smarty/Smarty.class.php');
            // adding back Yii autoload handler
            spl_autoload_register(array('YiiBase','autoload'));

            $this->smarty = new Smarty();

            $this->smarty->setTemplateDir('');
            //$this->smarty->template_dir = '';
            $compileDir = Yii::app()->getRuntimePath().'/smarty/compiled/';
            // create compiled directory if not exists
            if(!file_exists($compileDir)){
                mkdir($compileDir, 0755, true);
            }
            $this->smarty->setCompileDir($compileDir);
            $this->smarty->setPluginsDir(Yii::getPathOfAlias('application.extensions.Smarty.plugins'));
            //$this->smarty->plugins_dir[] = Yii::getPathOfAlias('application.extensions.Smarty.plugins');
            /*
            if(!empty($this->pluginsDir)){
                $this->smarty->plugins_dir[] = Yii::getPathOfAlias($this->pluginsDir);
            }*/
           /*
            if(!empty($this->configDir)){
                $this->smarty->config_dir = Yii::getPathOfAlias($this->configDir);
            }*/
            $cacheDir = Yii::app()->getRuntimePath().'/smarty/cache/';
            // create compiled directory if not exists
            if(!file_exists($cacheDir)){
                mkdir($cacheDir, 0755, true);
            }
            $this->smarty->setCacheDir($cacheDir);
            return $this->smarty ;
        }
    }
    //.....................................................................................................................


    //.....................................................................................................................

    public function ajaxReturn($data)
    {

        echo $data;
        throw new CException("暂时不准备用这个方法了！");
    }

    /**
     * ajax request is success handled
     * @param array|string $data
     * ----------------------------------------
     * 新浪ajax返回格式：{
     *    key: "xxxxxx",
     *    code: "10000",
     *    msg: "",
     *    data:"....."
     * }
     * 搜狐ajax返回格式：{
     *    status: 1,
     *    data  : "...."
     * }
     * ----------------------------------------
     */
    public function ajaxSuccess($data = array())
    {
        $ajaxReturn = array();
        if (is_string($data)) {
            $ajaxReturn['data'] = $data;
            $ajaxReturn['status'] = 'success';
        } elseif (is_array($data)) {
            if (!isset($data['status'])) {
                $data['status'] = 'success';
            }
            $ajaxReturn = &$data ;
        }
        echo CJSON::encode($ajaxReturn);
    }

    /**
     * @param array|string $data
     */
    public function ajaxFailure($data)
    {
        $ajaxReturn = array();
        if (is_string($data)) {
            $ajaxReturn['data'] = $data;
            $ajaxReturn['status'] = 'failure';
        } elseif (is_array($data)) {
            if (!isset($data['status'])) {
                $data['status'] = 'failure';
            }
            $ajaxReturn = &$data ;
        }
        echo CJSON::encode($ajaxReturn);

    }
    //----------<define controller action event>---------------------------------------------------
    public function onControllerAction($event)
    {
        $this->raiseEvent('onControllerAction', $event);
    }

    //----------<define controller action event/>---------------------------------------------------
}