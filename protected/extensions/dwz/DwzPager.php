<?php

/**
 * @version 0.1
 * @author dufei22 <dufei22@gmail.com>
 * @link http://blog.soyoto.com/
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
 * 生成如下:
	<div class="panelBar">
		<div class="pages">
			<span>显示</span>
			<select name="numPerPage" onchange="navTabPageBreak({numPerPage:this.value})">
				<option value="20">20</option>
				<option value="50">50</option>
				<option value="100">100</option>
				<option value="200">200</option>
			</select>
			<span>条，共${totalCount}条</span>
		</div>
		<div class="pagination" targetType="navTab" totalCount="200" numPerPage="20" pageNumShown="10" currentPage="1"></div>
	</div>
 * 
 */
class DwzPager extends CBasePager
{
	//每页显示数
	public $numPerPageList=array('10'=>10,'20'=>20,'50'=>50,'100'=>100);
	//显示的位置navTab或Dialog
	public $targetType='navTab';
	//每页显示多少条
	public $numPerPage='10';
	//翻页标示个数
	public $pageNumShown="5";
	//外层是否要包一层div
	public $showWrap=false;
	/**
	 * @var array 最外层的html选项
	 */
	public $htmlOptions=array();
	//

	/**
	 * 初始化一些默认设置
	 */
	public function init()
	{
		if (!isset($this->htmlOptions['id']))
			$this->htmlOptions['id']=$this->getId();
		if (!isset($this->htmlOptions['class']))
			$this->htmlOptions['class']='panelBar';
		if (isset($_REQUEST['numPerPage']))
			$this->numPerPage=(int)$_REQUEST['numPerPage'];

		$this->setPageSize($this->numPerPage);
	}

	/**
	 * 
	 */
	public function run()
	{
		$currentPage=$this->getCurrentPage()+1;
		if ($this->showWrap)
			echo CHtml::openTag('div',$this->htmlOptions);
		echo CHtml::beginForm(Yii::app()->getRequest()->getRequestUri(), 'POST', array('id'=>'pagerForm'));
		echo CHtml::hiddenField('pageNum', 1);
		echo CHtml::hiddenField('numPerPage', $this->getPageSize());
		echo CHtml::endForm();
		$this->outSelect();
		echo "<div class='pagination' targetType='{$this->targetType}' totalCount='{$this->getItemCount()}'
				numPerPage='{$this->getPageSize()}' pageNumShown='{$this->pageNumShown}' currentPage='{$currentPage}'></div>";
		if ($this->showWrap)
			echo CHtml::closeTag('div');
	}
	/**
	 * 输出每页显示条数的select框
	 */
	protected  function outSelect(){
		$pageCount=ceil($this->getItemCount()/$this->getPageSize());
		echo "<div class='pages'>\n<span>显示</span>\n";
		echo CHtml::dropDownList('numPerPage', $this->getPageSize(), $this->numPerPageList, array('onchange'=>'navTabPageBreak({numPerPage:this.value})'));
		echo "<span>条，共{$this->getItemCount()}条,{$pageCount}页</span>\n</div>\n";
	}
}