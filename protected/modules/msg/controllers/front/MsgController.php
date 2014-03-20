<?php

class MsgController extends BaseMsgController
{

    public function beforeAction($action)
    {

        // 使用用户中心布局
        $this->layout = UserHelper::getUserBaseLayoutAlias('userCenterContent');

        return parent::beforeAction($action);
    }

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array( //'accessControl', // perform access control for CRUD operations
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
                'actions' => array('index'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update', 'inbox', 'view'),
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
        $msg = new Msg();
        $msg = $this->loadModel($id);
        if ($msg->recipient == Yii::app()->user->id) {
            $msg->read = 1; // mark as read!
            $msg->save();
        }

        $this->render('view', array(
            'model' => $msg,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new Msg;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        $model->uid = user()->getId() ;
        $model->type = Msg::TYPE_MBR_PERSONAL ;
        $model->to_id = Yii::app()->request->getParam('u');

        if (isset($_POST['Msg'])) {
            $model->attributes = $_POST['Msg'];
            if ($model->save()) {

                if($model->type == Msg::TYPE_MBR_PERSONAL){
                    $msgInbox = new MsgInbox();
                    $msgInbox->uid = $model->to_id ;
                    $msgInbox->msg_id = $model->primaryKey ;

                    $msgInbox->save();
                }

                if (Yii::app()->request->isAjaxRequest) {
                    $this->ajaxSuccess(
                        array(
                            'message' => "Msg successfully added"
                        )
                    );
                    return;
                } else {
                    $this->redirect(array('view', 'id' => $model->id));
                }

            }
        }
        if (Yii::app()->request->isAjaxRequest) {
            $this->ajaxFailure(
                array(
                    'form' => $this->renderPartial('_form', array('model' => $model), true)
                )
            );
        } else {
            $this->render('create', array(
                'model' => $model,
            ));
        }

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

        if (isset($_POST['Msg'])) {
            $model->attributes = $_POST['Msg'];
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
            $msg = new Msg();
            $msg = $this->loadModel($id);
            if ($msg->recipient = Yii::app()->user->id) {
                $msg->delete();
            }
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
        $dataProvider = new CActiveDataProvider('Msg');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new Msg('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['Msg']))
            $model->attributes = $_GET['Msg'];

        //can only admin own messages
        $model->recipient = Yii::app()->user->id;

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
        $model = new Msg('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['Msg']))
            $model->attributes = $_GET['Msg'];

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
     * @return Msg
     */
    public function loadModel($id, $modelClass = 'Msg')
    {
        $model = Msg::model()->findByPk($id);
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
    protected function performAjaxValidation($model, $form = 'msg-form')
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === $form) {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionInbox()
    {

        // $dataProvider = Msg::getInbox(Yii::app()->user->id);

        // 收件箱
        // TODO 分类展示不同的消息类型
        $criteria = new CDbCriteria();
        $criteria->with = array(
            'msg'
        );

        $criteria->addColumnCondition(array(
            't.uid' => user()->getId(),
        ));

        $dataProvider = new CActiveDataProvider('MsgInbox', array(
            'criteria' => $criteria,
        ));

        //================================================================\\
        // 遍历收集发送者的id
        $msgInboxArr = $dataProvider->getData();
        $userIds = array() ;
        foreach($msgInboxArr as $msgInbox){
            if($msgInbox->msg->type == Msg::TYPE_MBR_PERSONAL){
                $userIds[] = $msgInbox->msg->uid ;
            }
        }
        $userIds = array_unique($userIds);
        $criteria = new CDbCriteria( );
        $criteria->index = 'id';
        $criteria->addInCondition('id',$userIds);
        $userList = User::model()->findAll($criteria) ;
       //  print_r($userList);
        Registry::instance()->set('senderList',$userList);

        //================================================================//

        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }
}
