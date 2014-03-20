<?php

class UserController extends BaseUserController
{
    /**
     * @var CActiveRecord the currently loaded data model instance.
     */
    private $_model;

    /**
     * @return array action filters
     */
    public function filters()
    {
        return CMap::mergeArray(parent::filters(), array(
            'accessControl', // perform access control for CRUD operations
        ));
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
                'actions' => array('index', 'view', 'space', 'ajaxLogin','ajaxProfileBox'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform
                'actions' => array('home', $this->action->id),
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays a particular model.
     */
    public function actionView()
    {
        $model = $this->loadModel();
        $this->render('view', array(
            'model' => $model,
        ));
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        /*
		$dataProvider=new CActiveDataProvider('User', array(
			'criteria'=>array(
		        'condition'=>'status>'.User::STATUS_BANNED,
		    ),
				
			'pagination'=>array(
				'pageSize'=>Yii::app()->controller->module->user_page_size,
			),
		));
        */
        $model = new User('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['User']))
            $model->attributes = $_GET['User'];

        $dataProvider = $model->search();
        $dataProvider->criteria->with = array('profile');
        $dataProvider->getCriteria()->addCondition('status>' . User::STATUS_BANNED);
        $this->render('/user/index', array(
            'dataProvider' => $dataProvider,
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     */
    public function loadModel()
    {
        if ($this->_model === null) {
            if (isset($_GET['id']))
                $this->_model = User::model()->findbyPk($_GET['id']);
            if ($this->_model === null)
                throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $this->_model;
    }


    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the primary key value. Defaults to null, meaning using the 'id' GET variable
     * @throws CHttpException
     * @return \CActiveRecord
     */
    public function loadUser($id = null)
    {
        if ($this->_model === null) {
            if ($id !== null || isset($_GET['id']))
                $this->_model = User::model()->findbyPk($id !== null ? $id : $_GET['id']);
            if ($this->_model === null)
                throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $this->_model;
    }

    public function actionSpace()
    {
        // $this->forward('/status/status/index');
        //
        $spaceOwnerId = Yii::app()->request->getParam('u', 0);
        $spaceOwnerModel = User::model()->with('profile')->findByPk($spaceOwnerId);
        if (empty($spaceOwnerModel)) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        UserHelper::setSpaceOwnerId($spaceOwnerId);
        UserHelper::setSpaceOwnerModel($spaceOwnerModel);


        //.............................................................\\
        // 有人说用一个看不见的图片做统计 但不知道怎么搞的？
        if (!user()->getIsGuest()) {
            $visitorId = user()->getId();
            if ($visitorId != $spaceOwnerId) {
                //访客记录
                $currentTime = time();
                $sql = "
                    INSERT INTO user_space_visitor
                    (space_id,
                     visitor_id,
                     vtime)
                    VALUES (:space_id,
                      :visitor_id,
                         :vtime)
                         ON DUPLICATE KEY UPDATE vtime = :vtime;
                    ";
                $dbCommand = db()->createCommand($sql);
                $dbCommand->bindParam(':space_id', $spaceOwnerId);
                $dbCommand->bindParam(':visitor_id', $visitorId);
                $dbCommand->bindParam(':vtime', $currentTime);

                $dbCommand->execute();
                /**
                 * 貌似用
                 * $dbCommand->execute(array(':key1'=>val1,':key2'=>v2,...));
                 * 更简洁？
                 */
            }
        }
        //.............................................................//
        //------------------------------------------------------------\\
        // about cookie manage :http://www.yiiframework.com/wiki/152/cookie-management-in-yii
        // 访问统计不记录访客信息 ip user_id
        //$visitorId = user()->getIsGuest()?0 :user()->getId();
        $visitedSpacesKey = 'visitedSpaces';

        if (isset(Yii::app()->request->cookies[$visitedSpacesKey])) {
            $visitedSpacesCookie = Yii::app()->request->cookies[$visitedSpacesKey];
            $visitedSpaces = $visitedSpacesCookie->value;
            $visitedSpacesArr = CJSON::decode($visitedSpaces);
            if (!in_array($spaceOwnerId, $visitedSpacesArr)) {
                // 不在访问历史中 那么推人cookie 并统计入库
                $visitedSpacesArr[] = $spaceOwnerId;
                $visitedSpacesCookie->value = CJSON::encode($visitedSpacesArr);
                Yii::app()->request->cookies[$visitedSpacesKey] = $visitedSpacesCookie;

                SpaceVisitHelper::recordAccess(date('Y-m-d'), $spaceOwnerId);
            } else {
                // 已在访问历史中了 do nothing here

            }

        } else {

            //cookie 中并没有记录这个 表示是本次初次访问某个空间：
            $visitedSpacesArr = array($spaceOwnerId);
            $visitedSpacesCookie = new CHttpCookie($visitedSpacesKey, CJSON::encode($visitedSpacesArr));
            $visitedSpacesCookie->expire = time() + 60 * 60 * 24; //* 180;

            Yii::app()->request->cookies[$visitedSpacesKey] = $visitedSpacesCookie;

            SpaceVisitHelper::recordAccess(date('Y-m-d'), $spaceOwnerId);

        }

        //------------------------------------------------------------//

        $this->layout = "userSpace";
        $this->render('space');
    }

    /**
     * dashboard for the current login user
     */
    public function actionHome()
    {

        $this->forward('/status/status/create');


        // 下面的暂时不用了
        $this->layout = UserHelper::getUserCenterLayout();
        $model = User::model()->findByPk(Yii::app()->user->id);

        $this->render('home', array(
            'model' => $model,
            'profile' => $model->profile,
        ));
    }
    // ===================================================================================\\
    //===================
    // 收藏功能实现
    //===================

    /**
     *
     */
    public function actionGlean()
    {
        $request = Yii::app()->getRequest();
        if ($request->getIsPostRequest() && $request->getIsAjaxRequest()) {
            $objectType = $request->getParam('objectType');
            $objectId = $request->getParam('objectId');

            $userId = user()->getId();
            // 查看是否收藏过了
            if (!UserGlean::model()->exists('user_id = :user_id AND object_type = :object_type AND object_id = :object_id',
                array(':user_id' => $userId, ':object_type' => $objectType, ':object_id' => $objectId)
            )
            ) {

                $userGlean = new UserGlean();
                $userGlean->user_id = $userId;
                $userGlean->object_type = $objectType;
                $userGlean->object_id = $objectId;
                $userGlean->ctime = time();
                if ($userGlean->save()) {
                    $this->ajaxSuccess(array(
                        'msg' => '收藏成功',
                    ));
                } else {

                    $this->ajaxFailure(array(
                        'msg' => $userGlean->getErrors(),
                    ));
                }
            } else {
                $this->ajaxSuccess(array(
                    'msg' => '已收藏',
                ));
            }

        }
    }

    public function actionGleanList()
    {

        $this->redirect(array('/blog/glean/list'));

        $this->layout = 'userCenter';

        $model = new UserGlean('search');
        // 默认给一个收藏类型 在下面动态覆盖！
        $model->object_type = 'blog';
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['UserGlean'])) {
            $model->attributes = $_GET['UserGlean'];
            if (!isset($_GET['objectType'])) {
                $model->object_type = 'blog';
            } else {
                $model->object_type = $_GET['objectType'];
            }
        }
        // 关联查询下收藏的对象 动态关联 存在跨模块访问的问题！
        // $model->getDbCriteria()->with = array($model->object_type);

        if (Yii::app()->request->getIsAjaxRequest()) {
            $cs = Yii::app()->clientScript;
            $cs->scriptMap = array(
                'jquery.js' => false,
                'jquery.min.js' => false,
            );
            //die(print_r($_REQUEST,true));
            $this->layout = false;

            $dataProvider = $model->search();

            $this->beginClip('glean-type-list');
            // My::listView4sqlDataProvider($dp);
            $dataProvider->pagination->pageSize = 1;

            $this->widget('YsAjaxListView', array(
                'id' => 'glean-list-' . $model->object_type,
                'template' => '{pager}{items}{pager}',
                'dataProvider' => $dataProvider,
                'itemView' => 'glean/_view',
            ));
            $this->endClip();

            $this->renderText($this->clips['glean-type-list']);

            Yii::app()->end();
        }


        $this->render('glean/list', array('model' => $model));
    }

    public function actionGleanDelete()
    {
        $request = Yii::app()->request;
        if ($request->getIsPostRequest() && $request->getIsAjaxRequest()) {
            $id = $request->getParam('id');

            $userGlean = UserGlean::model()->findByPk($id);
            if ($userGlean->delete()) {
                $this->ajaxSuccess(array(
                    'msg' => '删除成功！',
                ));
            }

        } else {
            throw new CHttpException(404, '不允许的操作！.');
        }
    }

    // ===================================================================================//


    public function actionAjaxProfileBox()
    {
        if (request()->getIsAjaxRequest()) {
            $userId = request()->getParam('u');

            $user = User::model()->findByPk($userId);
            $this->renderPartial(
                '_ajaxProfileBox', array(
                    'user' => $user
                )
            );

        } else {
            WebUtil::throw404httpException('黑客么？');
        }
    }

    //-----------------------------------------\\
    // ajax登录
    public function actionAjaxLogin()
    {
        $model = new LoginForm();

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);

        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            if ($model->validate() && $model->login()) {
                if (Yii::app()->request->isAjaxRequest) {
                    $this->ajaxSuccess(
                        array(
                            'message' => "LoginForm successfully added"
                        )
                    );
                    return;
                }
            }
        }
        if (Yii::app()->request->isAjaxRequest) {
            $this->ajaxFailure(
                array(
                    'form' => $this->renderPartial('_ajaxLoginForm', array('model' => $model), true)
                )
            );
        }
    }

    protected function performAjaxValidation($model, $form = 'login-form')
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === $form) {
            $validateResult = CActiveForm::validate($model);
            echo $validateResult;
            /*
             $validateResult = CJSON::decode($validateResult);
             if(empty($validateResult)){
                 // 验证通过 然后记录最后登录时间：

             }*/
            Yii::app()->end();
        }
    }

    //-----------------------------------------//

}
