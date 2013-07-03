<?php

class TestController extends Controller
{
    /*
    * 
    */
	public function actionApi()
	{
        $rs = array(
            '1'=>'你好',
            '2'=>'测试api',
            '3'=>'api',
        );
        ApiModule::d($rs);
	}
}
