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

    /**
     * @return string the URL that shows the detail of the photo
     * ------------------------------------------------------------
     * 注意controller的createUrl 会考虑当前所处的module的 而直接用
     * app的该方法 不考虑moduleId 所以如果进行了URL规则设置 要小心这个
     * 东东！
     * ------------------------------------------------------------
     */
    public function getUrl()
    {
        return Yii::app()->createUrl('photo/view', array(
            'id'=>$this->id,
            'aid'=>$this->album_id,
            'u'=>$this->uid,
        ));
    }
}