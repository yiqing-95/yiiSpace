<?php

/**
 * @author dufei22 <dufei22@gmail.com>
 * @link http://blog.soyoto.com/
 */
/**
 * @version 1.0
 * @author yiqing95 <yiqing_95@qq.com>
 *  this widget is same to CJuiTabs
 */
Yii::import('ext.dwz.DwzWidget');

/**
 *
 * <pre>
<?php $this->widget('ext.dwz.DwzTabs', array(
'tabs'=>array(
'标题1'=>'Html<br/>内容',
'标题2'=>$this->renderPartial('test',array(),true),
'ajaxTab'=>array('ajax'=>array('/admin/default/test','id'=>11)),
...
),
'height'=>100,
...
)); ?>
 * </pre>
 * 生成如下
<div class="tabs">
<div class="tabsHeader">
<div class="tabsHeaderContent">
<ul>
<li class="selected"><a href="#"><span>标题1</span></a></li>
<li><a href="#"><span>标题2</span></a></li>
</ul>
</div>
</div>
<div class="tabsContent" style="height:150px;">
<div>内容1</div>
<div>内容2</div>
</div>
<div class="tabsFooter">
<div class="tabsFooterContent"></div>
</div>
</div>
 *
 */
class DwzTabs extends DwzWidget
{
    /**
     * @var $tabs array Tabs项目 (标题=>内容).
     */
    public $tabs = array();
    /**
     * @var string
     */
    public $headerTemplate = '<li><a class="{cls}" href="{url}"><span>{title}</span></a></li>';
    /**
     * @var string
     */
    public $contentTemplate = '<div id="{id}">{content}</div>';
    /**
     * @var string
     */
    public $footer = '';
    /**
     * @var int 内容区的高度
     */
    public $height = 200;

    public function run()
    {
        parent::run();
        $id = $this->getId();
        $this->htmlOptions['id'] = $id;

        $headers = '';
        $contents = '';
        $countTab = 0;

        foreach ($this->tabs as $title => $content) {
            $tabId = (is_array($content) && isset($content['id'])) ? $content['id'] : $id . '_tab_' . $countTab++;
            if (!is_array($content)) {
                $headers .= strtr($this->headerTemplate, array('{title}' => $title, '{url}' => 'javascript:void(0)', '{cls}' => $tabId)) . "\n";
                $contents .= strtr($this->contentTemplate, array('{content}' => $content, '{id}' => $tabId)) . "\n";

            } elseif (isset($content['content'])) {
                $headers .= strtr($this->headerTemplate, array('{title}' => $title, '{url}' => 'javascript:void(0)', '{cls}' => $tabId)) . "\n";
                $contents .= strtr($this->contentTemplate, array('{content}' => $content['content'], '{id}' => $tabId)) . "\n";

            } elseif (isset($content['ajax'])) {
                $headers .= strtr($this->headerTemplate, array('{title}' => $title, '{url}' => CHtml::normalizeUrl($content['ajax']), '{cls}' => 'j-ajax')) . "\n";
                $contents .= "<div></div>\n";
            }
        }
        if (isset($this->htmlOptions['class']))
            $this->htmlOptions['class'] = 'tabs ' . $this->htmlOptions['class'];
        else
            $this->htmlOptions['class'] = 'tabs ';

        echo CHtml::openTag($this->tagName, $this->htmlOptions) . "\n";

        echo "<div class='tabsHeader'>\n<div class='tabsHeaderContent'>\n<ul>\n";
        echo $headers;
        echo "</ul>\n</div>\n</div>\n<div class='tabsContent' style='height:{$this->height}px;'>\n";
        echo $contents;
        echo "\n</div>\n<div class='tabsFooter'>\n<div class='tabsFooterContent'>{$this->footer}</div>\n</div>\n";

        echo CHtml::closeTag($this->tagName) . "\n";
    }
}