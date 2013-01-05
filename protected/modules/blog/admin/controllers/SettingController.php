<?php

class SettingController extends Controller
{
    /**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('update'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
    
	public function actionUpdate()
	{
		$model = new SettingForm;
		foreach ($this->settings as $key => $value) {
			$model->$key = $value;
		}
		
		
		if(isset($_POST['SettingForm']))
		{
			$model->attributes=$_POST['SettingForm'];
			if($model->validate())
			{
				$option_value = json_encode($model->attributes);
				$option_name = 'settings';
				//TODO 未完成
				//$this->refresh();
			}
		}
		
		$this->render('update',array(
			'model'=>$model,
		));
	}

}