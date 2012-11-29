<?php

class InstallController extends BasePhotoController
{
	public function actionIndex()
	{
		Yii::import('photo.install.PhotoInstaller');
        $installer = new PhotoInstaller();
        $installer->uninstall() ;
        // 先清理干净再安装！
        $installer->install();
	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}