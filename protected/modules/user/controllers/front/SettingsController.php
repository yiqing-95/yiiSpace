<?php

class SettingsController extends BaseUserController
{

    public $layout = '//layouts/user/user_center';

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
                'actions' => array('index', 'view', 'space'),
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
     * Lists all setting items.
     */
    public function actionIndex()
    {
        $this->render('/user/index', array(
        ));
    }

    public function actionPhoto()
    {
        $model = UserModule::user()->profile;
        if (Yii::app()->request->getIsPostRequest()) {

            //$fileFiledName = CHtml::resolveName($model,'file');


            $im = new Imagemanager();
            $uploadStorage = YsUploadStorage::instance();
            $uploadDir = $uploadStorage->getUploadDir();
            $im->loadFromPost('photo', $uploadDir . DS, time());
            if ($im == true) {
                $im->resizeScaleHeight(150);
                //if there exists old upload then we should delete it
                $oldImgPath = $model->photo;

                $model->photo = $uploadStorage->realPath2url($uploadDir . DIRECTORY_SEPARATOR . $im->getName());
                if($model->save(false)){
                    if(!empty($oldImgPath)){
                        $oldImgPath = $uploadStorage->url2realPath($oldImgPath);
                        if(file_exists($oldImgPath)){
                            unlink($oldImgPath);
                        }
                    }
                }
            }
        }
        $this->render("photo", array('model' => $model));
    }


    /**
     * @return User
     */
    public function loadModel()
    {
        return UserModule::user();
    }

}
