<?php

class PhotoController extends BasePhotoController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';


    protected  function beforeAction( $action){

        switch ($action->id) {
            case 'create':
            case 'update':
            case 'manage':
                $this->layout = YsHelper::getUserCenterLayout();
                break;
            default:
                ;
        }
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
        $this->layout = YsHelper::getUserSpaceLayout(true);

        $spaceOwnerId = $_GET['u'];
        $albumId = $_GET['aid'];

        $allPhotosOfAlbum = Photo::model()->findAll(array(
            'index'=>'id',
            'order'=>'id DESC',
            'condition'=> 'uid=:uid AND album_id=:aid',
            'params'=>array(':uid'=>$spaceOwnerId,':aid'=>$albumId),
        ));

        if(!isset($allPhotosOfAlbum[$id])){
            throw new CHttpException(404,'The requested page does not exist.');
        }

        $this->render('view',array(
            'photos'=>$allPhotosOfAlbum,
            'model' => $allPhotosOfAlbum[$id],
        ));

        // 点击量：
        YsViewSystem::doView('photo',$id);
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
		$model=new Photo;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Photo']))
		{
			$model->attributes=$_POST['Photo'];
			if($model->validate()){
                //=================================================================
                // THIS is how you capture those uploaded images: remember that in your CMultiFile widget, you set 'name' => 'images'
                $images = CUploadedFile::getInstancesByName('images');

                $storage = YsUploadStorage::instance();
                // proceed if the images have been set
                if (isset($images) && count($images) > 0) {
                    // go through each uploaded image
                    foreach ($images as $image => $pic) {
                        // echo $pic->name.'<br />';
                        // $localTempFile = $pic->getTempName();
                        $extName = $pic->getExtensionName();

                         $saveToPath = $storage->getSaveToPath(user()->getId()).".{$extName}";
                        if ($pic->saveAs($saveToPath)) {

                            // add it to the main model now
                            $photo = new Photo();
                            $photo->uid = user()->getId();

                            $photo->orig_path = WebUtil::getRelativeUrl($saveToPath);
                            //==================================================
                           // $smallImageSpec = array(220, 220);
                           // $mediumImageSpec = array(800,800);
                           // 这里应该用图像处理底层函数 先获取下上传图片大小 如果比较小
                            // 可以不用缩放
                            $phpThumb = AppComponent::phpThumb()->create($saveToPath);
                            //$phpThumb->resize(550,800);
                            $phpThumb->resize(550,550);
                            $thumbImagePath = $storage->getSaveToPath(user()->id) . ".{$extName}";
                            $phpThumb->save($thumbImagePath);
                            $photo->path = WebUtil::getRelativeUrl($thumbImagePath);
                            //===================================================

                            $photo->title = $model->title;
                            $photo->desc = $model->desc ;
                            $photo->album_id = $model->album_id ;
                            $photo->save(); // DONE
                        } else {
                            // handle the errors here, if you want

                        }
                    }
                    $this->redirect(array('/album/view','u'=>user()->id,'id'=>$model->album_id));
                } else {
                    $model->addError('path', '请选择至少一个文件哦！');
                }
                //=================================================================
            }

		}

        $model->uid = user()->getId();

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

		if(isset($_POST['Photo']))
		{
			$model->attributes=$_POST['Photo'];
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
		$dataProvider=new CActiveDataProvider('Photo');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

    /**
     * Lists all models.
     */
    public function actionMember()
    {
        $this->layout = YsHelper::getUserSpaceLayout(true);
        $dataProvider=new CActiveDataProvider('Photo');

        $criteria = $dataProvider->getCriteria();
        $criteria->addColumnCondition(array(
            'uid'=>UserHelper::getSpaceOwnerId(),
        ));
        if(isset($_GET['album'])){
            $criteria->addColumnCondition(array(
                'album_id'=>$_GET['album'],
            ));

        }
        // $criteria->order = 'update_time DESC';
        $criteria->order = 'id DESC';

        $dataProvider->getPagination()->setPageSize(6);

        $this->render('index',array(
            'dataProvider'=>$dataProvider,
        ));
    }


    /**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Photo('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Photo']))
			$model->attributes=$_GET['Photo'];

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
		$model=Photo::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='photo-form')
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

    public function actionTest($id=''){
        $this->render('/test/test'.$id);
    }
}
