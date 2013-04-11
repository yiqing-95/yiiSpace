<?php

Yii::import('application.models._base.BaseComment');

class Comment extends BaseComment
{
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
}