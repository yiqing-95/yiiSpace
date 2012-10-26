<?php

class DefaultController extends BaseUserController
{
	
	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
        /*
		$dataProvider=new CActiveDataProvider('User', array(
			'criteria'=>array(
		        'condition'=>'status>'.User::STATUS_BANNED,
		    ),
			'pagination'=>array(
				'pageSize'=>Yii::app()->controller->module->user_page_size,
			),
		));*/
        $model=new User('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['User']))
            $model->attributes=$_GET['User'];

        $dataProvider = $model->search();
        $dataProvider->getCriteria()->addCondition('status>'.User::STATUS_BANNED);
        $this->render('/user/index',array(
            'dataProvider'=>$dataProvider,
            'model'=>$model,
        ));
	}

}