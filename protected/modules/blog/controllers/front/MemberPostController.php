<?php

class MemberPostController extends BaseBlogController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	 // public $layout='userSpace';

    protected function beforeAction($action)
    {
            $this->layout = 'userSpace';
           // $this->layout = UserHelper::getUserBaseLayoutAlias('userSpaceContent');

        return parent::beforeAction($action);
    }

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
				'actions'=>array('index','view',$this->action->id),
				'users'=>array('*'),
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
        $this->layout = 'main';
		$post=$this->loadModel($id,'author');

        UserHelper::setSpaceOwnerId($post->author_id);
        UserHelper::setSpaceOwnerModel($post->author);

        // $comment=$this->newComment($post);

		$this->render('view',array(
			'model'=>$post,
		   //	'comment'=>$comment,
		));
	}


	/**
	 * Lists all models for specified user .
	 */
	public function actionList()
	{
        $spaceOwnerId = $_GET['u'];
        UserHelper::setSpaceOwnerId($spaceOwnerId);
        UserHelper::setSpaceOwnerModel(UserModule::user($spaceOwnerId));

		$year = Yii::app()->request->getParam('year');
		$month = Yii::app()->request->getParam('month');
		$tag = Yii::app()->request->getParam('tag');
        $category = Yii::app()->request->getParam('category');
		$criteria = new CDbCriteria();
        $criteria->addColumnCondition(array('author_id'=>UserHelper::getSpaceOwnerId()));
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
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
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
	

}
