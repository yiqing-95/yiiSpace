<?php
$alias = md5(__FILE__);
Yii::setPathOfAlias($alias,dirname(__FILE__));
Yii::import($alias.'.interfaces.IAppService');

class AppService implements IAppService
{


    /**
     * @param string $param
     * @return string
     */
    public function helloTo($param = '')
    {
       return "from ". __METHOD__ . " . helloTo ".$param;
    }
}
