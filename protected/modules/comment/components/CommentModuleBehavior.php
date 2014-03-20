<?php

/**
 * Created by PhpStorm.
 * User: yiqing
 * Date: 14-2-15
 * Time: 下午12:53
 */
class CommentModuleBehavior extends CBehavior
{


    /**
     * Declares events and the event handler methods.
     *
     * See yii documentation on behavior; this is an override of
     * {@link CBehavior::events()}
     */
    public function events()
    {
        return array_merge(parent::events(), array(
            'onCommentCreate' => 'handleCommentCreate',
            'onCommentsDeleted' => 'handleCommentsDeleted',
        ));
    }

    /**
     * when comment added  you can increment the according model 's attribute (such as cmt_count )
     * @param CEvent $event
     */
    public function handleCommentCreate(CEvent $event)
    {
        // print_r($event) ;
        // $addedComment = new Comment() ;
        $addedComment = $event->sender;
        $model = $addedComment->model;
        $modelId = $addedComment->model_id;

        // TODO you can send email to the model owner !
        $modelOwnerId = $addedComment->model_owner_id;
        /**
         * dolphin handle the comment added event in such way:
         * using model to get the according config ,which contain the comment related fields :
         * cmt_count ,last_cmt_time etc ...
         * after the comment added , you can automatic update these fields !
         *
         * here we just let these things handled by the module that the model belongs to .
         */
        $cmtModule = Yii::app()->getModule('comment');
        $modelConfig = $cmtModule->getModelConfig($model);
        if (!empty($modelConfig) && is_array($modelConfig)) {
            if (isset($modelConfig['onCommentCreate'])) {

                if (isset($modelConfig['onCommentCreate']['service'])) {
                    YsModuleService::call($modelConfig['module'], $modelConfig['onCommentCreate']['service'],
                        // should convert the ar to array
                        $addedComment->getAttributes()
                    );
                }
            }
        }
    }

    public function handleCommentsDeleted(CEvent $event)
    {
        // print_r($event) ;
        // $addedComment = new Comment() ;
        $comment = $event->sender;
        $model = $comment->model;
        $modelId = $comment->model_id;

        // TODO you can send email to the model owner !
        $modelOwnerId = $comment->model_owner_id;

        $cmtModule = Yii::app()->getModule('comment');
        $modelConfig = $cmtModule->getModelConfig($model);
        if (!empty($modelConfig) && is_array($modelConfig)) {
            if (isset($modelConfig['onCommentsDeleted'])) {

                if (isset($modelConfig['onCommentsDeleted']['service'])) {
                    $notifyData = $comment->getAttributes();
                    $notifyData['model_cmt_count'] = Comment::model()->countByAttributes(array(
                        'model' => $model,
                        'model_id' => $modelId,
                    ));

                    YsModuleService::call($modelConfig['module'], $modelConfig['onCommentsDeleted']['service'],
                        // should convert the ar to array
                        $notifyData
                    );

                }
            }
        }
    }
}
