<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-2-13
 * Time: 上午9:27
 * To change this template use File | Settings | File Templates.
 * ---------------------------------------------------------------
 * note :  must be followed the CGridView initialized ,if go before it
 *         this widget will not do its job ^-^ ;
 * --------------------------------------------------------------
 * 注意：不能将这个widget放在CGridView 或其子类实例化之前 ；
 * 这个控件依赖于CCheckBoxColumn的选择功能。
 * ---------------------------------------------------------------
 */
class EOnGridChecked extends CWidget
{

    /**
     * @var string
     * a js callback function
     * the parameter you can use to get the selected values
     *  ----------------------
     *   eg:  js:function(selectedIds){
     *
     *  }
     *  -----------------------
     */
    public $callback ;

    public function init(){
        if(empty($this->callback)){
            throw new CException('you need assign a js callback function to use this widget !
             for example : function(ids){
                //ids  is an array which hold the all checked value of the checkbox
                 $("#someHiddenInputField").val(ids.toString());
             } ');
        }

        parent::init();

    }
    public function run(){
           $this->registerClientScript();
    }

    public function registerClientScript(){
        $jsCallBack = CJavaScript::encode($this->callback);

        Yii::app()->clientScript->registerScript('clickCheckBoxEvents',<<<EOD
            $('.checkbox-column :checkbox').live('click', function() {
                  var pks = getCheckedValues();
                  var callback = {$jsCallBack};
                  callback.call(this, pks); // callback.apply(this, pks);
                 //$("#msg").html("您共选中了 "+pks.length+" 项"); //见鬼了下面的竟然不能用
                 // var selectionIds=$.fn.yiiGridView.getSelection('xxx-grid');
                 // $("#msg").html("您共选中了 "+selectionIds.length+" 项");
            });
            //内嵌函数 获取选中的复选值
            function getCheckedValues(){
                 //收集被选中的元素的value值
                 var pks = []; //主键值  注意在sqlDataProvider 时要指定keyField
                 //构造一个正则 此正则匹配能选中所以复选框的那个总按钮：selectedItems_all
                 var   reg = new   RegExp("_all$");
                 $('.checkbox-column :checkbox').each(function(){
                             if (! reg.test($(this).attr('id')) && this.checked) {
                                pks.push(this.value);
                             }
                       }
                 );
                 return pks ;
            }
EOD
            ,CClientScript::POS_READY);

    }
}
