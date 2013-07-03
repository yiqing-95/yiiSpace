<?php

Yii::import('photo.models._base.BasePhotoAlbum');

class PhotoAlbum extends BasePhotoAlbum
{
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

    public function getAlbumCoverUrl(){
        if(empty($this->cover_uri)){
            return PhotoHelper::getDefaultAlbumCoverUrl();
        }else{
            return PhotoHelper::getDefaultAlbumCoverUrl();
        }
    }

    /**
     * @return string the URL that shows the detail of the photo
     */
    public function getUrl()
    {
        return Yii::app()->createUrl('album/view', array(
            'id'=>$this->id,
            'u'=>$this->uid,
        ));
    }

    /**
     * @static
     * @param int $uid
     * @return array
     */
    public static function getUserAlbum($uid=0){
        $criteria = new CDbCriteria();
        $criteria->order = 'id DESC';
        return PhotoAlbum::model()->findAllByAttributes(array(
            'uid'=>$uid
        ),$criteria);
    }

    /**
     * @static
     * @param string $action
     * @return array|bool
     */
    public static function privacyGroup($action='view')
    {
        $allGroups = self::availablePrivacyGroups();
        return isset($allGroups[$action]) ? $allGroups[$action] : false;
    }

    /**
     * @static
     * @return array
     */
    private static function availablePrivacyGroups()
    {
        return array(
            'view'=>array(
                1 => 'public',
                2 => 'friend',
                3 => 'self',
            ),
        );
    }
}