<?php

class SysAlbumController extends BackendController
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

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
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('index', 'view'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update', $this->action->id),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete'),
                'users' => array('admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
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
        $this->onControllerAction(new ControllerActionEvent($this, $this->action->id, $model));

        $this->render('view', array(
            'model' => $model,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new SysAlbum;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['SysAlbum'])) {
            $model->attributes = $_POST['SysAlbum'];
            if ($model->save()) {
                if (Yii::app()->request->isAjaxRequest) {
                    $this->ajaxSuccess(
                        array(
                            'message' => "SysAlbum successfully added"
                        )
                    );
                    return;
                } else
                    $this->redirect(array('view', 'id' => $model->id));
            }
        }
        if (Yii::app()->request->isAjaxRequest) {
            $this->ajaxFailure(
                array(
                    'form' => $this->renderPartial('_form', array('model' => $model), true)
                )
            );
        } else
            $this->render('create', array(
                'model' => $model,
            ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        $this->onControllerAction(new ControllerActionEvent($this, $this->action->id, $model));

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['SysAlbum'])) {
            $model->attributes = $_POST['SysAlbum'];
            if ($model->save()) {
                if (Yii::app()->request->isAjaxRequest) {
                    $this->ajaxSuccess(array(
                            'message' => "SysAlbum successfully saved"
                        )
                    );
                    return;
                } else
                    $this->redirect(array('view', 'id' => $model->id));
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
            $this->render('update', array(
                'model' => $model,
            ));
    }


    /**
     * Updates a particular model. in ajax mode
     * @param integer $id the ID of the model to be updated
     *  --------------------------------------------------
     *  use the application.extensions.formDialog2.FormDialog2 extension
     *  ajaxCreate functionality is almost using the same code (just change the $this->loadModel($id) to new SysAlbum)
     */
    public function actionUpdateAjax($id)
    {
        $model = $this->loadModel($id);
        $this->onControllerAction(new ControllerActionEvent($this, $this->action->id, $model));

        if (isset($_POST['SysAlbum'])) {
            $model->attributes = $_POST['SysAlbum'];
            if ($model->save()) {
                if (Yii::app()->request->isAjaxRequest) {
                    $this->ajaxSuccess(
                        array(
                            'message' => "SysAlbum successfully saved"
                        )
                    );
                    return;
                } else
                    $this->redirect(array('view', 'id' => $model->id));
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
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $model = $this->loadModel($id);
            $this->onControllerAction(new ControllerActionEvent($this, $this->action->id, $model));

            $model->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $dataProvider = new CActiveDataProvider('SysAlbum');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new SysAlbum('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['SysAlbum']))
            $model->attributes = $_GET['SysAlbum'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id)
    {
        $model = SysAlbum::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'sys-album-form') {
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
        if ($request->getIsPostRequest()) {
            if (isset($_POST['ids'])) {
                $ids = $_POST['ids'];
            }
            if (empty($ids)) {
                echo CJSON::encode(array('status' => 'failure', 'msg' => '至少选择一项'));
                return;
            }
            //print_r($ids);
            if (is_string($ids)) {
                $ids = explode(',', $ids);
            }
            $successCount = $failureCount = 0;

            $criteria = new CDbCriteria();
            $criteria->index = 'id';
            $criteria->addInCondition('id', $ids);
            $models = SysAlbum::model()->findAll($criteria);
            $this->onControllerAction(new ControllerActionEvent($this, $this->action->id, $models));

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
            return;
        } else {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
    }

    //==============<batch update>===================================================================


    public function actionBatchUpdateAjax()
    {

        $model = new SysAlbum;

        if (isset($_POST['SysAlbum'])) {
            $model->attributes = $_POST['SysAlbum'];
            if ($model->validate(array_keys($_POST['SysAlbum']))) {
                $items = $this->getItemsToUpdate();
                $this->onControllerAction(new ControllerActionEvent($this, $this->action->id, $items));

                foreach ($items as $i => $item) {
                    $item->attributes = $_POST['SysAlbum'];
                    $item->save(false); // $item->save(); will run the validate function !
                }
                $this->ajaxSuccess(
                    array(
                        'message' => "SysAlbum successfully saved" // .print_r($_POST['Comment'],true),
                    )
                );
                return;
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
    public function getItemsToUpdate()
    {
        if (isset($_POST['ids'])) {
            $ids = $_POST['ids'];
        }
        if (empty($ids)) {
            echo CJSON::encode(array('status' => 'failure', 'form' => '至少选择一项'));
            die();
        }
        //print_r($ids);
        if (is_string($ids)) {
            $ids = explode(',', $ids);
        }
        $criteria = new CDbCriteria();
        $criteria->index = 'id';
        $criteria->addInCondition('id', $ids);
        $items = SysAlbum::model()->findAll($criteria);

        return $items;
    }

    //==============<batch update/>===================================================================

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionUpload()
    {
        Yii::setPathOfAlias('xupload', Yii::getPathOfAlias('ext.xupload'));


        Yii::import("xupload.models.XUploadForm");
        $model = new XUploadForm;

        // 其实获取一个目录别名即可 另一个可以跟第一个共用一个别名！
        $templateDownload = $this->getViewFile('xupload/tmpl-download');
        Yii::setPathOfAlias('tplDir', dirname($templateDownload));

        /*  var_dump(array(
              $templateDownload,
              $templateUpload,
          )); die(__METHOD__);
           */

        $this->render('xupload/upload2', array(
            'model' => $model,
            'albumId'=>Yii::app()->request->getParam('albumId'),
        ));
      //  echo YsUploadStorage::instance()->getBaseUrl();
        //echo Ys::thumbUrl('/jj/jj.jpg',200,200,'jpg');
      //  echo bu();
    }

    /**
     * 专门处理上传跟文件删除！
     * @throws CHttpException
     */
    public function actionHandleUpload()
    {
        header('Vary: Accept');
        if (isset($_SERVER['HTTP_ACCEPT']) && (strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)) {
            header('Content-type: application/json');
        } else {
            header('Content-type: text/plain');
        }

        if (isset($_GET["_method"])) {
            if ($_GET["_method"] == "delete") {
                $storage = YsUploadStorage::instance();

                $fileUri = $_GET["file"] ;
               // $success = is_file($_GET["file"]) && $_GET["file"][0] !== '.' && $storage->deleteFile($_GET["file"]);
                $success =  $storage->deleteFile($fileUri);
                if($success){
                    // 文件被删除了那么需要记录到删除表中 这样便于用计划任务来删除小图
                    $dynamicAr = DynamicActiveRecord::forTable('sys_image_deleted');
                    $dynamicAr->file_uri = $fileUri ;
                    $dynamicAr->create_time = time() ;
                    // 这个看系统准备用什么了 比如 fs-fileSystem  ， fastDfs ， .....^-^ ;
                    $dynamicAr->storage_type = 'fs';
                    $dynamicAr->save();

                    // 删除图片表 及其跟相册表的关联
                  $sysPhoto =   SysPhoto::model()->findByAttributes(array(
                        'uri'=>$fileUri,
                    ));
                    if($sysPhoto != null){
                        $photoId = $sysPhoto->primaryKey ;
                        $albumId = $_GET['albumId'] ;
                        if($sysPhoto->delete() ){
                           DynamicActiveRecord::forTable('sys_album_object')->deleteAllByAttributes(array(
                              'id_album'=>$albumId,
                               'id_object'=>$photoId ,
                           ));
                            /*
                            // 相册数量递减
                            SysAlbum::model()->updateCounters(array(
                                'obj_count'=>-1,
                            ),
                                'id=:albumId',
                                array(
                                    ':albumId'=>$albumId,
                                )
                            );
                            */
                            // 如果被删除的图片时相册的封面那么还要更新下？。。。要死人啦
                            $album = SysAlbum::model()->findByPk($albumId);
                            if($album->cover_uri == $fileUri){
                                // 置空 或者默认图
                                $album->cover_uri = '';
                            }
                            $album->obj_count = new CDbExpression('obj_count -1 ');
                            $album->save(false);
                        }
                    }



                }
                echo json_encode($success);
            }
        } else {
            $this->init();
            Yii::setPathOfAlias('xupload', Yii::getPathOfAlias('ext.xupload'));
            Yii::import("xupload.models.XUploadForm");
            $model = new XUploadForm;

            //  $model = new Image;//Here we instantiate our model

            //We get the uploaded instance
            $uploadFile = CUploadedFile::getInstance($model, 'file');
            $model->file = $uploadFile;
            if ($model->file !== null) {
                // $model->mime_type = $model->file->getType();
                $model->mime_type = CFileHelper::getMimeType($uploadFile->getTempName());
                $model->size = $model->file->getSize();
                $model->name = $model->file->getName();
                //Initialize the ddditional Fields, note that we retrieve the
                //fields as if they were in a normal $_POST array

                // 这里是额外的信息！
                //  $model->title = Yii::app()->request->getPost('title', '');
                //  $model->description  = Yii::app()->request->getPost('description', '');

                if ($model->validate()) {
                    $albumId = $_GET['albumId'];
                    $album = SysAlbum::model()->findByPk($albumId,array(
                        // 一直不明白要最后一个成语的id管毛用 莫非要用ajax加载这个成员？
                        'select'=>'id,cover_uri,obj_count,last_obj_id'
                    ));

                    //=========================================================================\\
                    $storage = YsUploadStorage::instance();

                    /*
                    $path = Yii::app()->getBasePath() . "/../images/uploads";
                    $publicPath = Yii::app()->getBaseUrl() . "/images/uploads";
                    if (!is_dir($path)) {
                        mkdir($path, 0777, true);
                        chmod($path, 0777);
                    }
                    */
                    $model->file = $storage->upload($model->file);
                    // chmod($path . $model->name, 0777);

                    // 如果上传成功了那么数据存放到图片表去 同时桥表做关联
                    $sysPhoto = new SysPhoto();
                    $sysPhoto->status = 'approved';

                    $sysPhoto->uri = $model->file ;
                    $sysPhoto->hash = md5($model->file);
                    // 上传照片可以同时填写照片的标题和描述 如果不填默认为图片名称！
                    //  默认值基本无效！ 内部是isset($_POST['xxxx'])
                    $sysPhoto->title =  Yii::app()->request->getPost('title', '');
                    $sysPhoto->desc =   Yii::app()->request->getPost('description','') ;
                    if(empty($sysPhoto->title)){
                        $sysPhoto->title = $uploadFile->name ;
                    }
                    if(empty($sysPhoto->desc)){
                        $sysPhoto->desc = $uploadFile->name ;
                    }


                    $sysPhoto->ext = $uploadFile->getExtensionName();
                    $sysPhoto->mime_type = $model->mime_type;
                    $sysPhoto->size = $uploadFile->getSize() ;

                    $debugMsg = array();
                    if($sysPhoto->save()){

                        // 动态AR 的好处是不需要用Gii生模型 但同时还能够拥有常规AR的功能！
                        $dynamicAr = DynamicActiveRecord::forTable('sys_album_object');
                        $dynamicAr->id_object = $sysPhoto->primaryKey ;
                        $dynamicAr->id_album = $albumId ;
                        $dynamicAr->obj_order = 0 ;
                        if(!$dynamicAr->save()){
                            $debugMsg = $dynamicAr->getErrors();
                        }
                        //..............................................................\\
                        // 如果 相册的封面没设置那么把自己搞上 同时更新下相册相关的信息：

                        if(empty($album->cover_uri)){
                            $album->cover_uri = $model->file ;
                        }
                        /*
                        if(empty($album->obj_count)){
                            $album->obj_count = 1 ;
                        }else{
                            $album->obj_count += 1 ;
                        }
                        */
                        $album->obj_count = new CDbExpression('obj_count +1 ');
                        $album->last_obj_id = $sysPhoto->primaryKey ;
                        if(!$album->save(false)){
                            $debugMsg = $album->getErrors();
                        }
                        //..............................................................//

                    }else{
                        $debugMsg = $sysPhoto->getErrors();
                    }


                    //Now we return our json
                    echo json_encode(array(array(
                        // 这个只在调试下输出
                        'debugMsgs'=> YII_DEBUG ? $debugMsg :'',

                        "name" => $model->name,
                        "type" => $model->mime_type,
                        "size" => $model->size,
                        //Add the title
                        //  "title" => $model->title,
                        //And the description
                        //   "description" => $model->description,

                        "url" => $storage->getUrl($model->file),
                        "thumbnail_url" =>  Ys::thumbUrl($model->file,200,200,$uploadFile->getExtensionName()),

                        // 删除上传图片的配置
                        "delete_url" => $this->createUrl($this->action->id, array(
                                "_method" => "delete",
                                "file" =>$model->file,
                                'albumId'=>$albumId ,
                            )),
                        "delete_type" => "POST"
                    )));
                } else {
                    echo json_encode(array(array("error" => $model->getErrors('file'),)));
                    Yii::log("XUploadAction: " . CVarDumper::dumpAsString($model->getErrors()), CLogger::LEVEL_ERROR, "xupload.actions.XUploadAction");
                }
            } else {
                throw new CHttpException(500, "Could not upload file");
            }
        }
    }
}
