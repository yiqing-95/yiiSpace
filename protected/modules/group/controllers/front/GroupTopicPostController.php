<?php

class GroupTopicPostController extends BaseGroupController
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
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

    /**
     * Lists all models.
     */
    public function actionPosts4topic()
    {
        $topicId = $_GET['topic'];
        $topic = GroupTopic::getTopic4View($topicId);


        $dataProvider=new CActiveDataProvider('GroupTopicPost');
        $dataProvider->criteria->addColumnCondition(array(
           'topic_id'=>$topicId,
        ));

        // 临时测试下：
        $dataProvider->pagination->setPageSize(2);

        if(isset($_GET['postId'])){
            $postId = $_GET['postId'];

            $criteria2 = clone $dataProvider->criteria ;
             $criteria2->addCondition('t.id<'.(1+(int)$postId));
             $pagination2 = clone $dataProvider->pagination ;
             $pagination2->setItemCount(intval($dataProvider->model->count($criteria2)));

            $dataProvider->pagination->setCurrentPage($pagination2->getPageCount());
            //  不要忘了 只用一次 哦
            unset($_GET['postId']);
            /*
           $jumpToCriteria = clone $dataProvider->criteria ;
            $jumpToCriteria->addCondition('t.id<'.(1+(int)$postId));

            $total = (int)($dataProvider->model->count($jumpToCriteria));
            $dataProvider->pagination->setCurrentPage($total/$dataProvider->pagination->pageSize);
            */
            /*
             $sort = $dataProvider->getSort() ;
               if (!empty($sort)) {
                /*
                   TODO 获取当前排序的方向 ? 有必要么！！ 只要有一个为降序那么整体趋势是降序

                   $sortDirection = $sort->getDirection('create_time');
                   if($sortDirection == CSort::SORT_ASC || $sortDirection== null ){

                   }
                   $sortDirections = $sort->getDirections();
                   foreach($sortDirections as $sortDirection ){

                   }

                  //  获取当前排序方向：
                   $sortAttribute = $_GET[$sort->sortVar];
                   if(empty($sortAttribute)){
                       // 不存在 那么获取默认排序的方向
                       // 按升序处理
                       $jumpToCriteria->addCondition('t.id<'.(1+(int)$postId));
                   }else{
                       if(strpos($sortAttribute,$sort->descTag) !== false){
                           // 降序
                           // 按升序处理
                           $jumpToCriteria->addCondition('t.id<'.(1+(int)$postId));
                       }else{
                           // 按升序处理
                           $jumpToCriteria->addCondition('t.id<'.(1+(int)$postId));
                       }
                   }
            }
        */
        }



        // 用于创建
        $model = new GroupTopicPost() ;
        $model->group_id = $topic->group_id;
        $model->topic_id = $topic->primaryKey ;

        $this->render('posts4topic',array(
            'dataProvider'=>$dataProvider,
            'topic'=>$topic,
            'model'=>$model,
        ));
    }

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new GroupTopicPost;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['GroupTopicPost']))
		{
			$model->attributes=$_POST['GroupTopicPost'];

            $model->user_id = user()->getId() ;
            $model->ip = WebUtil::inet_aton(WebUtil::getClientIp());

			if($model->save()){
                if (Yii::app()->request->isAjaxRequest) {
                    $this->ajaxSuccess(
                        array(
                            'message' => "GroupTopicPost successfully added"
                        )
                    );
                    return ;
                }
            }
		}
        if (Yii::app()->request->isAjaxRequest) {
            $this->ajaxFailure(
                array(
                    'form' => $this->renderPartial('_form', array('model' => $model), true)
                )
            );
        }
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

		if(isset($_POST['GroupTopicPost']))
		{
			$model->attributes=$_POST['GroupTopicPost'];
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
	 * Manages all models.
	 */
	public function actionListMyPosts()
	{
		$model=new GroupTopicPost('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['GroupTopicPost']))
			$model->attributes=$_GET['GroupTopicPost'];

        // 去除搜索时可能输入的左右空格
        YiiUtil::trimAttributes($model);

        // 只显示当前登录者的话题
        $model->user_id = user()->getId() ;

        $dataProvider = $model->search() ;

        // 配置排序
        $sort = new CSort();
        //$sort->defaultOrder = 'item_no DESC';
        $sort->defaultOrder = 'create_time DESC ';
        //$sort->attributes = array('goods_name', 'item_no','goods_py');
        $sort->attributes = array('create_time',);

        $dataProvider->sort = $sort ;

		$this->render('listMyPosts',array(
			'model'=>$model,
            'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return GroupTopicPost the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=GroupTopicPost::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param GroupTopicPost $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='group-topic-post-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
