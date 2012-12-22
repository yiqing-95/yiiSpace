<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-12-20
 * Time: 下午2:58
 * To change this template use File | Settings | File Templates.
 */
class ObjectPublicPrivacyStrategy extends AbstractObjectPrivacyStrategy
{

    /**
     * @return bool
     */
    public function isAllowed()
    {
        return true;
    }
}
