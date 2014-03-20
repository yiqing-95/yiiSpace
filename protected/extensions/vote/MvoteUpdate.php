<?php

class MvoteUpdate extends CAction{

	public function run()
	{
		$pid = Yii::app()->request->getParam('pid');
		$create = Yii::app()->request->getParam('c');	
		$cookie = Yii::app()->request->getCookies();
		if(isset($cookie["user_vote_{$pid}"]->value) && 0 < $cookie["user_vote_{$pid}"]->value)Yii::app()->end(CJSON::encode(array('code'=>'-1')));	
		if($create == '0'){
			$data = Yii::app()->db->createCommand("insert into `cstar_rating` set `number`= '1',post_id = :pid")->bindValue(':pid',$pid)->execute();
		}else{
			$data = Yii::app()->db->createCommand("update `cstar_rating` set `number`= `number`+1 where post_id = :pid")->bindValue(':pid',$pid)->execute();
		}
		$time = time();
		$cookie = new CHttpCookie("user_vote_{$pid}",'user_vote');
		$cookie->expire = $time+86400;
		$cookie->value = (strtotime(date("Y-m-d 23:59:59")));
		$cookie->httpOnly = true;
		Yii::app()->request->cookies['user_vote']=$cookie;
		Yii::app()->end(CJSON::encode(array('code'=>'1')));
	}

}