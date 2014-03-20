<?php

/**
 * @version 0.1
 * @author dufei22 <dufei22@gmail.com>
 * @link http://blog.soyoto.com/
 */

Yii::import('ext.dwz.DwzWidget');
/**
 * 使用方法
	<?php $this->widget('ext.dwz.DwzPanel',array(
		'title'=>'Panel标题',
		'content'=>'Panel内容',
		...
	)); ?>
 * 生成
 * <pre>
	<div class="panel close collapse" defH="100">
		<h1>标题</h1>
		<div>内容</div>
	</div>
 * </pre>
 * 
 */
class DwzPanel extends DwzWidget
{
	//标题
	public $title;
	//内容
	public $content;
	//默认内容区高度
	public $height=300;
	//初始状态是否是关闭状态，默认false是打开状态
	public $close=false;
	//是否可折叠，默认是true是可折叠
	public $collapse=true;
	
	public function run()
	{
		$class='panel';
		if ($this->close)
			$class.=' close';
		if ($this->collapse)
			$class.=' collapse';

		if (isset($this->htmlOptions['class']))
			$this->htmlOptions['class']=$class.' '.$this->htmlOptions['class'];
		else
			$this->htmlOptions['class']=$class;

		$this->htmlOptions['defH']=$this->height;
		echo CHtml::openTag($this->tagName,$this->htmlOptions)."\n";

		echo "<h1>{$this->title}</h1>\n";
		echo "<div>{$this->content}</div>\n";

		echo CHtml::closeTag($this->tagName)."\n";
	}
}