<?php
/**
 *  
 * User: yiqing
 * Date: 13-5-19
 * Time: 上午1:09
 * To change this template use File | Settings | File Templates.
 * -------------------------------------------------------
 * 这个类是专门用在ajax环境中加载listView产生的问题的
 * -------------------------------------------------------
 * @see http://www.yiiframework.com/forum/index.php/topic/41407-is-it-possible-to-force-cgridview-to-load-data-via-ajax-only/page__pid__206133#entry206133
 */

Yii::import('zii.widgets.CListView');

class YsAjaxListView extends CListView{

    public $cssFile = false;

    /**
     * @var bool
     * don't register the js file for ajax loading !
     * jquery 改版了 好像可以注册了！！
     */
    public $registerScriptFile = false ;

    public function registerClientScript(){
        $id=$this->getId();

        if($this->ajaxUpdate===false)
            $ajaxUpdate=array();
        else
            $ajaxUpdate=array_unique(preg_split('/\s*,\s*/',$this->ajaxUpdate.','.$id,-1,PREG_SPLIT_NO_EMPTY));
        $options=array(
            'ajaxUpdate'=>$ajaxUpdate,
            'ajaxVar'=>$this->ajaxVar,
            'pagerClass'=>$this->pagerCssClass,
            'loadingClass'=>$this->loadingCssClass,
            'sorterClass'=>$this->sorterCssClass,
            'enableHistory'=>$this->enableHistory
        );
        if($this->ajaxUrl!==null)
            $options['url']=CHtml::normalizeUrl($this->ajaxUrl);
        if($this->updateSelector!==null)
            $options['updateSelector']=$this->updateSelector;
        foreach(array('beforeAjaxUpdate', 'afterAjaxUpdate', 'ajaxUpdateError') as $event)
        {
            if($this->$event!==null)
            {
                if($this->$event instanceof CJavaScriptExpression)
                    $options[$event]=$this->$event;
                else
                    $options[$event]=new CJavaScriptExpression($this->$event);
            }
        }

        $options=CJavaScript::encode($options);
        $cs=Yii::app()->getClientScript();

        if($this->registerScriptFile == true){
            $cs->registerCoreScript('jquery');
            $cs->registerCoreScript('bbq');
            if($this->enableHistory)
                $cs->registerCoreScript('history');
            $cs->registerScriptFile($this->baseScriptUrl.'/jquery.yiilistview.js',CClientScript::POS_END);
        }

        $cs->registerScript(__CLASS__.'#'.$id,"jQuery('#$id').yiiListView($options);");
    }
}