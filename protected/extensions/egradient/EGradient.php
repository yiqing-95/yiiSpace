<?php
/*
* Gradient Background extention
* author : pegel.linuxs@gmail.com
*/
class EGradient extends CWidget
{

	public $color1="#ffffff"; // first color
	public $color2="#000000"; // second color
	public $target="#target"; // target class or id
	public $style="toBottom"; // style gradient
	
	private $_style=array('toBottomRight','toBottom','toBottomLeft','toRight','toLeft','toTopRight','toTop','toTopLeft');
		
	public function init()
	{
		if (!in_array($this->style,$this->_style)) $this->style="toBottom";
	}
	
	public function run()
	{
		switch ($this->style) {
			case 'toBottomRight':
				$css = $this->toBottomRight($this->target,$this->color1,$this->color2);
				break;
			case 'toBottom':
				$css = $this->toBottom($this->target,$this->color1,$this->color2);
				break;
			case 'toBottomLeft':
				$css = $this->toBottomLeft($this->target,$this->color1,$this->color2);
				break;	
			case 'toRight':
				$css = $this->toRight($this->target,$this->color1,$this->color2);
				break;
			case 'toLeft':
				$css = $this->toLeft($this->target,$this->color1,$this->color2);
				break;
			case 'toTopRight':
				$css = $this->toTopRight($this->target,$this->color1,$this->color2);
				break;	
			case 'toTop':
				$css = $this->toTop($this->target,$this->color1,$this->color2);
				break;
			case 'toTopLeft':
				$css = $this->toTopLeft($this->target,$this->color1,$this->color2);
				break;		
			default:	
				$css = $this->toBottom($this->target,$this->color1,$this->color2);
		}

		$cs=Yii::app()->clientScript;
		$cs->registerCss(__CLASS__.$this->id,"
			{$css}
		",'all');
	}
	
	private function toBottomRight($target,$color1,$color2)
	{
		return "
		{$target}{
		background-image: -ms-linear-gradient(top left, {$color1} 0%, {$color2} 100%);
		background-image: -moz-linear-gradient(top left, {$color1} 0%, {$color2} 100%);
		background-image: -o-linear-gradient(top left, {$color1} 0%, {$color2} 100%);
		background-image: -webkit-gradient(linear, left top, right bottom, color-stop(0, {$color1}), color-stop(1, {$color2}));
		background-image: -webkit-linear-gradient(top left, {$color1} 0%, {$color2} 100%);
		background-image: linear-gradient(to bottom right, {$color1} 0%, {$color2} 100%);
		}
		";
	}
	
	private function toBottom($target,$color1,$color2)
	{
		return "
		{$target}{
		background-image: -ms-linear-gradient(top, {$color1} 0%, {$color2} 100%);
		background-image: -moz-linear-gradient(top, {$color1} 0%, {$color2} 100%);
		background-image: -o-linear-gradient(top, {$color1} 0%, {$color2} 100%);
		background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0, {$color1}), color-stop(1, {$color2}));
		background-image: -webkit-linear-gradient(top, {$color1} 0%, {$color2} 100%);
		background-image: linear-gradient(to bottom, {$color1} 0%, {$color2} 100%);
		}
		";
	}
	
	private function toBottomLeft($target,$color1,$color2)
	{
		return "
		{$target}{
		background-image: -ms-linear-gradient(top right, {$color1} 0%, {$color2} 100%);
		background-image: -moz-linear-gradient(top right, {$color1} 0%, {$color2} 100%);
		background-image: -o-linear-gradient(top right, {$color1} 0%, {$color2} 100%);
		background-image: -webkit-gradient(linear, right top, left bottom, color-stop(0, {$color1}), color-stop(1, {$color2}));
		background-image: -webkit-linear-gradient(top right, {$color1} 0%, {$color2} 100%);
		background-image: linear-gradient(to bottom left, {$color1} 0%, {$color2} 100%);
		}
		";
	}
	
	private function toRight($target,$color1,$color2)
	{
		return "
		{$target}{	
		background-image: -ms-linear-gradient(left, {$color1} 0%, {$color2} 100%);
		background-image: -moz-linear-gradient(left, {$color1} 0%, {$color2} 100%);
		background-image: -o-linear-gradient(left, {$color1} 0%, {$color2} 100%);
		background-image: -webkit-gradient(linear, left top, right top, color-stop(0, {$color1}), color-stop(1, {$color2}));
		background-image: -webkit-linear-gradient(left, {$color1} 0%, {$color2} 100%);
		background-image: linear-gradient(to right, {$color1} 0%, {$color2} 100%);
		}
		";
	}

	private function toLeft($target,$color1,$color2)
	{
		return "
		{$target}{	
		background-image: -ms-linear-gradient(right, {$color1} 0%, {$color2} 100%);
		background-image: -moz-linear-gradient(right, {$color1} 0%, {$color2} 100%);
		background-image: -o-linear-gradient(right, {$color1} 0%, {$color2} 100%);
		background-image: -webkit-gradient(linear, right top, left top, color-stop(0, {$color1}), color-stop(1, {$color2}));
		background-image: -webkit-linear-gradient(right, {$color1} 0%, {$color2} 100%);
		background-image: linear-gradient(to left, {$color1} 0%, {$color2} 100%);
		}
		";
	}
	
	private function toTopRight($target,$color1,$color2)
	{
		return "
		{$target}{
		background-image: -ms-linear-gradient(bottom left, {$color1} 0%, {$color2} 100%);
		background-image: -moz-linear-gradient(bottom left, {$color1} 0%, {$color2} 100%);
		background-image: -o-linear-gradient(bottom left, {$color1} 0%, {$color2} 100%);
		background-image: -webkit-gradient(linear, left bottom, right top, color-stop(0, {$color1}), color-stop(1, {$color2}));
		background-image: -webkit-linear-gradient(bottom left, {$color1} 0%, {$color2} 100%);
		background-image: linear-gradient(to top right, {$color1} 0%, {$color2} 100%);
		}
		";
	}
		
	private function toTop($target,$color1,$color2)
	{
		return "
		{$target}{
		background-image: -ms-linear-gradient(bottom, {$color1} 0%, {$color2} 100%);
		background-image: -moz-linear-gradient(bottom, {$color1} 0%, {$color2} 100%);
		background-image: -o-linear-gradient(bottom, {$color1} 0%, {$color2} 100%);
		background-image: -webkit-gradient(linear, left bottom, left top, color-stop(0, {$color1}), color-stop(1, {$color2}));
		background-image: -webkit-linear-gradient(bottom, {$color1} 0%, {$color2} 100%);
		background-image: linear-gradient(to top, {$color1} 0%, {$color2} 100%);
		}
		";
	}	

	private function toTopLeft($target,$color1,$color2)
	{
		return "
		{$target}{
		background-image: -ms-linear-gradient(bottom right, {$color1} 0%, {$color2} 100%);
		background-image: -moz-linear-gradient(bottom right, {$color1} 0%, {$color2} 100%);
		background-image: -o-linear-gradient(bottom right, {$color1} 0%, {$color2} 100%);
		background-image: -webkit-gradient(linear, right bottom, left top, color-stop(0, {$color1}), color-stop(1, {$color2}));
		background-image: -webkit-linear-gradient(bottom right, {$color1} 0%, {$color2} 100%);
		background-image: linear-gradient(to top left, {$color1} 0%, {$color2} 100%);
		}
		";
	}	
}


