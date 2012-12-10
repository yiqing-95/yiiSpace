<?php
/**
 * ECommentsFormWidget class file.
 *
 * @author Dmitry Zasjadko <segoddnja@gmail.com>
 * @link https://github.com/segoddnja/ECommentable
 */

/**
 * Widget for view comments form for current model
 *
 * @version 1.0
 * @package Comments module
 */
Yii::import('comments.widgets.ECommentsBaseWidget');
class ECommentsFormWidget extends ECommentsBaseWidget
{       
        /**
         * Is used for display validation errors
         * @var Comment newComment 
         */
        public $validatedComment;

    /**
     * @var bool
     */
    public $isReplyForm = false;
        
	public function run()
	{
            if($this->registeredOnly === false || Yii::app()->user->isGuest === false)
            {
                 $cmtModel =  empty($this->validatedComment) ? $this->createNewComment() : $this->validatedComment ;
                if($this->isReplyForm == true){
                    $this->render('cmtReplyForm', array(
                        'newComment' => $cmtModel,
                    ));
                }else{
                    $this->render('cmtForm', array(
                        'newComment' => $cmtModel,
                    ));
                }
               }
            else 
            {
                echo '<strong>'.Yii::t('CommentsModule.msg', 'You cannot add a new comment').'</strong>';
            }
	}
}