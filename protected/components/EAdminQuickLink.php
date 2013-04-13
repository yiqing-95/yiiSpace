<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-8-4
 * Time: 上午9:18
 * To change this template use File | Settings | File Templates.
 */
class EAdminQuickLink extends CWidget
{

    /**
     * @var
     * use our own url the gridView and listView
     * getUrl sometimes give wrong result !
     */
    public static $url ;
    /**
     * @var string
     */
    public static $quickLinkCssClass = 'quick-link';
    /**
     * @var string
     */
    private $id = 'quickLink';
    /**
     * @var string
     */
    public $label = '^top';

    /**
     * @var int
     */
    public static $counter = 1;


    /**
     * @var array
     */
    public $linkOptions = array();

    /**
     * @var CActiveRecord
     */
    public $model;
    /**
     * @var array
     * used for assigning model attributes
     * please refer the $model->search() method for detail
     * eg:
     *  array(
     *     'uid'=>'>1',
     *     'email'=>'yiqing'
     * );
     * ---------------------------------------------
     * (<, <=, >, >=, <> or =) can be as the prefix of value
     * ---------------------------------------------
     */
    public $attributes = array();

    /**
     * @var string
     * the gridView or listView's container selector
     */
    public $itemsViewContainer = 'body';

    /**
     * @return void
     */
    public function init(){

        $this->id .= '_' . self::$counter++;

        $attributes = array();
        foreach ($this->attributes as $attr => $val) {
            $attributes[CHtml::resolveName($this->model, $attr)] = $val;
            //unset($array[$attr]);
        }
        $this->linkOptions['queryParams'] = CJSON::encode($attributes);
        if (isset($this->linkOptions['class'])) {
            $this->linkOptions['class'] .= ' ' . self::$quickLinkCssClass;
        } else {
            $this->linkOptions['class'] = self::$quickLinkCssClass;
        }

        echo CHtml::link($this->label, '#', CMap::mergeArray(array('id' => $this->id), $this->linkOptions));


        $quickLinkCssClass = self::$quickLinkCssClass;

        if(self::$url == null){
            $controller = Yii::app()->getController();
           self::$url =  $url =  Yii::app()->createUrl($controller->getRoute(),$_GET);
        }else{
            $url = self::$url ;
        }
        $jsSetUp = <<<JS
           $(".{$quickLinkCssClass}").on("click",function() {
               var queryParams = $(this).attr("queryParams");
                queryParams = jQuery.parseJSON(queryParams);
                var queryStr = jQuery.param(queryParams);
                itemsViewUpdate({data:queryStr,url:"{$url}"},"{$this->itemsViewContainer}");
            });
JS;
        $this->registerClientScript();
        cs()->registerCoreScript('jquery')
            ->registerScript(__CLASS__,$jsSetUp,CClientScript::POS_READY);
    }


    /**
     * Registers necessary client scripts.
     */
    public function registerClientScript()
    {
        $jsFunc = <<<FUNC
         /**
         * listView or gridView update
         * @param options pass to the underlying ajax method
         * ajax(options);
         * ................................
         * itemsViewUpdate({data:xx=xx&jj=jj});
         * itemsViewUpdate({},listOrGridViewContainerSelector);
         * ................................
         */
        function itemsViewUpdate(options){
            //auto probe the gridView or listView type and update it!
            var listViewClass = '.list-view';
            var gridViewClass = '.grid-view';
            var viewContainerSelector  = 'body';
            if(arguments.length == 2){
                viewContainerSelector = arguments[1];
            }
            var XViewId ;
            if($(listViewClass,viewContainerSelector).size()>0){
                XViewId = $(listViewClass,viewContainerSelector).attr('id');
                $.fn.yiiListView.update(XViewId,options);
            }else if($(gridViewClass,viewContainerSelector).size()>0){
                XViewId = $(gridViewClass,viewContainerSelector).attr('id');
                $.fn.yiiGridView.update(XViewId,options );
            }
        }
FUNC;
        cs()->registerScript(__METHOD__, $jsFunc, CClientScript::POS_END);
    }
}