<?php

Yii::import('news.models._base.BaseNewsPost');

class NewsPost extends BaseNewsPost
{
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
}