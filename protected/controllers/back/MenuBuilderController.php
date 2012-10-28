<?php

class MenuBuilderController extends Controller
{
    public $layout = '//layouts/main';

    public function actionIndex()
    {
        WebUtil::printCharsetMeta();
        $this->render('index');
    }

    public function actionInitAjax()
    {
        if (Yii::app()->getRequest()->getIsAjaxRequest()) {
            $topRoot = AdminMenu::model()->find('group_code=:group_code', array(':group_code'=>'sys_admin_menu_root'));
            $dynaTreeData = array();
                $roots = $topRoot->children()->findAll();
                foreach ($roots as $root) {
                    $dynaTreeData[] = array(
                        'title' => $root->label,
                        'key' => $root->id,
                        'isLazy' => true,
                        'isFolder' => true,
                    );
                }
            echo CJSON::encode($dynaTreeData);
            die();
        }
    }

    public function actionLoadChildren()
    {
        if (Yii::app()->getRequest()->getIsAjaxRequest()) {
            $request = Yii::app()->getRequest();
            $parent = AdminMenu::model()->findByPk($request->getParam('key'));
            $descendants = $parent->children()->findAll();
             $dynaTreeData = array();
            foreach ($descendants as $child) {
                $dynaTreeData[] = array(
                    'title' => $child->label,
                    'key' => $child->id,
                    'isLazy' => $child->isLeaf() ? false : true,
                    'isFolder' => !$child->isLeaf(),
                );
            }
            echo CJSON::encode($dynaTreeData);
            die();
        }
    }

    public function actionMoveNode()
    {
        if (Yii::app()->getRequest()->getIsAjaxRequest()) {
            $request = Yii::app()->getRequest();
            $sourceNode = AdminMenu::model()->findByPk($request->getParam('srcNode'));
            $refNode = AdminMenu::model()->findByPk($request->getParam('refNode'));
            $moveMode = $request->getParam('moveMode');

            $moveMethod = 'move' . $moveMode;
            if (method_exists($sourceNode->asa('nestedSet'), $moveMethod)) {
                if ($sourceNode->$moveMethod($refNode)) {
                    $res['error'] = true;
                } else {
                    $res['error'] = false;
                }
            } else {
                $res['error'] = false;
            }
            echo CJSON::encode($res);
            die();
        }
    }

    /**
     * 只有删除操作会动原始节点
     */
    public function actionDeleteNode()
    {
        if (Yii::app()->getRequest()->getIsAjaxRequest()) {
            $request = Yii::app()->getRequest();
            $node = AdminMenu::model()->findByPk($request->getParam('nodeId'));
            // var_dump($node);
            $success = $node->deleteNode();
            echo CJSON::encode(array('error'=>$success));
            die();
        }
    }

    public function actionTest(){
        $topRoot = AdminMenu::model()->find('group_code=:group_code', array(':group_code'=>'sys_admin_menu_root'));

        $descendants=$topRoot->descendants()->findAll();

        $this->render('test',array('descendants'=>$descendants));
    }
    // Uncomment the following methods and override them if needed
    /*
     public function filters()
     {
         // return the filter configuration for this controller, e.g.:
         return array(
             'inlineFilterName',
             array(
                 'class'=>'path.to.FilterClass',
                 'propertyName'=>'propertyValue',
             ),
         );
     }

     public function actions()
     {
         // return external action classes, e.g.:
         return array(
             'action1'=>'path.to.ActionClass',
             'action2'=>array(
                 'class'=>'path.to.AnotherActionClass',
                 'propertyName'=>'propertyValue',
             ),
         );
     }
     */
}