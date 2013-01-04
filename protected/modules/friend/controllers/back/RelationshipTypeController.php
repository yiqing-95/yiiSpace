<?php

class RelationshipTypeController extends BackendController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	// public $layout='//layouts/iframe';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			//'accessControl', // perform access control for CRUD operations
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
				'actions'=>array('create','update'),
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
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new RelationshipType;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['RelationshipType']))
		{
			$model->attributes=$_POST['RelationshipType'];
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

		if(isset($_POST['RelationshipType']))
		{
			$model->attributes=$_POST['RelationshipType'];
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
		$dataProvider=new CActiveDataProvider('RelationshipType');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new RelationshipType('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['RelationshipType']))
			$model->attributes=$_GET['RelationshipType'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}


    /**
    * Manages all models.
    * advanced fuctionality ,batch operation are supportted
    */
    public function actionAdminAdv()
    {
        $model=new RelationshipType('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['RelationshipType']))
             $model->attributes=$_GET['RelationshipType'];

        $this->render('adminAdv',array(
             'model'=>$model,
        ));
    }


        public function actionBatchDelete()
        {
            //  print_r($_POST);
             $request = Yii::app()->getRequest();
            if($request->getIsPostRequest()){
                if(isset($_POST['ids'])){
                      $ids = $_POST['ids'];
                }elseif(! empty($_POST['items'])){
                      $ids = $_POST['items'];
                }
                if (empty($ids)) {
                     echo CJSON::encode(array('success' => false, 'msg' => '至少选择一项'));
                      die();
                }
                //print_r($ids);
                $successCount = $failureCount = 0;
                foreach ($ids as $id) {
                $model = $this->loadModel($id);
                        ($model->delete() == true) ? $successCount++ : $failureCount++;
                }
                 echo CJSON::encode(array('success' => true,
                     'data' => array(
                         'successCount' => $successCount,
                         'failureCount' => $failureCount,
                     )));
                die();
            }else{
                     throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
            }
        }

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
     * @return RelationshipType	 */
	public function loadModel($id,$modelClass='RelationshipType')
	{
		$model=RelationshipType::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model,$form='relationship-type-form')
	{
		if(isset($_POST['ajax']) && $_POST['ajax']===$form)
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
