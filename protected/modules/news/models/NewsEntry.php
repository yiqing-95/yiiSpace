<?php

Yii::import('news.models._base.BaseNewsEntry');

class NewsEntry extends BaseNewsEntry
{
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

    public function relations(){
        return array(
            'post'=>array(self::HAS_ONE,'NewsPost','nid'),
        );
    }

    public function beforeSave()
    {
        if (parent::beforeSave())
        {
            $now =  time(); //new CDbExpression('NOW()');
            $userId = Yii::app()->user->id;

            if ($this->getIsNewRecord())
            {
                // We are creating a new record.
                if ($this->hasAttribute('create_time'))
                    $this->create_time = $now;

                if ($this->hasAttribute('creator') && $userId !== null)
                    $this->creator = $userId;
            }
            else
            {
                // We are updating an existing record.
                if ($this->hasAttribute('update_time'))
                    $this->update_time = $now;

                if ($this->hasAttribute('modifierId') && $userId !== null)
                    $this->modifierId = $userId;
            }

            return true;
        }
        else
            return false;
    }


    protected function beforeDelete(){
        $continueDelete = parent::beforeDelete();
        if($continueDelete &&  NewsPost::model()->findByPk($this->id)->delete()){
            return true;
        }else{
            return false;
        }

    }
}