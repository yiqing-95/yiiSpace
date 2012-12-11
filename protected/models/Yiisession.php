<?php

Yii::import('application.models._base.BaseYiisession');

class Yiisession extends BaseYiisession
{
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
}