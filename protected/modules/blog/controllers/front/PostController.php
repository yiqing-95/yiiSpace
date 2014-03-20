<?php

class PostController extends BaseBlogController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='userSpace';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
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
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','SuggestTags','list'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin','demo'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
        $this->layout =  'userSpace';
		$post=$this->loadModel($id,'author');

        UserHelper::setSpaceOwnerId($post->author_id);
        UserHelper::setSpaceOwnerModel($post->author);

        //    $comment=$this->newComment($post);
		$this->render('view',array(
			'model'=>$post,
		 //	'comment'=>$comment,
		));
	}


	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{

        $this->layout = 'column1';



        $blogSysCategories = BlogSysCategory::model()->with(array('blogs:recent'))->findAll(array('order'=>'position DESC'));

		$this->render('index',array(
            'blogSysCategories'=>$blogSysCategories,
		));
	}

    /**
     * Lists all models.
     */
    public function actionList()
    {

        $this->layout = 'column1';

        $year = Yii::app()->request->getParam('year');
        $month = Yii::app()->request->getParam('month');
        $tag = Yii::app()->request->getParam('tag');
        $category = Yii::app()->request->getParam('category');
        $criteria = new CDbCriteria();
        if(isset($tag)){
            $criteria->addSearchCondition('tags',$tag);
        }
        if(isset($category)){
            $criteria->addSearchCondition('category_id',$category);
        }
        if(isset($month)){
            $criteria=array(
                'condition'=>'created > :time1 AND created < :time2 AND status=2',
                'params'=>array(':time1' => mktime(0,0,0,$month,1,$year),
                    ':time2' => mktime(0,0,0,$month+1,1,$year),
                ),
            );
        }
        $dataProvider=new CActiveDataProvider('Post',array(
            'criteria'=>$criteria,
            'sort'=>array(
                'defaultOrder'=> 'created  DESC',
            )
        ));

        $blogSysCategories = BlogSysCategory::model()->with(array('blogs:recent'))->findAll(array('order'=>'position DESC'));

        $this->render('list',array(
            'dataProvider'=>$dataProvider,
            'blogSysCategories'=>$blogSysCategories,
        ));
    }

	/**
	 * Suggests tags based on the current user input.
	 * This is called via AJAX when the user is entering the tags input.
	 */
	public function actionSuggestTags()
	{
		if(isset($_GET['q']) && ($keyword=trim($_GET['q']))!=='')
		{
			$tags=Tag::model()->suggestTags($keyword);
			if($tags!==array())
				echo implode("\n",$tags);
		}
	}

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param $id
     * @param array|string $with  array('author', 'category'=>array('order'=>'id DESC'))
     * @throws CHttpException
     * @internal param \the $integer ID of the model to be loaded
     * @return Post
     */
	public function loadModel($id,$with=array())
	{
        if(!empty($with)){
            $model=Post::model()->with($with)->findByPk($id);
        }else{
            $model=Post::model()->findByPk($id);
        }

		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='post-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	/**
	 * Creates a new comment.
	 * This method attempts to create a new comment based on the user input.
	 * If the comment is successfully created, the browser will be redirected
	 * to show the created comment.
	 * @param Post the post that the new comment belongs to
	 * @return Comment the comment instance
	 */
	protected function newComment($post)
	{
		$comment=new Comment;
		if(isset($_POST['ajax']) && $_POST['ajax']==='comment-form')
		{
			echo CActiveForm::validate($comment);
			Yii::app()->end();
		}
		if(isset($_POST['Comment']))
		{
			$comment->attributes=$_POST['Comment'];
			if($post->addComment($comment))
			{
				if($comment->status==Comment::STATUS_PENDING)
					Yii::app()->user->setFlash('commentSubmitted','Thank you for your comment. Your comment will be posted once it is approved.');
				$this->refresh();
			}
		}
		return $comment;
	}
}
