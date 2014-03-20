<?php echo "<?php\n"; ?>

class <?php echo $this->moduleClass; ?> extends CWebModule
{
	public function init()
	{
		// 这个方法在模块被创建时调用,自定义模块的代码可以放在这里

		// 导入模块级的 models模型 和 components组件
		$this->setImport(array(
			'<?php echo $this->moduleID; ?>.models.*',
			'<?php echo $this->moduleID; ?>.components.*',
		));

		//配置组件
		Yii::app()->setComponents(array(
			'clientScript'=>array(
				'class'=>'ext.dwz.DClientScript',
			),
		));
		Yii::import('ext.dwz.dwzHelper');
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// 这个方法在模块控制器的操作被执行前调用
			if ($controller->id=='default' && $action->id=='index')
				$controller->layout='dwz';
			else{
				$controller->layout=false;
				Yii::app()->clientScript->registerJQuery=false;
			}
			return true;
		}
		else
			return false;
	}
}
