<?php

/**
 * Created by PhpStorm.
 * User: yiqing
 * Date: 14-2-13
 * Time: 下午10:34
 */
class CommentModelProfilerManager
{

    /**
     * @param $model
     * @return mixed
     */
    public static  function getModelConfig($model){
          return Yii::app()->getModule('comment')->getModelConfig($model);
    }

    /**
     * TODO implement a memory cache functionality
     * @param string $model
     * @return array|callable
     */
    protected static function getModelSummaryRender($model){
        $modelConfig = self::getModelConfig($model);

        if(isset($modelConfig['modelProfiler'])){
           $modelProfilerConfig = $modelConfig['modelProfiler'] ;
           if(isset($modelProfilerConfig['isWidget']) && $modelProfilerConfig['isWidget'] == true){
               $modelProfilerObj = Yii::app()->getController()->widget($modelProfilerConfig['class']);
               $renderMethod = $modelProfilerConfig['renderMethod'] ;
               return array($modelProfilerObj,$renderMethod);
           }
        }else{
            return array() ;
        }

    }

    /**
     * @param string $model
     * @param array $data
     */
    public static function  renderModelSummary($model='',$data=array())
    {
        static $summaryRenders = array() ;
        $render = null ;
        if(!isset($summaryRenders[$model])){
            $render = self::getModelSummaryRender($model);
            $summaryRenders[$model] = $render ;
        }else{
            $render = $summaryRenders[$model];
        }


        if(!empty($render)){
            call_user_func_array($render,array($data));
        }else{
            echo __METHOD__ , 'error !!!';
            print_r($data);
        }


    }

}