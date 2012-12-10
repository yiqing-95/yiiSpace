<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-12-5
 * Time: 上午10:24
 * To change this template use File | Settings | File Templates.
 * ----------------------------------------------------------------------
 * 使用该类时一般需要指定 objectName 跟objectId 由于上面的继承层次中有inputWidget
 * 所以 还需要出来 $model 和 $attribute 情况 暂时还没完成这个种功能 留下一步
 * 再说 ， objectId 和objectName 还可以使js表达式（js:xxx 或 new CJavaScriptExpress('xxx');）
 * 这样 可以多个对象公用一个 widget 动态切换掉投票对象 一般类型不会变的只有id可能会变
 * 但也是支持类型和id同时变化情况！
 * ----------------------------------------------------------------------
 */
class YsStarRating extends CStarRating
{

    /**
     * @var string|CJavaScriptExpression
     */
    public $objectName ;
    /**
     * @var int|string|CJavaScriptExpression
     */
    public $objectId ;

    /**
     * @var string
     * the js StarRating selector
     */
    protected $containerId ='';

    /**
     * @var string
     * prevent for repeat post  ;
     * for multiple instance  can share only one ;
     */
    protected $doPostFlagVar ;

    public function init(){
        parent::init();

        $this->allowEmpty = false;
        $this->ratingStepSize = 1;
        $this->minRating = 1;


    }

    /**
     * Registers the necessary javascript and css scripts.
     * @param string $id the ID of the container
     */
    public function registerClientScript($id)
    {
       $this->containerId = $id;

        $doPostFlagVar = $id.'_'.str_replace(':','_',__METHOD__);

        $this->doPostFlagVar = $doPostFlagVar;

        Yii::app()->getClientScript()->registerScript($doPostFlagVar,"var {$doPostFlagVar} = true ;",CClientScript::POS_HEAD);

        /**
         * the call back js code will use the containerId
         */
        $this->callback = $this->getCallBackJs();

        parent::registerClientScript($id);
    }
    /**
     * @return string
     */
    protected function getCallBackJs(){
        //$urlTpl = Yii::app()->createUrl('/sys/starRatingAjax',array('objectName={objectName}','objectId'=>'{objectId}')) ;

        $url = Yii::app()->createUrl('/sys/starRatingAjax') ;

        /*
       if($this->isJsExpression($this->objectId)){
           if($this->isJsExpression($this->objectName)){
               $objectName = CJavaScript::encode($this->objectName);
           }else{
               $objectName = CJavaScript::quote($this->objectName);
           }
           $objectId = CJavaScript::encode($this->objectId);
       }else{
           $objectId = $this->objectId;
       }*/

        $data = CJavaScript::encode(
          array(
              Yii::app()->request->csrfTokenName => Yii::app()->request->getCsrfToken(),
              'rate'=>'js:$(this).val()',
              'objectName'=>$this->objectName,
              'objectId'=>$this->objectId,
          )
        );
        $js = <<<EOD
    function(){
       if({$this->doPostFlagVar} == false) return ;

        {$this->doPostFlagVar} = false;

        $.ajax({
        type: "POST",
        url: "{$url}",
        data: {$data},
        dataType: "json",
        success: function(res){
            //$("#result").html(res);
            if(res.status == "success"){
               jNotify("感谢您的参与！");

            }else{
               jNotify(res.msg);
            }
             // 重置
              // $("#{$this->containerId} >input").rating();
              //如果没有全局变量控制就不能设置某个值不然就死循环了！！
              //$("#{$this->containerId} >input").rating('select',0);

              {$this->doPostFlagVar} = true;
        }})}

EOD;
          return $js;
    }

    /**
     * @param $value
     * @return bool
     */
    protected  function isJsExpression($value){
       if(is_string($value) || is_object($value)){
           if(is_string($value) && (strpos($value,'js:')===0)){
               return true;
           }elseif($value instanceof CJavaScriptExpression){
               return true;
           }else{
               return false ;
           }
           //return ($value instanceof CJavaScriptExpression) || (strpos($value,'js:')===0) ;
       } else{
           return false ;
       }

    }
}
