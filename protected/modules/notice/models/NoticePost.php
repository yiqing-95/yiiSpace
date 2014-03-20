<?php

Yii::import('notice.models._base.BaseNoticePost');

class NoticePost extends BaseNoticePost
{
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
}