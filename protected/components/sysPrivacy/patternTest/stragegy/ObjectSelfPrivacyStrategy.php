<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-12-20
 * Time: 下午3:42
 * To change this template use File | Settings | File Templates.
 */
class ObjectSelfPrivacyStrategy extends AbstractObjectPrivacyStrategy
{


    /**
     * @return bool
     */
    public function isAllowed()
    {
        return $this->objectOwner == $this->visitor;
    }
}
