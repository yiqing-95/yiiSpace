<?php

/**
 * Created by PhpStorm.
 * User: yiqing
 * Date: 14-1-1
 * Time: 上午3:49
 */
class YsGleanWidget extends CWidget
{

    /**
     * @var string
     */
    public $tagName = 'a';
    /**
     * @var array
     */
    public $htmlOptions = array(
        'class'=>'button',
    );
    /**
     * @var string
     */
    public $label = '收藏';
    /**
     * @var string
     */
    public $objectType = '';
    /**
     * @var int
     */
    public $objectId = 2;
    /**
     * @var array
     */
    public $actionRoute = '/user/glean';

    public $actionConfirmation = '确定收藏？';

    /**
     *
     */
    public function init(){
        $this->htmlOptions = CMap::mergeArray(
            $this->htmlOptions,
            array(
           'data-object-type'=>$this->objectType,
            'data-object-id'=>$this->objectId,
            'data-action-url'=>Yii::app()->createUrl($this->actionRoute),
        )
        );

        if(isset($this->htmlOptions['class'])){
            $this->htmlOptions['class'] .= ' user-op-glean';
        }else{
            $this->htmlOptions['class'] = ' user-op-glean';
        }
    }
    /**
     *
     */
    public function run()
    {
        $this->renderActionElement();
        $this->registerJs();
    }

    /**
     * @return $this
     */
    public function renderActionElement()
    {

        $options = $this->htmlOptions;
        echo CHtml::tag(
            $this->tagName,
            $options,
            $this->label
        );

        return $this;
    }

    /**
     * @return $this
     */
    public function registerJs()
    {
        static $registered;

        if ($registered === true) {
            return $this;
        }
        $registered = true;

        $gleanHandler = <<<EOD
 $("body").on("click",'{$this->tagName}.user-op-glean',function(){
           var objectType = $(this).attr("data-object-type");
           var objectId = $(this).attr("data-object-id");

           var url = $(this).attr("data-action-url");

           $.ajax({
               type: "POST",
               url: url,
               dataType:"json",
               data: {"objectType":objectType,"objectId":objectId},
               beforeSend:function(){
                 if(confirm("{$this->actionConfirmation}")){
                     return true;
                 }  else{
                     return false ;
                 }
               },
               success: function(resp){
                   if(resp.status == 'success'){
                       alert( " " + resp.msg );

                   }else{
                       var errors = [];
                       errors = resp.msg ;

                       var alertMsg = '';
                       for(var idx in errors){
                           alertMsg += ""+idx+"|"+errors[idx] ;
                       }
                       // 操作失败了
                       alert(  alertMsg );
                   }

               }
           });
       }) ;
EOD;


        $cs = Yii::app()->clientScript;
        $cs->registerScript(__CLASS__, $gleanHandler, CClientScript::POS_READY);

        return $this;
    }
} 