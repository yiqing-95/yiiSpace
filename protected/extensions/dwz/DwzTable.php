<?php
/**
 * 这个相比DwzGrid要轻量一点，适合之列表数据，DwzGrid就是用来替代这个的，
 * 但是由于DWZ的原因DwzGrid并不含有过滤和排序的功能，和CGridView功能完全相同的DwzGridView.
 * 
 * @version 0.1
 * @author dufei22 <dufei22@gmail.com>
 * @link http://blog.soyoto.com/
 */


Yii::import('zii.widgets.grid.CDataColumn');
Yii::import('ext.dwz.DwzWidget');
Yii::import('ext.dwz.DwzPager');


/**
 * 这个类大量使用了zii.widgets.Grid中的代码,主要生成DWZ风格的表格，也可以使用按钮列，多选钮列等。
 * 没有排序过滤等功能，要使用这些功能请继续使用用CGridView。
 * 一般只需要设置$dataProvider和$columns即可，前一个是设置数据来源后一个是要显示的列，同CGridView。
 * 使用方法：
    $this->widget('ext.dwz.DwzTable', array(
		'dataProvider'=>$model->search(),
		'columns'=>array(
			'id',
			'name',
			array(
				'name'=>'content',
				'headerHtmlOptions'=>array('style'=>'width:320px'),
			),
			array(
				'class'=>'CButtonColumn',
				'header'=>'操作',
				'headerHtmlOptions'=>array('style'=>'width:50px'),
				'viewButtonOptions'=>array('target'=>'dialog'),
				'updateButtonOptions'=>array('target'=>'dialog'),
			),
		),
    ));
 */
class DwzTable extends DwzWidget
{
	/**
	 * @var IDataProvider 数据来源
	 */
	public $dataProvider;
	public $columns=array();
	public $tableHtmlOptions=array('class'=>'table','layoutH'=>48);
	public $pager=array('class'=>'DwzPager','showWrap'=>true);
	public $enablePagination=true;

	public $_formatter;
	public $nullDisplay='&nbsp;';
	public $enableSorting=false;
	public $baseScriptUrl;
	public $selectableRows;
	
	public function init() {
		//parent::init();
		if($this->baseScriptUrl===null)
			$this->baseScriptUrl=Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('zii.widgets.assets')).'/gridview';
		$this->initColumns();
	}
	
	public function run() {
		parent::run();
		$this->renderItems();
		$this->renderPager();
	}
	/**
	 * Creates column objects and initializes them.
	 */
	protected function initColumns()
	{
		if($this->columns===array())
		{
			if($this->dataProvider instanceof CActiveDataProvider)
				$this->columns=$this->dataProvider->model->attributeNames();
			else if($this->dataProvider instanceof IDataProvider)
			{
				// use the keys of the first row of data as the default columns
				$data=$this->dataProvider->getData();
				if(isset($data[0]) && is_array($data[0]))
					$this->columns=array_keys($data[0]);
			}
		}
		$id=$this->getId();
		foreach($this->columns as $i=>$column)
		{
			if(is_string($column))
				$column=$this->createDataColumn($column);
			else
			{
				if(!isset($column['class']))
					$column['class']='CDataColumn';
				$column=Yii::createComponent($column, $this);
			}
			if(!$column->visible)
			{
				unset($this->columns[$i]);
				continue;
			}
			if($column->id===null)
				$column->id=$id.'_c'.$i;
			$this->columns[$i]=$column;
		}

		foreach($this->columns as $column)
			$column->init();
	}
	/**
	 * Creates a {@link CDataColumn} based on a shortcut column specification string.
	 * @param string the column specification string
	 * @return CDataColumn the column instance
	 */
	protected function createDataColumn($text)
	{
		if(!preg_match('/^([\w\.]+)(:(\w*))?(:(.*))?$/',$text,$matches))
			throw new CException(Yii::t('zii','The column must be specified in the format of "Name:Type:Label", where "Type" and "Label" are optional.'));
		$column=new CDataColumn($this);
		$column->name=$matches[1];
		if(isset($matches[3]))
			$column->type=$matches[3];
		if(isset($matches[5]))
			$column->header=$matches[5];
		return $column;
	}

	public function renderItems()
	{
		if($this->dataProvider->getItemCount()>0 || $this->showTableOnEmpty)
		{
			//echo "<table class=\"{$this->TableCssClass}\">\n";
			echo CHtml::openTag('table', $this->tableHtmlOptions);
			$this->renderTableHeader();
			$this->renderTableFooter();
			$this->renderTableBody();
			echo CHtml::closeTag('table');
			//echo "</table>";
		}
		else
			$this->renderEmptyText();
	}

	/**
	 * Renders the table header.
	 */
	public function renderTableHeader()
	{
		echo "<thead>\n";
		echo "<tr>\n";
		foreach($this->columns as $column)
			$column->renderHeaderCell();
		echo "</tr>\n";
		echo "</thead>\n";
	}

	/**
	 * Renders the table footer.
	 */
	public function renderTableFooter()
	{
		$hasFooter=$this->getHasFooter();
		if($hasFooter)
		{
			echo "<tfoot>\n";
			if($hasFooter)
			{
				echo "<tr>\n";
				foreach($this->columns as $column)
					$column->renderFooterCell();
				echo "</tr>\n";
			}
			echo "</tfoot>\n";
		}
	}

	/**
	 * Renders the table body.
	 */
	public function renderTableBody()
	{
		$data=$this->dataProvider->getData();
		$n=count($data);
		echo "<tbody>\n";

		if($n>0)
		{
			for($row=0;$row<$n;++$row)
				$this->renderTableRow($row);
		}
		else
		{
			echo '<tr><td colspan="'.count($this->columns).'">';
			$this->renderEmptyText();
			echo "</td></tr>\n";
		}
		echo "</tbody>\n";
	}
	/**
	 * Renders a table body row.
	 * @param integer the row number (zero-based).
	 */
	public function renderTableRow($row)
	{
		echo '<tr>';
		foreach($this->columns as $column)
			$column->renderDataCell($row);
		echo "</tr>\n";
	}
	/**
	 * @return boolean whether the table should render a footer.
	 * This is true if any of the {@link columns} has a true {@link CGridColumn::hasFooter} value.
	 */
	public function getHasFooter()
	{
		foreach($this->columns as $column)
			if($column->getHasFooter())
				return true;
		return false;
	}

	/**
	 * Renders the pager.
	 */
	public function renderPager()
	{
		if(!$this->enablePagination)
			return;

		$pager=array();
		if(is_string($this->pager))
			$class=$this->pager;
		else if(is_array($this->pager))
		{
			$pager=$this->pager;
			if(isset($pager['class']))
			{
				$class=$pager['class'];
				unset($pager['class']);
			}
		}
		$pager['pages']=$this->dataProvider->getPagination();

		if($pager['pages']->getPageCount()>1)
		{
			$this->widget($class,$pager);
		}
		else
			$this->widget($class,$pager);
	}

	/**
	 * 没有数据的时候返回的文本
	 */
	public function renderEmptyText()
	{
		$emptyText=$this->emptyText===null ? Yii::t('zii','No results found.') : $this->emptyText;
		echo CHtml::tag('span', array('class'=>'empty'), $emptyText);
	}
	/**
	 * @return CFormatter the formatter instance. Defaults to the 'format' application component.
	 */
	public function getFormatter()
	{
		if($this->_formatter===null)
			$this->_formatter=Yii::app()->format;
		return $this->_formatter;
	}

	/**
	 * @param CFormatter the formatter instance
	 */
	public function setFormatter($value)
	{
		$this->_formatter=$value;
	}
}
