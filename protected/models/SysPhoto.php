<?php

Yii::import('application.models._base.BaseSysPhoto');

class SysPhoto extends BaseSysPhoto
{
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

    public function relations(){
        return array(
            // 属于相册对象
            'albumObject'=>array(self::BELONGS_TO,'SysAlbumObject',array('id'=>'id_object')),
        );
    }
}