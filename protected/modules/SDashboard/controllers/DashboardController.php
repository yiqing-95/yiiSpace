<?php

class DashboardController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/main';

	/**
	 * @return array action filters
	 */
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

			array('allow', // allow authenticated users to access all actions
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function allowedActions(){
		return 'index,create,update,updatePortlets,active,deactivateportlet,demoAjax,modify,viewwidget';
	}

	/** demo actions **/

	/** A demo to render stuff in a portlet with ajaxrequest**/
	public function actiondemoAjax(){

		echo "this is the demo ajax request";
	}

	public function actionviewWidget(){
		$this->render('viewwidget');

	}

	/*needed actions */
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		if(isset($_POST['Dashboard']))
		{
			if($_POST['Dashboard']['newrecord'] == 'create'){
				$model=new Dashboard;
			}else{
				$model = $this->loadModel($_POST['Dashboard']['newrecord']);
			}
			$model->attributes=$_POST['Dashboard'];
			
				if(isset($_POST['Dashboard']['default'])){
					$model->user_id = 0;	
				}else{
					$model->user_id = Yii::app()->user->id;
				}	
				
				if($model->save()){
					echo CJSON::encode(array('status'=>'success'));
				}else{
				 echo CJSON::encode(array('status'=>'error'));
				}
		}

	}
	public function actionUpdate(){
			$model = $this->loadModel($_GET['id']);
			echo $this->renderPartial('sdashboard.views.dashboard._form',array('model'=>$model),true);
		
	}



	public function actionIndex($mode="index"){ //optionally $mode =  "modify" 

		$model = new Dashboard;
		$dataProvider = Dashboard::findPortlets(Yii::app()->user->id); //findPortlets($userid);
		

		if(Yii::app()->request->isAjaxRequest){
			$content = 	$this->renderPartial($mode,array(
				'model'=>$model,
				'dataProvider'=>$dataProvider,
				),true);
			echo $content;
		}else{
			$this->render($mode,array(
				'model'=>$model,
				'dataProvider'=>$dataProvider,
				));
			}
		
	}

	public function actionupdatePortlets(){
		if ( Yii::app()->request->isAjaxRequest )
			{	
					$model = $this->loadModel($_GET['id']);
					$model->position = $_GET['pos'];
					$model->save(false);
			}
		echo CJSON::encode(array(
					'status'=>'success',
					'message'=>'Updated the portlets positions',
		));
	}
	public function actionupdatePortletsSize(){
		if ( Yii::app()->request->isAjaxRequest )
			{	
					$model = $this->loadModel($_GET['id']);
					$model->size = round($_GET['width'],0) . "," . round($_GET['height'],0);
					$model->save(false);
			}
		echo CJSON::encode(array(
					'status'=>'success',
					'message'=>'Updated the portlets size',
		));
	}
		

	public function actiondeactivateportlet(){
		if ( Yii::app()->request->isAjaxRequest ){
					$model = $this->loadModel($_POST['id']);
					$model->active = 0;
					if($model->save(false)){	
						$status = 'success';
					}else{
						$status = 'error';
					}
					echo CJSON::encode(array('status'=>$status));
		}
	}
	public function actionactive(){
		if ( Yii::app()->request->isAjaxRequest ){

			$rows = Dashboard::model()->findAll('user_id=:user_id', array(':user_id'=>Yii::app()->user->id));

			foreach($rows as $row){
				$model = $this->loadModel($row->id);
				$model->active = 0;
				$model->save(false);
			}
			foreach($_POST as $portlet=>$active){
				$model = $this->loadModel($portlet);
				$model->active = 1;
				$model->save(false);
			}
			echo CJSON::encode(array('status'=>'success'));
		}
	}
	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete()
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			if($this->loadModel($_POST['id'])->delete()){	
				$status = 'success';
			}else{
				$status = 'error';
			}
			echo CJSON::encode(array('status'=>$status));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}


	protected function performAjaxValidation( $model )
	{
		if ( isset ( $_POST['ajax'] ) && $_POST['ajax']==='dashboard-form' )
		{
			echo CActiveForm::validate( $model );
			Yii::app()->end();
		}
	}
	

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Dashboard::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

}

