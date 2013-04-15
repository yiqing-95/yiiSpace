<?php
/**
 *
 * User: yiqing
 * Date: 13-4-15
 * Time: 下午3:52
 * To change this template use File | Settings | File Templates.
 * -------------------------------------------------------
 * -------------------------------------------------------
 */

class CascadeTabView extends CWidget
{
    /**
     * Default CSS class for the tab container
     */
    const CSS_CLASS = 'tabs';

    /**
     * @var mixed the CSS file used for the widget. Defaults to null, meaning
     * using the default CSS file included together with the widget.
     * If false, no CSS file will be used. Otherwise, the specified CSS file
     * will be included when using this widget.
     */
    public $cssFile;
    /**
     * @var string the ID of the tab that should be activated when the page is initially loaded.
     * If not set, the first tab will be activated.
     */
    public $activeTab;
    /**
     * @var array the data that will be passed to the partial view rendered by each tab.
     */
    public $viewData;
    /**
     * @var array additional HTML options to be rendered in the container tag.
     */
    public $htmlOptions;
    /**
     * @var array tab definitions. The array keys are the IDs,
     * and the array values are the corresponding tab contents.
     * Each array value must be an array with the following elements:
     * <ul>
     * <li>title: the tab title. You need to make sure this is HTML-encoded.</li>
     * <li>content: the content to be displayed in the tab.</li>
     * <li>view: the name of the view to be displayed in this tab.
     * The view will be rendered using the current controller's
     * {@link CController::renderPartial} method.
     * When both 'content' and 'view' are specified, 'content' will take precedence.
     * </li>
     * <li>url: a URL that the user browser will be redirected to when clicking on this tab.</li>
     * <li>data: array (name=>value), this will be passed to the view when 'view' is specified.
     * This option is available since version 1.1.1.</li>
     * <li>visible: whether this tab is visible. Defaults to true.
     * this option is available since version 1.1.11.</li>
     * </ul>
     * <pre>
     * array(
     *     'tab1'=>array(
     *           'title'=>'tab 1 title',
     *           'view'=>'view1',
     *     ),
     *     'tab2'=>array(
     *           'title'=>'tab 2 title',
     *           'url'=>'http://www.yiiframework.com/',
     *     ),
     * )
     * </pre>
     */
    public $tabs = array();

    /**
     * Runs the widget.
     */
    public function run()
    {
        foreach ($this->tabs as $id => $tab)
            if (isset($tab['visible']) && $tab['visible'] == false)
                unset($this->tabs[$id]);

        if (empty($this->tabs))
            return;

        if ($this->activeTab === null || !isset($this->tabs[$this->activeTab])) {
            reset($this->tabs);
            list($this->activeTab,) = each($this->tabs);
        }

        $htmlOptions = $this->htmlOptions;
        $htmlOptions['id'] = $this->getId();
        if (!isset($htmlOptions['class']))
            $htmlOptions['class'] = self::CSS_CLASS;

        $this->registerClientScript();

        echo CHtml::openTag('div', $htmlOptions) . "\n";
        $this->renderHeader();
        $this->renderBody();
        echo CHtml::closeTag('div');
    }

    /**
     * Registers the needed CSS and JavaScript.
     */
    public function registerClientScript()
    {
        $cs = Yii::app()->getClientScript();
        $cs->registerCoreScript('yiitab');
        $id = $this->getId();
        $cs->registerScript('Yii.CTabView#' . $id, "jQuery(\"#{$id}\").yiitab();");

        if ($this->cssFile !== false)
            self::registerCssFile($this->cssFile);
    }

    /**
     * Registers the needed CSS file.
     * @param string $url the CSS URL. If null, a default CSS URL will be used.
     */
    public static function registerCssFile($url = null)
    {
        $cs = Yii::app()->getClientScript();
        if ($url === null)
            $url = $cs->getCoreScriptUrl() . '/yiitab/jquery.yiitab.css';
        $cs->registerCssFile($url, 'screen');
    }

    /**
     * Renders the header part.
     */
    protected function renderHeader()
    {
        echo "<ul class=\"nav\">\n";

        $ajaxTab = false ;

        foreach ($this->tabs as $id => $tab) {
            $title = isset($tab['title']) ? $tab['title'] : 'undefined';
            $active = $id === $this->activeTab ? ' class="active"' : '';

            $linkOptions = array();

            if (isset($tab['ajax'])) {
                $linkOptions['class'] = 'ajax';
                $linkOptions['ref'] = $id;
                $linkOptions['ajax-url'] = $tab['url'];

                $linkOptions['ajax-reload'] = isset($tab['reload'])? 'true' : 'false';

                $url = "#{$id}";
                // mark it  as ajax tab behavior !
                $ajaxTab = true ;
            } else {
                $url = isset($tab['url']) ? $tab['url'] : "#{$id}";
            }

            if(isset($tab['linkOptions'])){
                $linkOptions = CMap::mergeArray($linkOptions,$tab['linkOptions']);
            }

            $linkAttributes = empty($linkOptions)? '' : CHtml::renderAttributes($linkOptions);

            echo "<li><a href=\"{$url}\"{$active} {$linkAttributes} >{$title}</a></li>\n";
        }
        echo "</ul>\n";

        if($ajaxTab == true){
            $this->doAjaxLoad() ;
        }
    }

    /**
     * load the specified tab use ajax request
     */
    protected function doAjaxLoad(){
         $ajaxLoad = <<<JS_INIT
    $("li .ajax").on("click",function(){
      var that = this ;
      var tabId = $(this).attr("ref");
      var ajaxReload = ($(this).attr("ajax-reload") == "true");

       if((ajaxReload == false) && jQuery.trim($("#" + tabId).html()) != '' ){
           // alert("加载过来哦！");
            return ;
       }

       var url = $(this).attr("ajax-url");
       $("#" + tabId).append('loading ..........');
       $.get(url,function(response){
            var \$data = $('<div class=cell>' + response + '</div>');
            $("#" + tabId).empty().append(\$data);
       });
    });
JS_INIT;

        Yii::app()->clientScript->registerScript(__CLASS__.__METHOD__,$ajaxLoad,CClientScript::POS_READY);
    }

    /**
     * Renders the body part.
     */
    protected function renderBody()
    {
        foreach ($this->tabs as $id => $tab) {
            $inactive = $id !== $this->activeTab ? ' style="display:none"' : '';
            echo "<div class=\"tab-content \" id=\"{$id}\"{$inactive}>\n";
            if (isset($tab['content']))
                echo '<div class="cell">', $tab['content'], '</div>';
            elseif (isset($tab['view'])) {
                if (isset($tab['data'])) {
                    if (is_array($this->viewData))
                        $data = array_merge($this->viewData, $tab['data']);
                    else
                        $data = $tab['data'];
                } else
                    $data = $this->viewData;
                $this->getController()->renderPartial($tab['view'], $data);
            }
            echo "</div><!-- {$id} -->\n";
        }
    }
}