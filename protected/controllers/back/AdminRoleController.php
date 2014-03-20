<?php

class AdminRoleController extends BackendController
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
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new AdminRole;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['AdminRole']))
		{
			$model->attributes=$_POST['AdminRole'];
			if($model->save()){
                if (Yii::app()->request->isAjaxRequest) {
                    exit(CJSON::encode(array(
                            'status' => 'success',
                            'message' => "AdminRole successfully added"
                        )
                    ));

                } else
				$this->redirect(array('view','id'=>$model->id));
            }
		}
        if (Yii::app()->request->isAjaxRequest) {
            exit(CJSON::encode(array(
                    'status' => 'failure',
                    'form' => $this->renderPartial('_form', array('model' => $model), true)
                )
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

		if(isset($_POST['AdminRole']))
		{
			$model->attributes=$_POST['AdminRole'];
			if($model->save()){
                if (Yii::app()->request->isAjaxRequest) {
                    exit(CJSON::encode(array(
                            'status' => 'success',
                            'message' => "AdminRole successfully saved"
                        )
                    ));

                } else
				$this->redirect(array('view','id'=>$model->id));
            }
		}

        if (Yii::app()->request->isAjaxRequest) {
            exit(CJSON::encode(array(
                    'status' => 'failure',
                    'form' => $this->renderPartial('_form', array('model' => $model), true)
                )
            )
            );
        } else
		$this->render('update',array(
			'model'=>$model,
		));
	}


    /**
     * Updates a particular model. in ajax mode
     * @param integer $id the ID of the model to be updated
     *  --------------------------------------------------
     *  use the application.extensions.formDialog2.FormDialog2 extension
     *  ajaxCreate functionality is almost using the same code (just change the $this->loadModel($id) to new AdminRole)
     */
    public function actionUpdateAjax($id)
	{
        $model=$this->loadModel($id);

		if(isset($_POST['AdminRole']))
		{
            $model->attributes=$_POST['AdminRole'];
			if($model->save()){
                if (Yii::app()->request->isAjaxRequest) {
                    exit(CJSON::encode(array(
                            'status' => 'success',
                            'message' => "AdminRole successfully saved"
                        )
                    ));

                } else
                      $this->redirect(array('view','id'=>$model->id));
            }
		}

          if (Yii::app()->request->isAjaxRequest) {
            exit(CJSON::encode(array(
                    'status' => 'failure',
                    'form' => $this->renderPartial('_form', array('model' => $model), true)
                    )
                  ));
            } else
            $this->render('update', array('model' => $model,));
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
		$dataProvider=new CActiveDataProvider('AdminRole');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new AdminRole('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['AdminRole']))
			$model->attributes=$_GET['AdminRole'];

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
		$model=AdminRole::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='admin-role-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

    /**
     * 批量删除动作 其他批处理 可以仿此
     *---------------------------------------------
     *  基本ajax返回结构定为： {status:"success/failure",
     *                           msg   :  "the operate result text",
     *                           data  :  "response to client"
     *                          }
     *---------------------------------------------
     */
    public function actionBatchDelete()
    {
        //  print_r($_POST);
        $request = Yii::app()->getRequest();
        if($request->getIsPostRequest()){
            if(isset($_POST['ids'])){
                $ids = $_POST['ids'];
            }
            if (empty($ids)) {
                echo CJSON::encode(array('status' => 'failure', 'msg' => '至少选择一项'));
                die();
            }
            //print_r($ids);
            if(is_string($ids)){
                $ids = explode(',',$ids);
            }
            $successCount = $failureCount = 0;
            foreach ($ids as $id) {
                $model = $this->loadModel($id);
                ($model->delete() == true) ? $successCount++ : $failureCount++;
            }
            echo CJSON::encode(array('status' => 'success',
                'data' => array(
                    'successCount' => $successCount,
                    'failureCount' => $failureCount,
                )));
            die();
        }else{
            throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
        }
    }

    //==============<batch update>===================================================================



    public function actionBatchUpdateAjax()
	{

        $model = new AdminRole;

		if(isset($_POST['AdminRole']))
		{
            $model->attributes=$_POST['AdminRole'];
			if($model->validate(array_keys($_POST['AdminRole']))){
                     $items=$this->getItemsToUpdate();
                    foreach($items as $i=>$item)
                    {
                        $item->attributes = $_POST['AdminRole'];
                        $item->save(false);// $item->save(); will run the validate function !
                    }
                    exit(CJSON::encode(array(
                            'status' => 'success',
                            'message' => "AdminRole successfully saved" // .print_r($_POST['Comment'],true),
                        )
                    ));
            }
		}

          if (Yii::app()->request->isAjaxRequest) {
            exit(CJSON::encode(array(
                    'status' => 'failure',
                    'form' => $this->renderPartial('batchUpdate', array('model' => $model), true)
                    )
                  ));
            } else
            $this->render('batchUpdate', array('model' => $model,));
	}

    /**
    * @see http://www.yiiframework.com/doc/guide/1.1/en/form.table
    */
    public  function getItemsToUpdate(){
        if(isset($_POST['ids'])){
            $ids = $_POST['ids'];
        }
        if (empty($ids)) {
            echo CJSON::encode(array('status' => 'failure', 'form' => '至少选择一项'));
            die();
        }
        //print_r($ids);
        if(is_string($ids)){
            $ids = explode(',',$ids);
        }
        $criteria = new CDbCriteria();
        $criteria->index = 'id';
        $criteria->addInCondition('id',$ids);
        $items = AdminRole::model()->findAll($criteria);

         return $items ;
    }

    //==============<batch update/>===================================================================
}
