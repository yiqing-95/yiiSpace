<?php

class MyPostController extends BaseBlogController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='userCenter';

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
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update',$this->action->id),
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


    protected  function beforeAction( $action){
        return parent::beforeAction($action);
    }

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$post = $this->loadModel($id,'seo');


        $seoModel = $post->seo ;
        $this->registerSeo($seoModel);


		$this->render('view',array(
			'model'=>$post,

		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Post;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Post']))
		{
			$model->attributes=$_POST['Post'];
            $model->author_id = Yii::app()->user->getId();

            /**
             * 赋值给多对多关联对象
             *@see https://github.com/yiiext/activerecord-relation-behavior
             */
            if(!empty($model->sysCategories)){
                $model->sysCates  = $model->sysCategories ;
            }


			if($model->save()){
                $this->redirect(array('view','id'=>$model->id));
            }

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
		$model=$this->loadModel($id,'sysCates');

        /**
         * 也可以在afterFind中做
         */
        if(!empty($model->sysCates)){
            foreach($model->sysCates  as $sysCateModel){
                $model->sysCategories[] =  $sysCateModel->primaryKey ;
            }
        }

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Post']))
		{
			$model->attributes=$_POST['Post'];

            /**
             * 赋值给多对多关联对象
             *@see https://github.com/yiiext/activerecord-relation-behavior
             */
            if(!empty($model->sysCategories)){
                $model->sysCates  = $model->sysCategories ;
            }

			if($model->save()){
                //-----------------------------------------------------------\\
                Yii::import('my.seo.SeoSaver');
                // 额外的SEO数据存储！ 如果用behavior 来做监听afterSave方法即可
                SeoSaver::save();
                //-----------------------------------------------------------//
                $this->redirect(array('view','id'=>$model->id));
            }
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

        $criteria->addColumnCondition(array('author_id'=>user()->getId()));

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
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Post('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Post']))
			$model->attributes=$_GET['Post'];

        $model->author_id = user()->getId();
		$this->render('admin',array(
			'model'=>$model,
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
	 * @param integer the ID of the model to be loaded
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
