<?php

class StatusController extends BaseStatusController
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    //public $layout='//layouts/iframe';

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
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('index', 'view'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update',$this->action->id),
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
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new Status;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Status'])) {
            if (isset($_POST['status_type']) && $_POST['status_type'] != 'update') {

                if ($_POST['status_type'] == 'image') {
                    $model = new StatusImage();
                    $model->processImage('image_file');

                } elseif ($_POST['status_type'] == 'video') {
                    $model = new StatusVideo();
                    $model->setVideoIdFromURL($_POST['video_url']);
                } elseif ($_POST['status_type'] == 'link') {
                    $model = new StatusLink();
                    $model->setURL($_POST['link_url']);
                    $model->setDescription($_POST['link_description']);
                }
            }

            $model->attributes = $_POST['Status'];
            $model->generateType();

            //echo   YiiUtil::getPathOfClass($model) ;  die(__METHOD__);
            if ($model->save()){
                //$this->redirect(array('view', 'id' => $model->id));
                echo CHtml::script('parent.refreshListOrGridView();');
                exit;
            }

        }

        $loggedInUser = Yii::app()->user->id;
        $user = isset($_GET['u']) ? $_GET['u'] : $loggedInUser;
        if(isset($_GET['u'])){
           $this->layout = '//layouts/user/user_space';
        }else{
            $this->layout = '//layouts/user/user_center';
        }

        if ($loggedInUser == $user) {
               $model->profile = $loggedInUser;
        } else {
            $connections = RelationShip::getNetwork($user, false);
            if (in_array($loggedInUser, $connections)) {
                $model->profile = $user ;

            } else {
                throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.  ');
            }
        }

        $model->creator = $loggedInUser;

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

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Status'])) {
            $model->attributes = $_POST['Status'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     * @throws CHttpException
     * @return void
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
        $dataProvider = new CActiveDataProvider('Status');

        $user = isset($_GET['u']) ?$_GET['u']: Yii::app()->user->id ;

        $dataProvider->criteria = array(
            'condition'=>"profile={$user}" ,
        );


        if(Yii::app()->request->getIsAjaxRequest()){
            $this->layout = false ;
            $this->renderPartial('index', array(
                'dataProvider' => $dataProvider,
            ),false,true
            );

        }else{
            $this->render('index', array(
                'dataProvider' => $dataProvider,
            ));
        }
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new Status('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['Status']))
            $model->attributes = $_GET['Status'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }


    /**
     * Manages all models.
     * advanced fuctionality ,batch operation are supportted
     */
    public function actionAdminAdv()
    {
        $model = new Status('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['Status']))
            $model->attributes = $_GET['Status'];

        $this->render('adminAdv', array(
            'model' => $model,
        ));
    }


    public function actionBatchDelete()
    {
        //  print_r($_POST);
        $request = Yii::app()->getRequest();
        if ($request->getIsPostRequest()) {
            if (isset($_POST['ids'])) {
                $ids = $_POST['ids'];
            } elseif (!empty($_POST['items'])) {
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
        } else {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param $id
     * @param string $modelClass
     * @throws CHttpException
     * @param integer the ID of the model to be loaded
     * @return Status     */
    public function loadModel($id, $modelClass = 'Status')
    {
        $model = Status::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     * @param string $form
     * @return void
     * @internal param \the $CModel model to be validated
     */
    protected function performAjaxValidation($model, $form = 'status-form')
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === $form) {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionListRecentStatus($u=null){
        $this->layout = false;
   $user = ($u == null) ? user()->getId() : $u ;
            $dp = Status::listRecentStatuses($user);
       // My::listView4sqlDataProvider($dp);
        $this->widget('zii.widgets.CListView',array(
            'id'=>'status-list',
            'template'=>'{pager}{items}{pager}',
            'dataProvider'=>$dp,
            'itemView'=>'_statusView',
        ));


    }

    /**
     * @param null $u
     */
    public function actionStream($u=null){
       // $this->layout = false;
        $user = ($u == null) ? user()->getId() : $u ;
        $dataProvider = Status::buildStream($user);
        //My::listView4sqlDataProvider($dataProvider); die(__METHOD__);
         $this->render('stream',array('dataProvider'=>$dataProvider));

    }
}
