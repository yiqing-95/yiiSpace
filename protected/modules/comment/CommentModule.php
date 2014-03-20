<?php

/**
 * 此模块参考子yupe、以及官方扩展中的commentsModule模块实现
 * 版权归人家哦
 *
 *
 * 注意对ajax加载评论的情形 CListView 的ajaxUpdate可以同时更新评论表单跟评论列表
 * 这样对图片切换同时同步加载评论的情形时可以搞定的！
 *
 * Class CommentModule
 */
class CommentModule extends  CWebModule
{
    /*
        * delete comment action route
        */
    const DELETE_ACTION_ROUTE = '/comment/comment/delete';

    /*
     * approve comment action route
     */
    const APPROVE_ACTION_ROUTE = '/comment/comment/approve';


    public $notifier = 'application\modules\comment\components\Notifier';
    public $defaultCommentStatus;
    public $autoApprove = true;
    public $notify = true;
    public $email;
    public $import = array();
    public $showCaptcha = 1;
    public $minCaptchaLength = 3;
    public $maxCaptchaLength = 6;
    public $rssCount = 10;
    public $antispamInterval = 10;
    public $allowedTags;




    public function init()
    {
        // import the module-level models and components
        $this->setImport(array(
            'comment.models.*',
            'comment.components.*',
        ));


        if (Yii::app() instanceof CWebApplication) {

            // Raise onModuleCreate event.
            Yii::app()->onModuleCreate(new CEvent($this));
        }
    }


    public function beforeControllerAction($controller, $action)
    {
        if(parent::beforeControllerAction($controller, $action))
        {
            /**
             * here you can do the filtering manually according to the [controller or action]
             *  eg :
             *       if($controller->id == 'xxx' && $action->id = 'yyy'){...}

            $filterChan = CFilterChain::create($controller,$action,array(
                array(
                    'my.filters.ERequestLockFilter',
                    // 'method'=>'ANY',
                ),
            ));
            $filterChan->run() ;

            // above code have made the controller and the action been executed  so we just return a false to halt the
            // execution !
            return false ;
            */

            // this method is called before any module controller action is performed
            // you may place customized code here
              return true;
        }
        else
            return false;
    }

    //.................................................................\\
    /**
     * @var
     */
    private $_assetsUrl;
    /**
     * @return string the base URL that contains all published asset files of gii.
     */
    public function getAssetsUrl()
    {
        if($this->_assetsUrl===null){
            $this->_assetsUrl= Yii::app()->
                getAssetManager()
                ->publish(
                    dirname(__FILE__). DIRECTORY_SEPARATOR .'assets'
                    ,
                    false,
                    -1,
                    YII_DEBUG
                );
        }

        return $this->_assetsUrl;
    }

    /**
     * @param string $value the base URL that contains all published asset files of gii.
     */
    public function setAssetsUrl($value)
    {
        $this->_assetsUrl=$value;
    }
    //.................................................................//

    /**
     * @param $model
     * @return bool|array
     */
    public function getModelConfig($model){

        // 模型名称的配置模拟 可以通过数据库来管理 在模块安装时
        $modelCommentConfigs = array(
            'Post' => array(
                'module' => 'blog',
                // 'service' => 'canDeleteAndEditComment'
                'modelProfiler'=>array(
                    'class'=>'blog.widgets.BlogProfiler',
                    'isWidget'=>true ,
                    'renderMethod'=>'commentSummary',
                ),
                'onCommentCreate'=>array(
                     // 'class'=>'',
                     'service'=>'handleCommentCreate',
                ),
                'onCommentsDeleted'=>array(
                    // 'class'=>'',
                    'service'=>'handleCommentsDeleted',
                ),
            )
        );
        if(isset($modelCommentConfigs[$model])){
            return $modelCommentConfigs[$model] ;
        }else{
            return false ;
        }
    }
    //-----------------------------------------------------\\
    /**
     * module events which can be handled by another modules
     *
     * module behaviors will proxy the listen actions !
     */
    /**
     * set behaviors

    public function preinit(){
    $this->behaviors = array(
    // handle the comment add  , delete event
    'commentEventListener'=>array(
    'class' =>  'comment.components.CommentModuleBehavior',
    ),
    );
    parent::preinit() ;
    }
     * */
    /**
     * behaviors will handle the comment related events (add , delete)
     *
     * this method will be called later after the comment added or deleted !
     * @return array
     */
    public function behaviors(){
        return array(
            'commentEventListener'=>array(
                'class' =>  'comment.components.CommentModuleBehavior',
            ),
        );
    }

    /**
     * This event should be raised when Comment created
     *
     * @param $event
     */
    public function onCommentCreate($event)
    {
        $this->raiseEvent('onCommentCreate', $event);
    }

    /**
     * This event should be raised when Comment[s] deleted
     *
     * @param $event
     */
    public function onCommentsDeleted($event)
    {
        $this->raiseEvent('onCommentsDeleted', $event);
    }

    //-----------------------------------------------------//

}