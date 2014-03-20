<?php

class GroupController extends BaseGroupController
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = 'main';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
        'accessControl',
         // perform access control for CRUD operations
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
                'actions' => array('index', 'view','latestJoined','allMembers','listMemberGroups'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update','joinGroup','listMyGroups'),
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

        $group = $this->loadModel($id);

        /*
        if ($group->creator == $uid || (!empty($groupMember) && $groupMember->approved)) {
            $this->menu = array(
                array('label' => 'create topic', 'url' => array('/group/groupTopic/create', 'group' => $group->id)),
            );
        }
         */

        //=============================================\\
        $criteria = new CDbCriteria();
        $criteria->addColumnCondition(array(
            'group_id' => $id,
        ));

        $dataProvider = new CActiveDataProvider('GroupTopic', array(
            'criteria' => $criteria,
        ));

        //=============================================//


        $this->render('view', array(
            'model' => $group,
            'dataProvider'=>$dataProvider,
        ));
    }

    /**
     * @param $id
     */
    public function actionAllMembers($id){

        $criteria = new CDbCriteria();

        $criteria->addColumnCondition(array(
            'group_id'=>$id,
        ));
        // 加载用户模型
        $criteria->with = array('user');

        $dataProvider = new CActiveDataProvider('GroupMember',array(
            'criteria'=>$criteria,
        ));

        $this->render('member/all',array(
            'dataProvider'=>$dataProvider,
        ));
    }

    /**
     * 必须为ajax请求哦！
     * @param $id
     */
    public function actionLatestJoined($id){
            $this->widget('group.widgets.GroupMemberWidget',array(
               'groupId'=>$id,
            ));
    }

    public function actionJoinGroup(){
        //  print_r($_POST);
        $request = Yii::app()->request ;
        $groupId = $request->getParam('groupId');
        $userId = user()->getId() ;

        if(!GroupMember::model()->exists('group_id=:groupId AND user_id=:userId',
            array(
                ':groupId'=>$groupId,
                ':userId'=>$userId ,
            )))
        {
            $groupMember = new GroupMember() ;
            $groupMember->group_id = $groupId ;
            $groupMember->user_id = $userId ;
            //  初次加入 其实应该看下该组的配置在决定是否批准！
            $groupMember->approved  = 1 ;
            $groupMember->requested = 1 ;
            $groupMember->requested_time = time() ;
           if(! $groupMember->save()){
               $this->ajaxFailure(array(
                  'error'=>$groupMember->getErrors(),
               ));
           }else{
               $this->ajaxSuccess(array(
                   'msg'=>'申请成功！',
               ));
           }

        }else{
            // 重复提交？
            $this->ajaxSuccess(
                array(
                    'msg'=>'请等待批准您的申请！',
                )
            );
        }
        }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $dataProvider = new CActiveDataProvider('Group');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }


    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param $id
     * @param string $modelClass
     * @throws CHttpException
     * @param integer the ID of the model to be loaded
     * @return Group
     */
    public function loadModel($id, $modelClass = 'Group')
    {
        $model = Group::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }


    //-----------------------------------------------------------\\
    /**
     * 用户中心看到的我的分组
     */
    public function actionListMyGroups(){
        $dataProvider = Group::getUserGroupsDataProvider(user()->getId());

        $this->render('userCenter/listGroups',array(
            'dataProvider'=>$dataProvider ,
        ));
    }

    /**
     * 用户空间看到的我的分组
     */
    public function actionListMemberGroups(){
        $uid = UserHelper::getSpaceOwnerId() ;
        $dataProvider = Group::getUserGroupsDataProvider($uid);

        $this->render('userSpace/listGroups',array(
            'dataProvider'=>$dataProvider ,
        ));
    }
    //-----------------------------------------------------------//


}
