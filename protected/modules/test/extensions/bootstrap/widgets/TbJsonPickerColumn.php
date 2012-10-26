<?php
/**
 * TbJsonPickerColumn class
 *
 * The TbJsonPickerColumn works with TbJsonGridView and allows you to create a column that will display a picker element
 * The picker is a special plugin that renders a dropdown on click
 *
 * @author: antonio ramirez <antonio@clevertech.biz>
 * Date: 8/20/12
 * Time: 8:44 PM
 */
class TbJsonPickerColumn extends TbJsonDataColumn
{
	public $class = 'picker';

	public $pickerOptions = array();

	public function init()
	{
		if (!$this->class)
			$this->class = 'picker';
		$this->registerClientScript();
	}

	public function renderDataCellContent($row, $data)
	{

		if ($this->value !== null)
			$value = $this->evaluateExpression($this->value, array('data' => $data, 'row' => $row));
		else if ($this->name !== null)
			$value = CHtml::value($data, $this->name);

		$class = preg_replace('/\s+/', '.', $this->class);
		$value = $value === null ? $this->grid->nullDisplay : $this->grid->getFormatter()->format($value, $this->type);
		$value = CHtml::link($value, '#', array('class' => $class));

		if ($this->grid->json)
		{
			return $value;
		}
		echo $value;
	}

	public function registerClientScript()
	{

		$class = preg_replace('/\s+/', '.', $this->class);

		$cs = Yii::app()->getClientScript();
		$assetsUrl = Yii::app()->getAssetManager()->publish(dirname(__FILE__) . '/../assets', false, -1, true);

		$cs->registerCssFile($assetsUrl . '/css/bootstrap-picker.css');
		$cs->registerScriptFile($assetsUrl . '/js/bootstrap.picker.js');
		$cs->registerScript(__CLASS__ . '#' . $this->id, "$(document).on('click','#{$this->grid->id} a.{$class}', function(){
			if($(this).hasClass('pickeron'))
			{
				$(this).removeClass('pickeron').picker('toggle');
				return;
			}
			$('#{$this->grid->id} a.pickeron').removeClass('pickeron').picker('toggle');
			$(this).picker(" . CJavaScript::encode($this->pickerOptions) . ").picker('toggle').addClass('pickeron'); return false;
		})");
	}

}