    <?php $comment = $data ; ?>

    <li id="comment-<?php echo $comment->cmt_id; ?>">

        <div class="comment-header">
            <?php echo $comment->userName;?>
            <?php echo Yii::app()->dateFormatter->formatDateTime($comment->create_time);?>
        </div>

        <?php if ($this->adminMode === true): ?>
        <div class="admin-panel">
            <?php if ($comment->status === null || $comment->status == Comment::STATUS_NOT_APPROVED)
            echo CHtml::link(Yii::t('CommentsModule.msg', 'approve'), Yii::app()->urlManager->createUrl(
                CommentsModule::APPROVE_ACTION_ROUTE, array('id' => $comment->cmt_id)
            ), array('class' => 'approve'));?>
            <?php echo CHtml::link(Yii::t('CommentsModule.msg', 'delete'), Yii::app()->urlManager->createUrl(
            CommentsModule::DELETE_ACTION_ROUTE, array('id' => $comment->cmt_id)
        ), array('class' => 'delete'));?>
        </div>
        <?php endif; ?>

        <div>
            <?php echo CHtml::encode($comment->cmt_text);?>
        </div>

        <?php
        if ( $this->allowSubcommenting === true) {
           // $this->render('ECommentsWidgetComments', array('comments' => $comment->childs));
            if ($this->allowSubcommenting === true && ($this->registeredOnly === false || Yii::app()->user->isGuest === false)) {
                echo CHtml::link(Yii::t('CommentsModule.msg', 'Add reply'), '#', array('rel' => $comment->cmt_id, 'class' => 'add-comment'));
            }
        }
        ?>
    </li>