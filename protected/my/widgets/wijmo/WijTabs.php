<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-4-10
 * Time: 下午2:35
 *------------------------------------------------------------
 *                 _            _
 *                (_)          (_)
 *        _   __  __   .--. _  __   _ .--.   .--./)
 *       [ \ [  ][  |/ /'`\' ][  | [ `.-. | / /'`\;
 *        \ '/ /  | || \__/ |  | |  | | | | \ \._//
 *      [\_:  /  [___]\__.; | [___][___||__].',__`
 *       \__.'            |__]             ( ( __))
 *
 *--------------------------------------------------------------
 * compare to yii CJuiTabs  this widget doesn't support ajax loading .
 *
 * --------------------------------------------------------------
 */
class WijTabs extends EWijmoWidget
{
    public function init(){
        parent::init();
        $this->registerCssFile('themes/wijmo/jquery.wijmo.wijtabs.css');
        $this->registerScriptFile('wijmo/jquery.wijmo.wijtabs.js',CClientScript::POS_HEAD);
    }

    public function run(){
        //如果没有传递tabs数组 那么认为想手动初始化该widget
        if(! empty($this->tabs)){
            $this->renderTabs();
        }
        parent::run();
    }
    //-------------------<下面是版本二添加的东东>----------------------------------------------------------------
    /**
     * @var array list of tabs (tab title=>tab content).
     * Note that the tab title will not be HTML-encoded.
     * The tab content can be either a string or an array. When it is an array, it can
     * be in one of the following two formats:
     * <pre>
     * array('id'=>'myTabID', 'content'=>'tab content')
     * array('id'=>'myTabID', 'ajax'=>URL)
     * </pre>
     * where the 'id' element is optional. The second format allows the tab content
     * to be dynamically fetched from the specified URL via AJAX. The URL can be either
     * a string or an array. If an array, it will be normalized into a URL using {@link CHtml::normalizeUrl}.
     */
    public $tabs=array();
    /**
     * @var string the name of the container element that contains all panels. Defaults to 'div'.
     */
    public $tagName='div';
    /**
     * @var array
     */
    public $htmlOptions = array();
    /**
     * @var string the template that is used to generated every panel title.
     * The token "{title}" in the template will be replaced with the panel title and
     * the token "{url}" will be replaced with "#TabID" or with the url of the ajax request.
     */
    public $headerTemplate='<li><a href="{url}" title="{id}">{title}</a></li>';
    /**
     * @var string the template that is used to generated every tab content.
     * The token "{content}" in the template will be replaced with the panel content
     * and the token "{id}" with the tab ID.
     */
    public $contentTemplate='<div id="{id}">{content}</div>';

    /**
     *
     */
    public function renderTabs(){
        $id = $this->getId();
        if (isset($this->htmlOptions['id'])){
            $id = $this->htmlOptions['id'];
        }else{
            $this->htmlOptions['id']=$id;
        }
        echo CHtml::openTag($this->tagName,$this->htmlOptions)."\n";

        $tabsOut = "";
        $contentOut = "";
        $tabCount = 0;

        foreach($this->tabs as $title=>$content)
        {
            $tabId = (is_array($content) && isset($content['id']))?$content['id']:$id.'_tab_'.$tabCount++;

            if (!is_array($content))
            {
                $tabsOut .= strtr($this->headerTemplate, array('{title}'=>$title, '{url}'=>'#'.$tabId, '{id}'=>'#' . $tabId))."\n";
                $contentOut .= strtr($this->contentTemplate, array('{content}'=>$content,'{id}'=>$tabId))."\n";
            }/* elseif (isset($content['ajax']))
            {
                $tabsOut .= strtr($this->headerTemplate, array('{title}'=>$title, '{url}'=>CHtml::normalizeUrl($content['ajax']), '{id}'=>'#' . $tabId))."\n";
            }*/  else
            {
                $tabsOut .= strtr($this->headerTemplate, array('{title}'=>$title, '{url}'=>'#'.$tabId, '{id}'=>$tabId))."\n";
                if(isset($content['content']))
                    $contentOut .= strtr($this->contentTemplate, array('{content}'=>$content['content'],'{id}'=>$tabId))."\n";
            }
        }
        echo "<ul>\n" . $tabsOut . "</ul>\n";
        echo $contentOut;

        echo CHtml::closeTag($this->tagName)."\n";

        $this->selector = '#'.$id;
    }
}