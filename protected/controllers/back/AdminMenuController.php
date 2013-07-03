<?php

class AdminMenuController extends BackendController
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/empty';

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
                'actions' => array('create', 'update'),
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
        $model = new AdminMenu();

        // die(YiiUtil::getPathOfClass($model));
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        $request = Yii::app()->getRequest();

        if (isset($_POST['AdminMenu'])) {
            $model->attributes = $_POST['AdminMenu'];
            if ($model->validate()) {
                if (($pid = Yii::app()->user->getState(__METHOD__)) !== null) {
                    $parentNode = AdminMenu::model()->findByPk($pid);
                    if ($pid == null || $pid == '-1') {
                        if (($topRoot = AdminMenu::model()->roots()->find('group_code=:group_code', array(':group_code' => 'sys_admin_menu_root'))) == null) {
                            $topRoot = new AdminMenu();
                            $topRoot->label = 'top_virtual_root';
                            $topRoot->group_code = 'sys_admin_menu_root';
                            $topRoot->saveNode();
                        }

                        $parentNode = $topRoot;
                    }
                    $model->appendTo($parentNode);

                }
                $this->redirect(array('view', 'id' => $model->id));
            }
        }
        if (($pid = $request->getParam('parentId', '-1'))) {
            Yii::app()->user->setState(__METHOD__, $pid);
        }
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

        if (isset($_POST['AdminMenu'])) {
            $model->attributes = $_POST['AdminMenu'];

            if ($model->validate() && $model->saveNode())
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
        $this->layout = '//layouts/empty';
        $dataProvider = new CActiveDataProvider('AdminMenu');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new AdminMenu('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['AdminMenu']))
            $model->attributes = $_GET['AdminMenu'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }


    /**
     * Manages all models.
     * advanced functionality ,batch operation are supported
     */
    public function actionAdminAdv()
    {
        $model = new AdminMenu('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['AdminMenu']))
            $model->attributes = $_GET['AdminMenu'];

        $this->render('adminAdv', array(
            'model' => $model,
        ));
    }


    /**
     * @throws CHttpException
     */
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
     * @internal param \the $integer ID of the model to be loaded
     * @return AdminMenu
     */
    public function loadModel($id, $modelClass = 'AdminMenu')
    {
        $model = AdminMenu::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param $model
     * @param string $form
     * @return void
     * @internal param \the $CModel model to be validated
     */
    protected function performAjaxValidation($model, $form = 'admin-menu-form')
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === $form) {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
