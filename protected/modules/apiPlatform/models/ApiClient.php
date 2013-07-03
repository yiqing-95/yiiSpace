<?php

Yii::import('apiPlatform.models._base.BaseApiClient');

class ApiClient extends BaseApiClient
{
    /**
     * 未审核
     */
    const STATUS_UNCHECKED = 0 ;
    /**
     * 审核通过
     */
    const STATUS_CHECKED = 1 ;

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

    /**
     * @var array
     */
    protected $oldAttributes = array();

    protected function afterFind(){
        parent::afterFind();
        /**
         * 先保存起来 如果修改了 那么这里保存的是原始值
         */
        $this->oldAttributes = $this->getAttributes();
    }

    protected function beforeSave(){
        if (parent::beforeSave()) {
            if (($this->getIsNewRecord()
                && $this->hasAttribute('create_time'))
                && (is_null($this->create_time)
                    || $this->create_time == 0)
            ) {
                $this->create_time = time();
            }
            if ($this->hasAttribute('update_time')
                && (is_null($this->update_time) || $this->update_time == 0)
            ) {
                $this->modifier_id = Yii::app()->user->getId();
                $this->update_time = time();
            }
            return true;
        } else {
            return false;
        }
    }
}