<?php

Yii::import('photo.models._base.BasePhotoAlbum');

class PhotoAlbum extends BasePhotoAlbum
{
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
}