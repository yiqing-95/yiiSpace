<?php if ( ! defined('YII_PATH')) exit('No direct script access allowed');

class ExtensionController extends Controller{
    
    public function init()
    {
        parent::init();
        
        // move the view path under this extension
        Yii::app()->setViewPath(dirname(__FILE__).'/../views/');
    }
    
    public function actionIndex()
    {


        Yii::app()->clientScript->registerScript('selectMenu','
            $(".ecc").addClass("active");
        ');
        $this->render('index',array('text'=>'hello world!'));
    }


}