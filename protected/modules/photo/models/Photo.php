<?php

Yii::import('photo.models._base.BasePhoto');

class Photo extends BasePhoto
{
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

    protected function beforeSave(){
        if($this->getIsNewRecord()){
            if(empty($this->hash)){
                $this->hash = md5(microtime());
            }
        }
        return parent::beforeSave();
    }

    public function getThumbUrl(){
     return   Ys::thumbUrl($this->path,90,90);
    }

    public function getViewUrl(){
        return bu($this->path);
    }
}