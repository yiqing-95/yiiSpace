<?php

class SysAudioController extends BackendController
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
            array(
                'ext.yiiext.filters.forgerySession.EForgerySessionFilter + create,sampleUpload',
                // the name of the parameter that stores session id.
                'paramName' => Yii::app()->session->sessionName,
                // the method which sent the data. This should be either 'POST', 'GET' or 'AUTO'.
                'method' => 'POST',
            ),
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

        if (isset($_POST['SysAudio'])) {
            $model->attributes = $_POST['SysAudio'];
            if ($model->save()) {
                if (Yii::app()->request->isAjaxRequest) {
                    $this->ajaxSuccess(array(
                            'message' => "SysAudio successfully saved"
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
     *  ajaxCreate functionality is almost using the same code (just change the $this->loadModel($id) to new SysAudio)
     */
    public function actionUpdateAjax($id)
    {
        $model = $this->loadModel($id);
        $this->onControllerAction(new ControllerActionEvent($this, $this->action->id, $model));

        if (isset($_POST['SysAudio'])) {
            $model->attributes = $_POST['SysAudio'];
            if ($model->save()) {
                if (Yii::app()->request->isAjaxRequest) {
                    $this->ajaxSuccess(
                        array(
                            'message' => "SysAudio successfully saved"
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
     * Lists all models.
     */
    public function actionIndex()
    {
        $dataProvider = new CActiveDataProvider('SysAudio');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new SysAudio('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['SysAudio']))
            $model->attributes = $_GET['SysAudio'];

        if(isset($_GET['albumId'])){
          //  $model->albumId = $_GET['albumId'];
            $albumId = $_GET['albumId'];
            $model
                ->with(array(
                    'albumObject'
                ))
                ->getDbCriteria()
                ->addColumnCondition(array(
                    'albumObject.id_album' => $albumId,
                ));
            $model->getDbCriteria()->params[':id_album'] = $albumId ;
        }

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param $id
     * @param array $with
     * @throws CHttpException
     * @internal param \the $integer ID of the model to be loaded
     * @return SysAudio
     */
    public function loadModel($id,$with=array())
    {
        if(!empty($with)){
            $model = SysAudio::model()->with($with)->findByPk($id);
        }else{
            $model = SysAudio::model()->findByPk($id);
        }

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
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'sys-audio-form') {
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
            $models = SysAudio::model()->findAll($criteria);
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

    /**
     * 这个方法是很重要的 在sns中有些参数一直要跟随下去
     *
     * @param string $route
     * @param array $params
     * @param string $ampersand
     * @return string
     */
    public function createUrl($route,$params=array(),$ampersand='&')
    {
        // 注入相册id
        if(isset($_GET['albumId'])){
            $params = array_merge(array('albumId'=>$_GET['albumId']),$params);
        }
       return parent::createUrl($route,$params,$ampersand);
    }


    public function actionBatchUpdateAjax()
    {

        $model = new SysAudio;

        if (isset($_POST['SysAudio'])) {
            $model->attributes = $_POST['SysAudio'];
            if ($model->validate(array_keys($_POST['SysAudio']))) {
                $items = $this->getItemsToUpdate();
                $this->onControllerAction(new ControllerActionEvent($this, $this->action->id, $items));

                foreach ($items as $i => $item) {
                    $item->attributes = $_POST['SysAudio'];
                    $item->save(false); // $item->save(); will run the validate function !
                }
                $this->ajaxSuccess(
                    array(
                        'message' => "SysAudio successfully saved" // .print_r($_POST['Comment'],true),
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
        $items = SysAudio::model()->findAll($criteria);

        return $items;
    }

    //==============<batch update/>===================================================================

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        // die(__METHOD__);
        $model = new SysAudio;

        // 给些默认值
        $model->name = '名称';
        $model->uid = user()->getId();

        if (isset($_GET['albumId'])) {
            $model->albumId = $_GET['albumId'];
        }
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['SysAudio'])) {
            $model->attributes = $_POST['SysAudio'];

            $model->uri = $uploadFile = CUploadedFile::getInstanceByName('Filedata');
            if ($model->validate()) {

                //--------------------------------------------\\
                // Yii app的end方法使用了 register_shutdown 注册了 所以总会被最终调用的注册监听endRequest的callabe总被调用！
                // 这个是用来hack 日志问题！ 不需要debug信息的输出 特别是yii-debug-toolbar 组件！
                ob_start();
                Yii::app()->end(0, false);
                ob_end_clean();
                //--------------------------------------------//

                $storage = YsUploadStorage::instance();
                $model->uri = $storage->upload($uploadFile);


                $model->file_size = $uploadFile->getSize() ;

                if ($model->save(false)) {

                    echo CJSON::encode(
                        array(
                            'error' => false,
                            'fileUrl' => $storage->getUrl($model->uri),
                            'msg'=>$model->getErrors(),
                            'forward'=>$this->createUrl('admin',array('albumId'=>$model->albumId)),
                        )
                    );
                    die();
                }


            }else{
                echo CJSON::encode(array(
                   'error'=>true ,
                    'msg'=>$model->getErrors(),
                ));
                die();
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    //........................................................................\\
    /**
     * @Desc(测试swfUpload上传)
     * 图片预览要关闭debug模式 Yii-debug-Toolbar 会干扰输出！
     */
    public function actionSampleUpload()
    {
        try {
            // get the file
            $picture_file = CUploadedFile::getInstanceByName('Filedata');
            if (!$picture_file || $picture_file->getHasError()) {
                echo 'Error: Documento Invalido';
                Yii::app()->end();
            }

            //--------------------------------------------\\
            // Yii app的end方法使用了 register_shutdown 注册了 所以总会被最终调用的注册监听endRequest的callabe总被调用！
            // 这个是用来hack 日志问题！ 不需要debug信息的输出 特别是yii-debug-toolbar 组件！
            ob_start();
            Yii::app()->end(0, false);
            ob_end_clean();
            //--------------------------------------------//


            // remember the post params?
            // $yourvar = Yii::app()->request->getParam('yourvarname');
            $picture_name = $picture_file->name;
            //
            // I normally here I use PhpThumb Library instead of this
            // make sure 'thepathyousavethefile' is a writable path
            $picture_file->saveAs(
                Yii::getPathOfAlias('webroot.images') . DIRECTORY_SEPARATOR .
                $picture_name
            );
            // Return the file id to the script
            // This will display the thumbnail of the uploaded file to the view
            echo "FILEID:" . Yii::app()->getBaseUrl() .
                '/images/' .
                $picture_name;
            // 调试模式下 输出的debug信息 会干扰客户端显示图片的！
            exit();
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
        eixt();
    }
    //........................................................................//


    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     * @throws CHttpException
     */
    public function actionDelete($id)
    {
        if (Yii::app()->request->isPostRequest) {

            $albumObjConf = array() ;
            if(isset($_GET['albumId'])){
                $albumObjConf = array(
                    'condition'=>'id_album=:id_album',
                    'params'=>array(
                        ':id_album'=>$_GET['albumId'],
                    ),
                );
            }
            // we only allow deletion via POST request
            $model = $this->loadModel($id,array(
                'albumObject'=> $albumObjConf,
                'album',
            ));

            $model->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

}
