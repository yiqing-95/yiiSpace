<?php
Yii::import('zii.widgets.CPortlet');

class UserMenu extends CPortlet
{
	public function init()
	{
		$this->title=CHtml::encode('功能');
		parent::init();
	}

	protected function renderContent()
	{
		$this->render('userMenu');
	}
}