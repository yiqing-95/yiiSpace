<?php
Yii::app()->getModule('comment');

class LastCommentsWidget extends YsWidget
{
    public $model;
    public $commentStatus;
    public $limit = 10;
    public $onlyWithAuthor = true;
    /**
     * you can assign it to another value
     * @var string
     */
    public $view = 'lastComments';
    /**
     * to whom the model belong to
     * @var int
     */
    public $modelOwnerId  ;

    public function init()
    {
        if ($this->model) {
            $this->model = is_object($this->model) ? get_class($this->model) : $this->model;
        }

        $this->commentStatus || ($this->commentStatus = Comment::STATUS_APPROVED);
    }

    public function run()
    {
        $criteria = new CDbCriteria(array(
           // 'condition' => 'status = :status AND id<>root',
            'condition' => 'status = :status ',
            'params' => array(':status' => $this->commentStatus),
            'limit' => $this->limit,
            'order' => 'id DESC',
        ));

        if ($this->model) {
            $criteria->addCondition('model = :model');
            $criteria->params[':model'] = $this->model;
        }

        if ($this->onlyWithAuthor) {
            $criteria->addCondition('user_id is not null');
        }

        if($this->modelOwnerId){
            $criteria->addCondition('model_owner_id = :model_owner_id');
            $criteria->params[':model_owner_id'] = $this->modelOwnerId;
        }

        $comments = Comment::model()->findAll($criteria);

        $this->render($this->view, array('models' => $comments));
    }
}