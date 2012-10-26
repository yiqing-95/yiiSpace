<?php
/*
 * Created on 04.09.2012

 *
 * Copyright: Syed Uzair Shah
 * Updated by: Syed Uzair Shah ( syeduzairahmad@live.com)
 *
 * GNU LESSER GENERAL PUBLIC LICENSE
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU lesser General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * Requirements:
 * The CK-Editor have to be installed and configured. The Editor itself is
 * not included to this extension.
 *
 * This extension have to be installed into:
 * <Yii-Application>/protected/extensions/
 *
 *
 */

class ELightBoxContentWidget extends CWidget
{
	public $classname;
	public $divid;
	public $width;
	public $css;
	public $content;
	public $mapdivid;
	public $address;
	public $label;
	public $linklabel;
	public function init()
	{
		if($this->classname==="" || $this->divid==="")
		{
			throw new CException(" Class name and id required");
		}
		
		
	}
	public function run()
	{

		$cs = Yii::app()->getClientScript();
		
		$assets = Yii::app()->getAssetManager()->publish(dirname(__FILE__).DIRECTORY_SEPARATOR.'assets');
		
		$cs->registerCssFile($assets."/colorbox".'.css');
		$cs->registerScriptFile("https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js",CClientScript::POS_END);
		$cs->registerScriptFile($assets."/jquery.colorbox".'.js',CClientScript::POS_END);
		$cs->registerScriptFile($assets."/commonscript".'.js',CClientScript::POS_END);
		
		
		$this->render('ELightBoxContentWidget',array(
			'classname'=>$this->classname,
			'divid'=>$this->divid,
			'width'=>$this->width,
			'content'=>$this->content,
			'mapdivid'=>$this->mapdivid,
			'address'=>$this->address,
			'label'=> $this->label,
			'linklabel'=>$this->linklabel,
			
		));
	}
}
?>
