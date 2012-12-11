<?php

Yii::import('application.models._base.BaseSysHook');

class SysHook extends BaseSysHook
{
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
}