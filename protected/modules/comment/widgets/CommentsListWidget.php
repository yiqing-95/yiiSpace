<?php
Yii::import('comment.models.Comment');

/**
 * 该类其实可以直接继承自CListView 但也可以内部使用它(主要考虑到需要使用theme特征 原始的CListView 对子view的存放不友好)
 * 一般有组合优于继承的说法
 *
 * 该widget 负责渲染评论列表 或者一个单独的评论内容(ajax 提交后为了提高效率 不是全加载而是只加载一个评论内容)
 *
 * .........................................
 * 对某个实体的view视图 如果出现翻页 并且是ajax请求时 可以只局部渲染此widget 而不是整个view视图都加载
 *  ~~~
 *  [php]
 *       CDetailView ...
 *
 *       CommentsListViewWidget ..
 *
 *       CommentFormWidget
 *  ~~~
 *  在以上view视图中 可以在ajax请求模式中只渲染CommentListViewWidget
 * 这样在需要单独为commentList 准备一个视图比如： _commentList   在控制器actionView中只渲染此局部视图
 * ~~~
 * [php]
 *       actionView($id){
 *          $model = loadModel(id);
 *          if(request()->getIsAjaxRequest()){
 *             $this->renderPartial('_commentList',array(
                    'model'=>$model ;
 *             ));
 *             // 直接结束本次请求处理
 *              Yii::app()->end();
 *          }
 *          $this->render(....);
 * }
 * ~~~
 * Class CommentsListWidget
 */
class CommentsListWidget extends YsWidget
{


    public $model;
    public $modelId;
    public $label;
    /**
     * used to render single comment
     * @var null|Comment
     */
    public $comment = null;
    public $comments;
    public $status;
    /**
     *  这个可以被 其他模块的视图实现替换掉哦
     *  如使用别名：user.views.xxx._commentView
     * @var string
     */
    public $view = 'commentsList';

    /**
     * @var string
     */
    public $id;

    /**
     * TODO .....
     * who can delete a comment item
     * - the comment owner
     * - the comment object(target) owner
     *
     * @var bool
     */
    public $canDelete = false;

    /**
     * only the object(target model) owner can approve a comment
     * @var bool
     */
    public $canApprove = false ;

    /**
     * determined by canDelete and canApprove attribute
     * @var bool
     */
    protected $adminMode = false ;

    /**
     * will pass to the underline CListView
     * @var string
     */
    // public $ajaxUrl = array('/comment/comment/commentList');

    /**
     * @var string
     */
    public $baseAssetsUrl ;

    /**
     * Инициализация виджета:
     * @throws CException
     * @return void
     **/
    public function init()
    {
        if ($this->comment === null) {
            /*
            Yii::app()->clientScript->registerScriptFile(
                Yii::app()->assetManager->publish(Yii::app()->theme->basePath . '/web/js/commentlist.js')
            );
            */
            if ((empty($this->model) && empty($this->modelId))) {
                throw new CException(
                    Yii::t(
                        'CommentModule.comment',
                        'Please, set "model" and "modelId" for "{widget}" widget!',
                        array(
                            '{widget}' => get_class($this),
                        )
                    )
                );
            }

            $this->model = is_object($this->model) ? get_class($this->model) : $this->model;
            $this->modelId = (int)$this->modelId;
        }

        if (empty($this->label)) {
            $this->label = Yii::t('CommentModule.comment', 'Comments');
        }

        if (empty($this->status)) {
            $this->status = Comment::STATUS_APPROVED;
        }
        // 此id用来标识listView 用来ajax更新
        // 如果不设此值那么需要使用js动态计算 比如.list-view .comment 等方式！
        if (empty($this->id)) {
            $this->id = "cmt_list_{$this->model}_{$this->modelId}";
        }

        // TODO here have some improvement！
        // $this->adminMode = $this->canDelete || $this->canApprove ;
        if(!Yii::app()->user->getIsGuest()    )
        $this->adminMode = true ;

        //
        if($this->baseAssetsUrl===null){
            $assetsPath = dirname(__FILE__). DIRECTORY_SEPARATOR . 'assets';
            $this->baseAssetsUrl = Yii::app()->getAssetManager()->publish($assetsPath,false,-1,YII_DEBUG);
        }

        $options = CJavaScript::encode(array(
            'deleteConfirmString' => Yii::t('CommentModule.msg', 'Delete this comment?'),
            'approveConfirmString' => Yii::t('CommentModule.msg', 'Approve this comment?'),
            'cancelButton' => Yii::t('CommentModule.msg', 'Cancel'),
        ));

        Yii::app()->clientScript->registerScriptFile($this->baseAssetsUrl.'/comment.js',CClientScript::POS_END)
        ->registerScript(__CLASS__.'#'.$this->id,"jQuery('#{$this->id}').commentsList($options);",CClientScript::POS_READY);
    }

    /**
     * Запуск виджета:
     *
     * @return void
     **/
    public function run()
    {
        if ($this->comment === null) {

            $criteria = new CDbCriteria(array(
                    'condition' => 't.model = :model AND t.model_id = :modelId AND t.status = :status',
                    'params' => array(
                        ':model' => $this->model,
                        ':modelId' => $this->modelId,
                        ':status' => $this->status,
                    ),
                    'order' => 't.lft',
                )
            );
            $dataProvider = new CActiveDataProvider('Comment', array(
                'criteria' => $criteria,
            ));
            // 如果评论者是本站会员 收集评论者的id
            $userIds = array() ;
            foreach($dataProvider->getData() as $cmt){
                if(!empty($cmt->user_id)){
                    $userIds[]  = $cmt->user_id ;
                }
            }
            $userProfiles = array() ;
            if(!empty($userIds)){
                $userProfiles = YsModuleService::call('user','getSimpleProfilesByIds',$userIds);
            }

            $this->render(
                $this->view,
                array(
                    'dataProvider' => $dataProvider,
                    'userProfiles'=> $userProfiles,
                )
            );
        } else {
            $userProfiles = array() ;
            if(!empty($this->comment->user_id)){
                $userProfiles = YsModuleService::call('user','getSimpleProfilesByIds',array($this->comment->user_id));
            }

            // 渲染单独的一个评论
            $this->render(
                '_view', // 也可以暴露为变量 可配置的？
                array(
                    'data' => $this->comment,
                    'userProfiles'=> $userProfiles
                )
            );
        }
    }
}