<?php

class CategoryController extends BaseBlogController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='column2';

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
				'actions'=>array('index','view','ajaxMyCategories'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update' , $this->action->getId()),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete' , $this->action->getId()),
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
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model=new Category;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        $model->uid = user()->getId();

        if(isset($_POST['Category']))
        {
            $model->attributes=$_POST['Category'];
            if($model->save()){
                if (Yii::app()->request->isAjaxRequest) {


                    $this->ajaxSuccess(
                        array(
                            'message' => "Category successfully added",
                            'category'=>$model->attributes,
                        )
                    );
                    return ;
                } else
                    $this->redirect(array('view','id'=>$model->id));
            }
        }
        if (Yii::app()->request->isAjaxRequest) {
            $this->ajaxFailure(
                array(
                    'form' => $this->renderPartial('_form', array('model' => $model), true)
                )
            );
        } else
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

		if(isset($_POST['Category']))
		{
			$model->attributes=$_POST['Category'];
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
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Category'

        );
        $dataProvider->criteria->addColumnCondition(array('uid'=>user()->getId()));

		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
        //$this->layout = 'userCenter';

		$model=new Category('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Category']))
			$model->attributes=$_GET['Category'];

        $model->uid = user()->getId();

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Category::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='category-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

    /**
     * ajax 加载博客分类
     * 同时服务于 用户中心 和用户profile两种场景
     */
    public function actionAjaxMyCategories(){
       $request = Yii::app()->request ;
        $forUserCenter = false  ;
        //如果未传递userId参数那么认为是在用户中心布局下
        if(($userId = $request->getParam('userId',false))===false){
            $forUserCenter = true ;
            $userId = user()->getId();
        }



        $categories = Category::model()->findAllByAttributes(array(
            'uid'=>$userId,
        ));

        //.............................................\\
        // 还要查下 未分类的日志统计:
        $unCategorizedCount = Post::model()->countByAttributes(array(
           'author_id'=>$userId ,
            'category_id'=>0
        ));

        // 推人数组头部
        $unCategorizeItem = new Category();
        $unCategorizeItem->mbr_count = $unCategorizedCount;
        $unCategorizeItem->setPrimaryKey(0);
        $unCategorizeItem->name = '未归类日志';

        array_unshift($categories,$unCategorizeItem);

        //.............................................//

        $categoryHtmlTpl = '<li class=""><a href="{cateUrl}" class=""><span class="data">{memberCount}</span>{cateName}</a></li> '.PHP_EOL;

        $response = '';

        foreach($categories as $category){
            if($forUserCenter){
                $createUrl = $this->createUrl('my/index',array('category'=>$category->primaryKey));
            }else{
                $createUrl = $this->createUrl('member/list',array('u'=>$userId,'category'=>$category->primaryKey));
            }

            $response .= strtr($categoryHtmlTpl,array(
               '{cateUrl}'=> $createUrl,
                '{memberCount}'=>$category->mbr_count,
                '{cateName}'=>$category->name ,
            ));
        }

        echo $response ;
    }
}
