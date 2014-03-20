<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-12-3
 * Time: 上午1:12
 * To change this template use File | Settings | File Templates.
 * -------------------------------------------------------------
 * 系统穿越性功能 在这里分流
 * --------------------------------------------------------------
 */
class SysController extends YsController
{


    public function actionStarRatingAjax()
    {

        /**
         *
        $ratingAjax=isset($_POST['rate']) ? $_POST['rate'] : 0;
        echo "You are voting $ratingAjax through AJAX!";*/
        $request = Yii::app()->request;
        if ($request->getIsAjaxRequest()) {
            //关于 RatePostName 系统统一吧 或者 UI上的YsStarRating 需要根据objectName来查询这个变量的名字
            $rate = $request->getParam('rate');
            $objectName = $request->getParam('objectName');
            $objectId = $request->getParam('objectId');

            YsVotingSystem::doRating($objectName, $objectId);
        }
    }

    public function actionComment()
    {

        $request = Yii::app()->request;
        if ($request->getIsAjaxRequest()) {
            $this->layout = false;
            $objectName = $request->getParam('objectName');
            $objectId = $request->getParam('objectId');
            ob_start();
            $this->widget('comments.widgets.ECommentsListWidget', array(
                'objectName' => $objectName,
                'objectId' => $objectId,
                'dialogOptions' => array(
                    'width' => '500',
                )
            ));
            $this->renderText(ob_get_clean());
        } else {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }


    }


    public function actionRunWidget()
    {
        $request = Yii::app()->request;
        $class = $request->getParam('class');
        $options = $request->getParam('options', array());
        $className = Yii::import($class, true);
        //if($className instanceof IRunnableWidget){ // 这个是先实例化在检测 不好
        if (is_subclass_of($className, 'IRunnableWidget')) {
            $this->widget($class, $options);

        } else {
            throw new CHttpException(404, 'you can not call a widget class which is not a IRunnable Widget directly .');
        }


    }
}
