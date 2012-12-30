<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-12-20
 * Time: 下午3:01
 * To change this template use File | Settings | File Templates.
 */
class AbstractObjectPrivacyStrategy implements IObjectPrivacyStrategy
{
    /**
     * @var string
    public $actionName = 'view';
     */

    /**
     * @var int
     */
    protected $objectOwner;

    /**
     * @var int
     */
    protected $visitor;

    public function __construct(ObjectPrivacyContext $privacyContext )
    {
        $this->objectOwner = $privacyContext->getObjectOwner();
        $this->visitor = $privacyContext->getViewer();
    }

    /**
     * @return bool
     */
    public function isAllowed()
    {
        return true;
    }
}
