<?php
/**
 *
 * 根据controller action id 路由日志到不同的地方：
 * 在CWebApplication::beforeControllerAction 中用Yii::app()->params 记录控制器跟action id
 *
 * 然后再在这里的processLog中实现路由 根据上面保存的东西 来分流
 *
 * Log本身是贯穿到应该的各个组件的 有可能在控制器运行域内 也可能不在 所以要在特定地方来收集
 *
 */

class YsTestLogRoute  extends CLogRoute{


    /**
     * @var string
     */
    protected  $controllerId = '';
    /**
     * @var string
     */
    protected $actionId = '';

    /**
     * @var array
     */
    protected $notInControllerActionTimes = 0;

    public function collectLogs($logger, $processLogs=false)
    {
        //echo(__METHOD__);
        if(!empty(Yii::app()->controller)){
            $this->controllerId = Yii::app()->controller->id;
            if(!empty(Yii::app()->controller->action)){
                $this->actionId = Yii::app()->controller->action->id ;
            }
        }else{
             $this->notInControllerActionTimes ++ ;
        }
       parent::collectLogs($logger,$processLogs);
    }
    /**
     * Processes log messages and sends them to specific destination.
     * Derived child classes must implement this method.
     * @param array $logs list of messages. Each array element represents one message
     * with the following structure:
     * array(
     *   [0] => message (string)
     *   [1] => level (string)
     *   [2] => category (string)
     *   [3] => timestamp (float, obtained by microtime(true));
     */
    protected function processLogs($logs)
    {
        // TODO: Implement processLogs() method.
        // print_r($logs);
        /*
        if(!empty(Yii::app()->controller)){
            print_r(array(
                'controller'=>Yii::app()->controller->id,
                'action'=>Yii::app()->controller->action->id ,
            ));
        }

         print_r(
           array(
               'controller'=>$this->controllerId,
               'action'=>$this->actionId,
               'notInControllerActionTimes'=>$this->notInControllerActionTimes,
           )
         );
        */
    }
}