<?php
/**
 * This is the template for generating a controller class file for CRUD feature.
 * The following variables are available in this template:
 * - $this: the BootCrudCode object
 */
?>
<?php echo "<?php\n"; ?>

class <?php echo $this->controllerClass; ?> extends <?php echo $this->baseControllerClass."\n"; ?>
{
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
        $model = $this->loadModel($id);

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
		$this->layout = '//layouts/blank';
		$model=new <?php echo $this->modelClass; ?>;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['<?php echo $this->modelClass; ?>']))
		{
			$model->attributes=$_POST['<?php echo $this->modelClass; ?>'];
			if($model->save()){
                if (Yii::app()->request->isAjaxRequest) {
                    $this->ajaxSuccess(
                        array(
                            'message' => "<?php echo $this->modelClass; ?> successfully added"
                        )
                    );
                    return ;
                } else
				$this->redirect(array('admin','id'=>$model-><?php echo $this->tableSchema->primaryKey; ?>));
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
		$this->layout = '//layouts/blank';
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['<?php echo $this->modelClass; ?>']))
		{
			$model->attributes=$_POST['<?php echo $this->modelClass; ?>'];
			if($model->save()){
                if (Yii::app()->request->isAjaxRequest) {
                    $this->ajaxSuccess(array(
                            'message' => "<?php echo $this->modelClass; ?> successfully saved"
                        )
                    );
                    return ;
                } else
				$this->redirect(array('admin','id'=>$model-><?php echo $this->tableSchema->primaryKey; ?>));
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
		$dataProvider=new CActiveDataProvider('<?php echo $this->modelClass; ?>');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
     * 性能提升技巧
     *@see http://www.yiiframework.com/wiki/520/how-to-avoid-rendering-entire-page-on-ajax-call-for-cgridview-and-clistview/
	 */
	public function actionAdmin()
	{
		$model=new <?php echo $this->modelClass; ?>('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['<?php echo $this->modelClass; ?>']))
			$model->attributes=$_GET['<?php echo $this->modelClass; ?>'];

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
		$model=<?php echo $this->modelClass; ?>::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model,$form='<?php echo $this->class2id($this->modelClass); ?>-form')
	{
		if(isset($_POST['ajax']) && $_POST['ajax']===$form)
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
            $models = $this->getItemsToUpdate();

            $successItems = $failureItems = array();

            foreach ($models as $model) {
                if($model->delete() == true){
                    $successItems[] = $model->primaryKey;
                }else{
                    $failureItems[] = $model->primaryKey;
                }
            }
            $this->ajaxSuccess(
                array(
                    'data' => array(
                        'successItems' => $successItems,
                        'failureItems' => $failureItems,
                    )
                )
            );
            return ;
        }else{
            throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
        }
    }

    //==============<batch update>===================================================================

    /**
    * @see http://www.yiiframework.com/doc/guide/1.1/en/form.table
    */
    public  function getItemsToUpdate($exitOnEmptySelection = true){
        if(isset($_POST['ids'])){
            $ids = $_POST['ids'];
        }
        if (empty($ids) && $exitOnEmptySelection ) {
            $this->ajaxFailure('至少选择一项');
            die();
        }
        //print_r($ids);
        if(is_string($ids)){
            $ids = explode(',',$ids);
        }
        $criteria = new CDbCriteria();
        $criteria->index = '<?php echo $this->tableSchema->primaryKey; ?>';
        $criteria->addInCondition('<?php echo $this->tableSchema->primaryKey; ?>',$ids);
        $items = <?php echo $this->modelClass; ?>::model()->findAll($criteria);

         return $items ;
    }

    //==============<batch update/>===================================================================
}
