<?php

class DefaultController extends Controller
{
	public function actionIndex()
	{
		$this->render('index');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
	    if($error=Yii::app()->errorHandler->error)
	    {
			//$error['message'] = '你访问的页面不存在';
            $arr = array(
                'message'=>$error['message'],
                'errorCode'=>$error['errorCode'],
                'result'=>'false',
            );
            Apimodule::d($arr);
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('error', $error);
	    }
	}

}
