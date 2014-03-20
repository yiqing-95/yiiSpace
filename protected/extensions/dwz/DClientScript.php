<?php
/**
 * @author dufei22 mail:dufei22@gmail.com QQ:15756286
 * 主要用于解决在dwz界面中使用了yii自带的扩展会重复注册jquery的问题，需要在config/main.php中配置
 * 		'clientScript'=>array(
 *			'class'=>'ext.dwz.DClientScript',
 *		),
 * 并在要显示在dwz界面中的action中将regiterJQuery设为false才不会注册，一般放到控制器基类中，
 * 如果有哪个action不需要显示在dwz界面中而是在新窗口中打开则可以在这个action中再设为true。
 * 加上判断是防止哪天改了ClientScript为默认了，也不会出错
 *		if (Yii::app()->clientScript instanceof DClientScript)
 *			Yii::app()->clientScript->registerJQuery=false;
 */
class DClientScript extends CClientScript
{
	public $registerJQuery=true;
	
	public function registerCoreScript($name)
	{
		if ($this->registerJQuery===false && strtolower($name)=='jquery'){
		}else{
			parent::registerCoreScript($name);
		}
	}
}
