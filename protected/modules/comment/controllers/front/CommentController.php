<?php

class CommentController extends BaseCommentController
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return  parent::filters() + array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('index', 'view','test','add'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update', $this->action->id),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete'),
                'users' => array('admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }


    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Comment the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Comment::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Comment $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'comment-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }


    /**
     * test comment event
     */
    public function actionTest(){
          $cmtModule = Yii::app()->getModule('comment');
          $cmtModule->attachBehaviors($cmtModule->behaviors());
          $cmtModule->onCommentCreate(new CEvent($this));
    }

    //========================================================
    /**
     * add comment to a target object
     * @throws CHttpException
     */
    public function actionAdd()
    {
        if (!Yii::app()->getRequest()->getIsPostRequest() || !Yii::app()->getRequest()->getPost('Comment')) {
            throw new CHttpException(404);
        }

        $redirect = Yii::app()->getRequest()->getPost('redirectTo', Yii::app()->user->returnUrl);

        $comment = new Comment;

        $comment->setAttributes(
            Yii::app()->getRequest()->getPost('Comment')
        );

        $module = Yii::app()->getModule('comment');
        // $comment->status = (int)$module->defaultCommentStatus;

        if (!Yii::app()->user->getIsGuest()) {
            $comment->setAttributes(
                array(
                    'user_id' => Yii::app()->user->getId(),
                    'name' => user()->username,

                    //'email'   => Yii::app()->user->getState('email'),
                    'email' => user()->email,
                )
            );

            if ($module->autoApprove) {
                $comment->status = Comment::STATUS_APPROVED;
            }
        }

        $saveStatus = false;
        $parentId = $comment->getAttribute('parent_id');
        $message = Yii::t('CommentModule.comment', 'Record was not added! Fill form correct!');
        //................................................................\\
        $antiSpamTime = $this->module->antispamInterval;
        $itIsSpamMessage = null;
        /*
        $itIsSpamMessage =  Comment::isItSpam(
            $comment,
            Yii::app()->user->getId(),
            $antiSpamTime
        );
        */
        //................................................................//


        if ($itIsSpamMessage) {
            $message = Yii::t(
                'CommentModule.comment',
                'Spam protection, try to create comment after {few} minutes!',
                array('{few}' => round($antiSpamTime / 60, 1))
            );
        } else {

            // Если указан parent_id просто добавляем новый комментарий.
            if ($parentId > 0) {
                $rootForComment = Comment::model()->findByPk($parentId);
                $saveStatus = $comment->appendTo($rootForComment);
            } else { // Иначе если parent_id не указан...
                /*
                $rootNode = Comment::createRootOfCommentsIfNotExists($comment->getAttribute("model"),
                    $comment->getAttribute("model_id"));

                // Добавляем комментарий к корню.
                if ($rootNode !== false && $rootNode->id > 0) {
                    $saveStatus = $comment->appendTo($rootNode);
                }
                */
                $saveStatus = $comment->saveNode();
            }
        }

        if ($saveStatus) {


            $message = $comment->status !== Comment::STATUS_APPROVED
                ? Yii::t('CommentModule.comment', 'You comments is in validation. Thanks.')
                : Yii::t('CommentModule.comment', 'You record was created. Thanks.');

            $commentContent = $comment->status !== Comment::STATUS_APPROVED
                ? ''
                : $this->_renderComment($comment);

            if (Yii::app()->getRequest()->getIsAjaxRequest()) {
                $this->ajaxSuccess(
                    array(
                        'message' => $message,
                        'comment' => array(
                            'parent_id' => $comment->parent_id
                        ),
                        'commentContent' => $commentContent
                    )
                );
            }

            Yii::app()->user->setFlash(
                YsFlashMessages::SUCCESS_MESSAGE,
                $message
            );

            $this->redirect($redirect);

        } else {

            if (Yii::app()->getRequest()->getIsAjaxRequest()) {
                Yii::app()->ajax->failure(
                    array(
                        'message' => $message
                    )
                );
            }

            Yii::app()->user->setFlash(
                YsFlashMessages::ERROR_MESSAGE, $message
            );
            $this->redirect($redirect);
        }
    }

    /**
     * @param Comment $comment
     * @return null|string
     */
    protected function _renderComment(Comment $comment = null)
    {
        if ($comment === null) {
            return null;
        }

        ob_start();

        $comment->refresh();

        $this->widget(
            'application.modules.comment.widgets.CommentsListWidget', array(
                'canDelete' => true,
                'comment' => $comment
            )
        );

        return ob_get_clean();
    }

    /**
     * @deprecated
     *
     * 难点 由于评论功能跟其他模块需要隔离
     * 对于是否显示评论删除,批准的adminMode 需要查询其他模块
     * 这个要调用其他模块的某个门面服务!
     *
     * 此动作主要应对相册浏览时 整体页面不跳转 点击某个图片
     * 加载相应model的评论场景 (不要忘了用js设置评论的目标id哦--》modelId)
     *
     * note ： 可以通过仔细设计actionView 的视图达到相同效果哦！
     * 请参看CommentsListWidget类
     *
     * @throws CHttpException
     */
    public function actionCommentList()
    {
        $request = Yii::app()->request;
        if ($request->getIsAjaxRequest()) {
            $model = $request->getParam('model');
            $modelId = $request->getParam('modelId');

            // 模型名称的配置模拟
            $modelCommentConfigs = array(
                'User' => array(
                    'module' => 'user',
                    'service' => 'canDeleteAndEditComment'
                )
            );

            /**
             *
             */
            $canDelete = $canEdit = false;

            if (isset($modelCommentConfigs[$model])) {
                $modelCommentConfig = $modelCommentConfigs[$model];
                $moduleId = $modelCommentConfig['module'];
                $moduleServiceName = $modelCommentConfig['service'];
                $rtn = YsModuleService::call($moduleId, $moduleServiceName, array(
                    'model' => $model,
                    'modelId' => $moduleId,
                    'userId' => user()->getId(),
                ));

                if ($rtn == true) {
                    $canEdit = $canDelete = true;
                }
            }

            $this->widget(
                'application.modules.comment.widgets.CommentsListWidget',
                array(
                    // 在需要动态切换加之模型的评论列表时 注意id问题
                    // 'id'=> get_class($model).'_'.$model->primaryKey,
                    'id' => $model . '_list',
                    'model' => $model,
                    'modelId' => $modelId,
                    'canDelete' => $canDelete,
                    'canApprove' => $canEdit,
                )
            );

        } else {
            throw new CHttpException(400);
        }

    }

    /**
     * Deletes a particular model.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        // we only allow deletion via POST request
        $result = array('cmtId' => $id);
        if ($this->loadModel($id)->setDeleted()) {
            $result['status'] = 'success';
        } else {
            $result['status'] = 'fail';
        }
        echo CJSON::encode($result);
    }

    /**
     * Approves a particular model.
     * @param integer $id the ID of the model to be approve
     */
    public function actionApprove($id)
    {
        // we only allow deletion via POST request
        $result = array('cmtId' => $id);
        if ($this->loadModel($id)->setApproved()) {
            $result['status'] = 'success';
        } else {
            $result['status'] = 'fail';
        }

        echo CJSON::encode($result);
    }

    //--------------------------------------------------------------------------\\
    public function actionSent(){
        $this->layout = 'userCenter';

        $criteria = new CDbCriteria(array(
            'condition' => 'user_id=:userId',
            'params' => array(':userId' => Yii::app()->user->getId()),
            // 'limit' => $this->limit,
            //  'order' => 'id DESC',
        ));


        $dataProvider = new CActiveDataProvider('Comment',array(
            'criteria'=>$criteria,
        ));
        $dataProvider->getSort()->defaultOrder = 'id DESC';

        //...................................................................\\

        // 如果评论者是本站会员 收集评论者的id
        $userIds = array() ;
        foreach($dataProvider->getData() as $cmt){
            if(!empty($cmt->user_id)){
                $userIds[]  = $cmt->user_id ;
            }
            if(!empty($cmt->model_owner_id)){
                $userIds[]  = $cmt->model_owner_id ;
            }
        }
        $userProfiles = array() ;
        if(!empty($userIds)){
            $userProfiles = YsModuleService::call('user','getSimpleProfilesByIds',$userIds);
        }
       //...................................................................//

        $this->render('sent', array(
            'dataProvider' => $dataProvider,
            'userProfiles'=> $userProfiles,
        ));
    }

    public function actionReceived(){
        $this->layout = 'userCenter';

        $criteria = new CDbCriteria(array(
            'condition' => 'model_owner_id=:model_owner_id',
            'params' => array(':model_owner_id' => Yii::app()->user->getId()),
            // 'limit' => $this->limit,
            //  'order' => 'id DESC',
        ));


        $dataProvider = new CActiveDataProvider('Comment',array(
            'criteria'=>$criteria,
        ));
        $dataProvider->getSort()->defaultOrder = 'id DESC';

        //...................................................................\\

        // 如果评论者是本站会员 收集评论者的id
        $userIds = array() ;
        foreach($dataProvider->getData() as $cmt){
            if(!empty($cmt->user_id)){
                $userIds[]  = $cmt->user_id ;
            }
            if(!empty($cmt->model_owner_id)){
                $userIds[]  = $cmt->model_owner_id ;
            }
        }
        $userProfiles = array() ;
        if(!empty($userIds)){
            $userProfiles = YsModuleService::call('user','getSimpleProfilesByIds',$userIds);
        }
        //...................................................................//

        $this->render('received', array(
            'dataProvider' => $dataProvider,
            'userProfiles'=> $userProfiles,
        ));
    }
    //--------------------------------------------------------------------------//

}
