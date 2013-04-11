<?php
/**
 * 考虑了大数据问题 所以一张新闻表分两张了
 * 一个实体 在list 时或者查询时 被用到的字段可以分到一个表
 * 而在单独显示且不长用到listView 环境下的字段分到另一个表
 * 带来了一定的复杂性 需要同时增删改两个或者多个实体
 * .......................................
 * 批修改暂时没有搞 涉及多个model gii生成的只适合主实体
 * ---------------------------------------
 * 多model同视图wiki 参考：http://www.yiiframework.com/wiki/19/how-to-use-a-single-form-to-collect-data-for-two-or-more-models
 * 最好用事务来做 但本次实现没有使用事务！
 * don't save any data until all validations are OK...
 * ---------------------------------------
 * Class NewsEntryController
 *
 */
class NewsEntryController extends BackendController
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
        $model =  NewsEntry::model()->with('post')->findByPk($id); //$this->loadModel($id);
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
		$model=new NewsEntry;

        $newsPost = new NewsPost();
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['NewsEntry'],$_POST['NewsPost']))
		{
			$model->attributes=$_POST['NewsEntry'];
            $newsPost->attributes = $_POST['NewsPost'];

            // validate BOTH $a and $b
            $valid = $model->validate();
            $valid = $newsPost->validate() && $valid;

			if($valid){
                // 不必要再次验证了
                $model->save(false);
                $newsPost->nid = $model->id;
                $newsPost->save(false) ;

                if (Yii::app()->request->isAjaxRequest) {
                    $this->ajaxSuccess(
                        array(
                            'message' => "NewsEntry successfully added"
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
                            'form' => $this->renderPartial('_form', array(
                                'model' => $model,
                                'newsPost'=>$newsPost,
                            ), true)
                        )
                );
        } else
		$this->render('create',array(
			'model'=>$model,
            'newsPost'=>$newsPost ,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=  NewsEntry::model()->with('post')->findByPk($id); //$this->loadModel($id);
        $newsPost = $model->post;

        $this->onControllerAction(new ControllerActionEvent($this,$this->action->id,$model));

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['NewsEntry'],$_POST['NewsPost']))
		{
			$model->attributes=$_POST['NewsEntry'];

            $newsPost->attributes = $_POST['NewsPost'];

            $validate = $model->validate();
            $validate = $validate && $newsPost->validate();

			if($validate){
                $model->save(false) ;
                $newsPost->save(false);

                if (Yii::app()->request->isAjaxRequest) {
                    $this->ajaxSuccess(array(
                            'message' => "NewsEntry successfully saved"
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
                         'form' => $this->renderPartial('_form', array('model' => $model,'newsPost'=>$newsPost), true)
                    )
            );
        } else
		$this->render('update',array(
			'model'=>$model,
            'newsPost'=>$model->post,
		));
	}


    /**
     * Updates a particular model. in ajax mode
     * @param integer $id the ID of the model to be updated
     *  --------------------------------------------------
     *  use the application.extensions.formDialog2.FormDialog2 extension
     *  ajaxCreate functionality is almost using the same code (just change the $this->loadModel($id) to new NewsEntry)
     */
    public function actionUpdateAjax($id)
	{
        $model=  NewsEntry::model()->with('post')->findByPk($id); //$this->loadModel($id);
        $newsPost = $model->post ;

        $this->onControllerAction(new ControllerActionEvent($this,$this->action->id,$model));

		if(isset($_POST['NewsEntry']))
		{
            $model->attributes=$_POST['NewsEntry'];

            $newsPost->attributes = $_POST['NewsPost'];

            if($model->validate() && $newsPost->validate()){
                $model->save();
                $newsPost->save();

                if (Yii::app()->request->isAjaxRequest) {
                    $this->ajaxSuccess(
                        array(
                            'message' => "NewsEntry successfully saved"
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
                            'form' => $this->renderPartial('_form', array('model' => $model,'newsPost'=>$newsPost), true)
                        )
                );

            } else
            $this->render('update', array('model' => $model,'newsPost'=>$newsPost));
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
		$dataProvider=new CActiveDataProvider('NewsEntry');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new NewsEntry('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['NewsEntry']))
			$model->attributes=$_GET['NewsEntry'];

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
		$model=NewsEntry::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='news-entry-form')
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
            $models = NewsEntry::model()->findAll($criteria);
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

        $model = new NewsEntry;

		if(isset($_POST['NewsEntry']))
		{
            $model->attributes=$_POST['NewsEntry'];
			if($model->validate(array_keys($_POST['NewsEntry']))){
                    $items=$this->getItemsToUpdate();
                    $this->onControllerAction(new ControllerActionEvent($this,$this->action->id,$items));

                    foreach($items as $i=>$item)
                    {
                        $item->attributes = $_POST['NewsEntry'];
                        $item->save(false);// $item->save(); will run the validate function !
                    }
                    $this->ajaxSuccess(
                        array(
                            'message' => "NewsEntry successfully saved" // .print_r($_POST['Comment'],true),
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
        $items = NewsEntry::model()->findAll($criteria);

         return $items ;
    }

    //==============<batch update/>===================================================================
}
