<?php

class RelationshipController extends  BaseFriendController
{
    protected  function beforeAction( $action){

        $actionId = $action->getId();

        if (in_array($actionId, array('myRelationships', 'create', 'update', 'manager'))) {
            $this->layout = 'userCenter';
        } elseif (in_array($actionId, array('member','viewAll'))) {
            $this->layout = 'userSpace';
            //$this->layout = UserHelper::getUserBaseLayoutAlias('userSpaceContent');
        }else{
            $this->layout = 'userSpace';
        }

        return parent::beforeAction($action);
    }
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
                'actions' => array('create', 'update', 'pendingRelationships','myRelationships'),
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
        /*
          $model=new Relationship;

          // Uncomment the following line if AJAX validation is needed
          // $this->performAjaxValidation($model);

          if(isset($_POST['Relationship']))
          {
              $model->attributes=$_POST['Relationship'];
              if($model->save())
                  $this->redirect(array('view','id'=>$model->id));
          }

          $this->render('create',array(
              'model'=>$model,
          ));*/
        $this->layout = false;
        $model = new Relationship;

        $this->performAjaxValidation($model, 'relationship-form');
        if (($user_b = Yii::app()->request->getParam('user_b', null)) !== null) {
            $model->user_b = $user_b;
        }
        if (isset($_POST['Relationship'])) {
            $model->attributes = $_POST['Relationship'];
            $model->user_a = user()->getId();
            if ($model->validate()) {
                $model = $model->createRelation();
                if (Yii::app()->request->isAjaxRequest) {
                    echo CJSON::encode(array(
                        'success' => true,
                        'div' => $this->renderPartial('_form', array('model' => $model), true),
                    ));
                    Yii::app()->end();
                } else {
                    //浏览当前新创建的评论  没此必要
                    $this->redirect(array('view', 'id' => $model->id));
                }
            }

        }
        if (Yii::app()->request->isAjaxRequest && Yii::app()->getRequest()->getIsPostRequest()) {
            echo CJSON::encode(array(
                'success' => false,
                'div' => $this->renderPartial('_form', array('model' => $model), true)));
            exit;
        } else {
            $request = Yii::app()->getRequest();
            $this->render('create', array('model' => $model));
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

        if (isset($_POST['Relationship'])) {
            $model->attributes = $_POST['Relationship'];
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
        $dataProvider = new CActiveDataProvider('Relationship');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new Relationship('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['Relationship']))
            $model->attributes = $_GET['Relationship'];

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
        $model = new Relationship('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['Relationship']))
            $model->attributes = $_GET['Relationship'];

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
     * @internal param \the $integer ID of the model to be loaded
     * @return Relationship
     */
    public function loadModel($id, $modelClass = 'Relationship')
    {
        $model = Relationship::model()->findByPk($id);
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
    protected function performAjaxValidation($model, $form = 'relationship-form')
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === $form) {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionPendingRelationships()
    {
       // $this->layout = '//layouts/user/user_center';

        $dataProvider = Relationship::getRelationships(0, user()->getId(), 0);
        $this->render('pendingRelations', array(
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionMyRelationships(){
       // $this->layout = '//layouts/user/user_center';

       //  $dataProvider = Relationship::getByUser(Yii::app()->user->id);
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        //$criteria->compare('t.id', $this->id, true);
        $criteria->compare('t.user_a',user()->getId(), true);

        $criteria->with = array('friend');

        $dataProvider = new CActiveDataProvider('Relationship', array(
            'criteria' => $criteria,
            'pagination' => array('pageSize' => 10),
            'sort' => array(
                'defaultOrder' => 't.create_time DESC',
            )
        ));



        $this->render('user/myRelationships', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * @param int $u
     */
    public function actionViewAll($u=1){
       // $this->layout = '//layouts/user/user_space';

        $criteria = new CDbCriteria;

        //$criteria->compare('t.id', $this->id, true);
        $criteria->compare('t.user_a',$u, true);

        if(($cateId = request()->getParam('cateId',false)) !==false){
            $criteria->addColumnCondition(
                array(
                    't.category_id'=>$cateId
                )
            );
        }

        $criteria->with = array('friend');

        $dataProvider = new CActiveDataProvider('Relationship', array(
            'criteria' => $criteria,
            'pagination' => array('pageSize' => 10),
            'sort' => array(
                'defaultOrder' => 't.create_time DESC',
            )
        ));



        $this->render('user/myRelationships', array(
            'dataProvider' => $dataProvider,
        ));

    }



    /**
     * @param int $u
     */
    public function actionFollowerList($u=0){
        // $this->layout = '//layouts/user/user_space';

        $criteria = new CDbCriteria;

        //$criteria->compare('t.id', $this->id, true);
        $criteria->addColumnCondition(
            array(
                'user_b'=>$u
            )
           );


        $criteria->with = array('follower');

        $dataProvider = new CActiveDataProvider('Relationship', array(
            'criteria' => $criteria,
            'pagination' => array('pageSize' => 10),
            'sort' => array(
                'defaultOrder' => 't.create_time DESC',
            )
        ));



        $this->render('follower/myRelationships', array(
            'dataProvider' => $dataProvider,
        ));

    }

    public function actionApprove()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $relation_id = Yii::app()->request->getParam('relation_id');
            $model = Relationship::model()->findByPk($relation_id,'user_b=:user_b',array(':user_b'=>user()->getId()));
            if(!empty($model)){
                $model->approveRelationship();
                if($model->save()){
                    echo CJSON::encode(array(
                        'success' => true,
                    ));
                }else{
                    echo CJSON::encode(array(
                        'success' => false,
                    ));
                }
                Yii::app()->end();
            }else{
                //the login user shouldn't operate the resource that do not belong to themselves
                throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
            }
        }
    }

    public function actionReject()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $relation_id = Yii::app()->request->getParam('relation_id');
            $model = Relationship::model()->findByPk($relation_id,'user_b=:user_b',array(':user_b'=>user()->getId()));
            if(!empty($model)){
                if($model->delete()){
                    echo CJSON::encode(array(
                        'success' => true,
                    ));
                }else{
                    echo CJSON::encode(array(
                        'success' => false,
                    ));
                }
                Yii::app()->end();
            }else{
                //the login user shouldn't operate the resource that do not belong to themselves
                throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
            }
        }else{
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
    }

     // ============================<关注 取消关注>=======================================================\\
    public function actionHasFollowed(){
        $visitorId = user()->getId();
        $objectId = Yii::app()->request->getParam('objectId');

        $isFriend = Relationship::model()->exists(
            'user_a=:visitorId AND user_b=:objectId',
            array(
                ':visitorId'=>$visitorId,
                ':objectId'=>$objectId,
            )
        );

        $this->ajaxSuccess(array(
            'hasFollowed'=> ($isFriend == true)
        ));
    }

    public function actionFollow(){
        $visitorId = user()->getId();
        $objectId = Yii::app()->request->getParam('objectId');
        $categoryId = Yii::app()->request->getParam('categoryId',0);

        $userB = User::model()->findByPk($objectId);

        $relationship = new Relationship();

        // 状态墙用来渲染 朋友的链接图像
        $relationship->friendObj = $userB ;

        $relationship->type = 1 ;
        $relationship->category_id = $categoryId ;
        $relationship->user_a = $visitorId;
        $relationship->user_b = $objectId ;
        $relationship->accepted = 0 ;
        $relationship->create_time = time() ;

        if($relationship->save()){

            RelationshipCategory::model()->updateCounters(array(
                    'mbr_count'=>1,
                ),
                'id=:cateId AND user_id=:userId',
                array(
                    ':cateId'=>$categoryId,
                    ':userId'=>$visitorId,
                )
            );

            $this->ajaxSuccess(array(

            ));
        }else{
            $this->ajaxFailure(array(
                'error'=>$relationship->getErrors(),
            ));
        }

    }

    // =============================<关注 取消关注/>======================================================//


}
