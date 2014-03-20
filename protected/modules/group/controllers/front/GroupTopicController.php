<?php

class GroupTopicController extends BaseGroupController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
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
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update',$this->action->id),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
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
        $model = GroupTopic::getTopic4View($id);
        if($model===null){
           WebUtil::throw404httpException();
        }
        // 为子控制器传值
        unset($_GET['id']);
        $_GET['topic'] = $id ;
        // 导航到子控制器中
        $this->forward('groupTopicPost/posts4topic');
        /*
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
        */
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{

        $groupId = Yii::app()->request->getParam('groupId');

        $model=new GroupTopic;
        $model->group_id = $groupId ;
        $model->creator_id = user()->getId() ;


		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['GroupTopic']))
		{
			$model->attributes=$_POST['GroupTopic'];

            $model->created = time() ;

			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['GroupTopic']))
		{
			$model->attributes=$_POST['GroupTopic'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('GroupTopic');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionListMyTopics()
	{
		$model=new GroupTopic('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['GroupTopic']))
			$model->attributes=$_GET['GroupTopic'];

        // 只显示当前登录者的话题
        $model->creator_id = user()->getId() ;

        $dataProvider = $model->search() ;

        // 配置排序
        $sort = new CSort();
        //$sort->defaultOrder = 'item_no DESC';
        $sort->defaultOrder = 'created DESC ';
        //$sort->attributes = array('goods_name', 'item_no','goods_py');
        $sort->attributes = array('created',);

        $dataProvider->sort = $sort ;


		$this->render('listMyTopics',array(
			'model'=>$model,
            'dataProvider'=>$dataProvider ,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return GroupTopic the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=GroupTopic::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param GroupTopic $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='group-topic-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
