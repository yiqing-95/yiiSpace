<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-8-10
 * Time: 下午12:45
 * To change this template use File | Settings | File Templates.
 */
class YsActiveRecord extends CActiveRecord
{
    public function behaviors()
    {
        return parent::behaviors() + array(
            'ysArBehavior' => array(
                'class' => 'my.behaviors.YsActiveRecordBehavior',
            ),
        );
    }

    protected function beforeSave()
    {
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
                $this->update_time = time();
            }
            return true;
        } else {
            return false;
        }
    }


}
