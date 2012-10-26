<?php
/**
 * Simple widjet for selecting page size of gridviews
 *
 * @author	Aruna Attanayake <aruna470@gmail.com>
 * @version 1.0
 */

class PageSize extends CWidget
{
	public $mPageSizeOptions = array(10=>10, 25=>25, 50=>50, 75=>75, 100=>100);
	public $mPageSize = 10;
	public $mGridId = '';
	public $mDefPageSize = 10;
	
	public function run()
	{			
		Yii::app()->user->setState('pageSize', $this->mPageSize);
		
		$this->mPageSize = null == $this->mPageSize ? $this->mDefPageSize : $this->mPageSize;
		
		echo 'Perpage: ';
		echo CHtml::dropDownList('pageSize', $this->mPageSize, $this->mPageSizeOptions,array(
				'onchange'=>"$.fn.yiiGridView.update('$this->mGridId',{ data:{pageSize: $(this).val() }})",
		));
	}
}
?>