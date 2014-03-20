<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 13-1-8
 * Time: 上午12:49
 * To change this template use File | Settings | File Templates.
 */
class TbJumpPager extends CBasePager
{

    /**
     * @var string the text shown before page buttons. Defaults to 'Go to page: '.
     */
    public $prependLabel = 'jump to';

    /**
     * @var string the text shown after page buttons.
     */
    public $appendLabel = 'go';


    public $pageSizeTpl = 999 ;

    /**
     * @var string
     */
    public $viewContainer = 'body';

    /**
     * @var string name of the GET variable storing the current page index. Defaults to 'page'.
     * ------------------------------------
     * should be same as your pagination 's
     * ------------------------------------
     */
    public $pageVar='page';

    /**
     * @var bool
     */
    public $ajaxUpdate = false;

    /**
     * @var string
     */
    public $cssClass = __CLASS__;


    /**
     *
     */
    public function init(){
       $this->pageVar = $this->pages->pageVar;
    }

    /**
     * @var array
     */
    /**
     * Executes the widget.
     * This overrides the parent implementation by displaying the generated page buttons.
     */
    public function run()
    {
       /*
        * must register the js firstly !!!
        if (($pageCount = $this->getPageCount()) <= 1)
            return;
        */

        /**
         * the created pagerNumber will be 1000
         */
        $pageUrlTemplate = $this->createPageUrl($this->pageSizeTpl);

        $thisCssClass = $this->cssClass ;

        $jsNameSpace = __CLASS__ ;

        $jumpForm = <<<EOD
        <div class="input-prepend input-append {$thisCssClass}" >
            <span class="add-on">{$this->prependLabel}</span>
             <input class="span2" type="text"/>
            <button class="btn" onclick="{$jsNameSpace}.goPage(this)" type="button" page-url="{$pageUrlTemplate}">{$this->appendLabel}</button>
        </div>

EOD;
        echo $jumpForm;
        $pageSizeTpl = $this->pageSizeTpl +1 ;

        $ajaxUpdate = CJavaScript::encode($this->ajaxUpdate);


        $js = <<<ON_JUMP
        var {$jsNameSpace} = {};
        {$jsNameSpace}.goPage = function(el){
           var toPage = $(el).prev().val();
               var ajaxUpdate = {$ajaxUpdate};
                    var er = /^[0-9]+$/;
                    if(er.test(toPage)){
                        if(ajaxUpdate){
                           jumpToPageAjax(toPage)
                        }else{
                            jumpToPage(toPage);
                        }
                        /*
                         var pageUrl = $(this).attr("page-url");
                         pageUrl = pageUrl.replace('{$pageSizeTpl}',toPage);
                         //alert(pageUrl);
                         window.location = pageUrl;
                         */
                    }else{
                         alert("must be a number");
                    }
        }
        function jumpToPage(page){
            //probe the gridView or listView id
            var listViewClass = '.list-view';
            var gridViewClass = '.grid-view';
            var viewContainerSelector  = "{$this->viewContainer}";
            var XViewId;
            var pageUrl ;
            if ($(listViewClass,  $(viewContainerSelector)).size() > 0) {
                XViewId = $(listViewClass, $(viewContainerSelector)).attr('id');
                 pageUrl = $.fn.yiiListView.getUrl(XViewId);

                //handle the url String ,  pathInfo and queryString
                pageUrl = pageUrl.replace(/{$this->pageVar}\/\d+/, "{$this->pageVar}/"+page);
                pageUrl = pageUrl.replace(/{$this->pageVar}=\d+/, "{$this->pageVar}="+page);
            } else if ($(gridViewClass,  $(viewContainerSelector)).size() > 0) {
                XViewId = $(gridViewClass,  $(viewContainerSelector)).attr('id');
                pageUrl = $.fn.yiiGridView.getUrl(XViewId);
                //handle the url String ,  pathInfo and queryString
                pageUrl = pageUrl.replace(/{$this->pageVar}\/\d+/, "{$this->pageVar}/"+page);
                pageUrl = pageUrl.replace(/{$this->pageVar}=\d+/, "{$this->pageVar}="+page);
            }
            window.location = pageUrl;
        }
        function jumpToPageAjax(page){
            //probe the gridView or listView id
            var listViewClass = '.list-view';
            var gridViewClass = '.grid-view';
            var viewContainerSelector  = "{$this->viewContainer}";
            var XViewId;
            if ($(listViewClass,  $(viewContainerSelector)).size() > 0) {
                XViewId = $(listViewClass, $(viewContainerSelector)).attr('id');
                var url = $.fn.yiiListView.getUrl(XViewId);

                //handle the url String ,  pathInfo and queryString
                url = url.replace(/{$this->pageVar}\/\d+/, "{$this->pageVar}/"+page);
                url = url.replace(/{$this->pageVar}=\d+/, "{$this->pageVar}="+page);

                $.fn.yiiListView.update(XViewId,{url:url,data:{"{$this->pageVar}":page }} );

            } else if ($(gridViewClass,  $(viewContainerSelector)).size() > 0) {
                XViewId = $(gridViewClass,  $(viewContainerSelector)).attr('id');
                var url = $.fn.yiiGridView.getUrl(XViewId);
                //handle the url String ,  pathInfo and queryString
                url = url.replace(/{$this->pageVar}\/\d+/, "{$this->pageVar}/"+page);
                url = url.replace(/{$this->pageVar}=\d+/, "{$this->pageVar}="+page);

                $.fn.yiiGridView.update(XViewId,{url:url,data:{"{$this->pageVar}":page }} );
            }
        }

ON_JUMP;
        cs()->registerScript(__CLASS__,$js,CClientScript::POS_END);
        /*
          if(Yii::app()->getRequest()->getIsAjaxRequest()){
             echo CHtml::script($js);
          }else{
              cs()->registerScript(__CLASS__,$js,CClientScript::POS_END);
          }*/
    }

}