<?php

/**
 * @version 0.1
 * @author dufei22 <dufei22@gmail.com>
 * @link http://blog.soyoto.com/
 */

Yii::import('ext.dwz.DwzWidget');

/**
 * 使用方法
 * <pre>
	<?php $this->widget('ext.dwz.DwzNavTab', array(
		'tabs'=>array(
			'管理区首页'=>$this->renderPartial('index',null,true),
			...
		),
		...
	)); ?>
 * </pre>
 * 生成如下
	<div id="navTab" class="tabsPage">
		<div class="tabsPageHeader">
			<div class="tabsPageHeaderContent">
				<ul class="navTab-tab">
					<li tabid="main" class="main"><a href="#"><span><span class="home_icon">管理区主页</span></span></a></li>
				</ul>
			</div>
			<div class="tabsLeft">left</div>
			<div class="tabsRight">right</div>
			<div class="tabsMore">more</div>
		</div>
		<ul class="tabsMoreList">
			<li><a href="javascript:void(0)">管理区主页</a></li>
		</ul>
		<div class="navTab-panel tabsPageContent" id="navTab-default">
			<?php echo $content; ?>
		</div>
	</div>
 * 
 */
class DwzNavTab extends DwzWidget
{
	/**
	 * @var $tabs array NavTab初始显示项目 (标题=>内容).
	 */
	public $tabs= array();

	public $headerTemplate= '<li tabid="{tid}" class="main"><a href="#"><span><span class="home_icon">{title}</span></span></a></li>';
	public $listTemplate=   '<li><a href="javascript:void(0)">{title}</a></li>';

	public function run()
	{
		parent::run();
		$headers='';
		$lists='';
		$contents='';
		$countTab=0;

		foreach ($this->tabs as $title=>$content){
			$tabid= 'main'.$countTab++;
			$headers.= strtr($this->headerTemplate, array('{tid}'=>$tabid,'{title}'=>$title))."\n";
			$lists  .= strtr($this->listTemplate, array('{title}'=>$title))."\n";
			$contents.= '<div>'.$content."</div>\n";
		}
		$this->htmlOptions['class']=trim('tabsPage '.$this->htmlOptions['class']);
		$this->htmlOptions['id']= 'navTab';
		echo CHtml::openTag($this->tagName,$this->htmlOptions)."\n";
		
		echo "<div class='tabsPageHeader'>\n<div class='tabsPageHeaderContent'>\n<ul class='navTab-tab'>\n";
		echo $headers;
		echo "\n</ul>\n</div>\n<div class='tabsLeft'>left</div>\n<div class='tabsRight'>right</div>\n<div class='tabsMore'>more</div>\n</div>\n<ul class='tabsMoreList'>\n";
		echo $lists;
		echo "\n</ul>\n<div class='navTab-panel tabsPageContent' id='navTab-default'>\n", $contents, "\n</div>\n";
		
		echo CHtml::closeTag($this->tagName)."\n";
	}
}