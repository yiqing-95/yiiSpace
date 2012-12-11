<?php
/**
 * 上传如果还想在dialog中进行
 * 使用quickDialog 扩展来做 ！
 */
class PhotoAlbumController extends BasePhotoController
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

    protected  function beforeAction( $action)
    {

        switch ($action->id) {
            case 'create':
            case 'update':
            case 'manage':
            case 'my':
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
        unset($_GET['id']);
        $_GET['album'] = $id ;
        $this->forward('photo/member');
        /*
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
        */
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new PhotoAlbum;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['PhotoAlbum'])) {
            $model->attributes = $_POST['PhotoAlbum'];
            if ($model->save()) {
                if (Yii::app()->request->isAjaxRequest) {
                    exit(CJSON::encode(array(
                            'status' => 'success',
                            'message' => "PhotoAlbum successfully added"
                        )
                    ));

                } else
                    $this->redirect(array('view', 'id' => $model->id));
            }
        }

        $model->uid = user()->getId();

        if (Yii::app()->request->isAjaxRequest) {
            exit(CJSON::encode(array(
                    'status' => 'failure',
                    'form' => $this->renderPartial('_form', array('model' => $model), true)
                )
            )
            );

        } else
            $this->render('create', array('model' => $model,));

    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        if($model->uid !== UserHelper::getVisitorId()){
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['PhotoAlbum'])) {
            $model->attributes = $_POST['PhotoAlbum'];
            if ($model->save()) {
                if (Yii::app()->request->isAjaxRequest) {
                    exit(CJSON::encode(array(
                            'status' => 'success',
                            'message' => "PhotoAlbum successfully saved"
                        )
                    ));

                } else
                    $this->redirect(array('view', 'id' => $model->id));
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
            $this->loadModel($id)->delete();

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
        $dataProvider = new CActiveDataProvider('PhotoAlbum');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Lists all models.for current login user
     */
    public function actionMy()
    {
        //$this->layout = YsHelper::getUserSpaceLayout();
        $dataProvider = new CActiveDataProvider('PhotoAlbum');
        $this->render('member', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Lists all models.for space user
     */
    public function actionMember()
    {
        $this->layout = YsHelper::getUserSpaceLayout();

        if(!isset($_GET['u'])){
            $_GET['u'] = user()->getId();
        }

        $dataProvider = new CActiveDataProvider('PhotoAlbum');
        $criteria = $dataProvider->getCriteria();
        $criteria->addColumnCondition(array(
           'uid'=>UserHelper::getSpaceOwnerId(),
        ));
       // $criteria->order = 'update_time DESC';
        $criteria->order = 'id DESC';

        $dataProvider->getPagination()->setPageSize(6);

        $this->render('member', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new PhotoAlbum('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['PhotoAlbum']))
            $model->attributes = $_GET['PhotoAlbum'];

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
        $model = PhotoAlbum::model()->findByPk($id);
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
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'photo-album-form') {
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
        } else {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
    }
}
