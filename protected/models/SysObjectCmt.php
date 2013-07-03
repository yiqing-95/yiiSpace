<?php

Yii::import('application.models._base.BaseSysObjectCmt');

class SysObjectCmt extends BaseSysObjectCmt
{
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

    /**
     * @return bool
     * beforeSave 方法发生在validate之后的
     */
    protected function beforeSave(){
        $defaultCommentConfig = array(
            //only registered users can post comments
            'registeredOnly' => false,
            'useCaptcha' => false,
            //allow comment tree
            'allowSubcommenting' => true,
            //display comments after moderation
            'premoderate' => false,
            //action for postig comment
            'postCommentAction' => 'comments/comment/postComment',
            //super user condition(display comment list in admin view and automoderate comments)
            'isSuperuser'=>'true',//'Yii::app()->user->checkAccess("moderate")',
            //order direction for comments
            'orderComments'=>'DESC',
        );

        if(!empty($this->extra_config)){
            $this->extra_config = CMap::mergeArray($defaultCommentConfig,$this->extra_config);
        }else{
            $this->extra_config = $defaultCommentConfig;
        }
       //  $this->extra_config = serialize($this->extra_config);
        $this->extra_config = CJSON::encode($this->extra_config);

        return parent::beforeSave();
    }

    protected function afterFind(){
      parent::afterFind();

       // $this->extra_config = unserialize($this->extra_config);
        $this->extra_config = CJSON::decode($this->extra_config);
    }
}