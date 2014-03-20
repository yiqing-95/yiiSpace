<?php

Yii::import('msg.models._base.BaseMsgInbox');

class MsgInbox extends BaseMsgInbox
{
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}


    public function relations() {
        return array(
            'msg'=>array(self::BELONGS_TO,'Msg','msg_id')
        );
    }

}