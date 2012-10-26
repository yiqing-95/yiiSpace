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


}
