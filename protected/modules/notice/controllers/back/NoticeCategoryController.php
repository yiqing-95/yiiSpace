<?php

class NoticeCategoryController extends BackendController
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

    public function actions()
    {
        return parent::actions() + array(
            'sortable' => array(
                'class'     => 'bootstrap.actions.TbSortableAction',
                'modelName' => 'NoticeCategory'
            ),
            'order' => array(
                'class' => 'ext.OrderColumn.OrderAction',
                'modelClass' => 'NoticeCategory',
                'pkName'  => 'id',
            ),
        );
    }

    /**
     * @see http://x-editable.demopage.ru/index.php?r=site/components#EditableSaver
     * @see https://github.com/vitalets/x-editable-yii.git
     */
    public function actionUpdateOrder()
    {
        Yii::import('ext.XEditable.*');
        $es = new EditableSaver('NoticeCategory');
        try {
            /*
            $es->onBeforeUpdate = function($event) {
                $event->sender->setAttribute('updated_at', date('Y-m-d H:i:s'));
            };
            $es->onBeforeUpdate = function($event) {
                if(Yii::app()->user->isGuest) {
                    $event->sender->error('You are not allowed to update data');
                }
            };*/
            $es->update();
        } catch(CException $e) {
            echo CJSON::encode(array('success' => false, 'msg' => $e->getMessage()));
            return;
        }
        echo CJSON::encode(array('success' => true));
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
        $model = $this->loadModel($id);
        $this->onControllerAction(new ControllerActionEvent($this,$this->action->id,$model));

		$this->render('view',array(
			'model'=>$model,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new NoticeCategory;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['NoticeCategory']))
		{
			$model->attributes=$_POST['NoticeCategory'];
			if($model->save()){
                if (Yii::app()->request->isAjaxRequest) {
                    $this->ajaxSuccess(
                        array(
                            'message' => "NoticeCategory successfully added"
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

        $this->onControllerAction(new ControllerActionEvent($this,$this->action->id,$model));

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['NoticeCategory']))
		{
			$model->attributes=$_POST['NoticeCategory'];
			if($model->save()){
                if (Yii::app()->request->isAjaxRequest) {
                    $this->ajaxSuccess(array(
                            'message' => "NoticeCategory successfully saved"
                        )
                    );
                    return ;
                } else
				$this->redirect(array('view','id'=>$model->id));
            }
		}

        if (Yii::app()->request->isAjaxRequest) {
            $this->ajaxSuccess(
                   array(
                         'status' => 'failure',
                         'form' => $this->renderPartial('_form', array('model' => $model), true)
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
     *  ajaxCreate functionality is almost using the same code (just change the $this->loadModel($id) to new NoticeCategory)
     */
    public function actionUpdateAjax($id)
	{
        $model=$this->loadModel($id);
        $this->onControllerAction(new ControllerActionEvent($this,$this->action->id,$model));

		if(isset($_POST['NoticeCategory']))
		{
            $model->attributes=$_POST['NoticeCategory'];
			if($model->save()){
                if (Yii::app()->request->isAjaxRequest) {
                    $this->ajaxSuccess(
                        array(
                            'message' => "NoticeCategory successfully saved"
                        )
                    );
                    return ;
                } else
                      $this->redirect(array('view','id'=>$model->id));
            }
		}

          if (Yii::app()->request->isAjaxRequest) {
                $this->ajaxSuccess(
                        array(
                            'status' => 'failure',
                            'form' => $this->renderPartial('_form', array('model' => $model), true)
                        )
                );

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
            $model = $this->loadModel($id);
            $this->onControllerAction(new ControllerActionEvent($this,$this->action->id,$model));

            $model->delete();

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
		$dataProvider=new CActiveDataProvider('NoticeCategory');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new NoticeCategory('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['NoticeCategory']))
			$model->attributes=$_GET['NoticeCategory'];

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
		$model=NoticeCategory::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='notice-category-form')
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
               return ;
            }
            //print_r($ids);
            if(is_string($ids)){
                $ids = explode(',',$ids);
            }
            $successCount = $failureCount = 0;

            $criteria = new CDbCriteria();
            $criteria->index = 'id';
            $criteria->addInCondition('id',$ids);
            $models = NoticeCategory::model()->findAll($criteria);
            $this->onControllerAction(new ControllerActionEvent($this,$this->action->id,$models));

            foreach ($models as $model) {
                ($model->delete() == true) ? $successCount++ : $failureCount++;
            }
            $this->ajaxSuccess(
                array(
                    'data' => array(
                        'successCount' => $successCount,
                        'failureCount' => $failureCount,
                    )
                )
            );
            return ;
        }else{
            throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
        }
    }

    //==============<batch update>===================================================================



    public function actionBatchUpdateAjax()
	{

        $model = new NoticeCategory;

		if(isset($_POST['NoticeCategory']))
		{
            $model->attributes=$_POST['NoticeCategory'];
			if($model->validate(array_keys($_POST['NoticeCategory']))){
                    $items=$this->getItemsToUpdate();
                    $this->onControllerAction(new ControllerActionEvent($this,$this->action->id,$items));

                    foreach($items as $i=>$item)
                    {
                        $item->attributes = $_POST['NoticeCategory'];
                        $item->save(false);// $item->save(); will run the validate function !
                    }
                    $this->ajaxSuccess(
                        array(
                            'message' => "NoticeCategory successfully saved" // .print_r($_POST['Comment'],true),
                        )
                    );
                return ;
            }
		}

          if (Yii::app()->request->isAjaxRequest) {
               $this->ajaxSuccess(
                        array(
                            'status' => 'failure',
                            'form' => $this->renderPartial('batchUpdate', array('model' => $model), true)
                        )
               );
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
        $items = NoticeCategory::model()->findAll($criteria);

         return $items ;
    }

    //==============<batch update/>===================================================================
}
