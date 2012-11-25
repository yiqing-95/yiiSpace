<?php

Yii::import('photo.models._base.BasePhoto');

class Photo extends BasePhoto
{
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
}