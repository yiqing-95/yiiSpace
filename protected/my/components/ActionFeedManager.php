<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-8-25
 * Time: 下午3:35
 * To change this template use File | Settings | File Templates.
 * -------------------------------------------------------------------------
 * maybe have another  actionType : controller/action
 * you can add another two method to support that just like (registerFeedHandler,getActionFeedHandler)
 * -------------------------------------------------------------------------
 */
class ActionFeedManager
{

    /**
     *
     */
    const ACTION_TYPE_AR_INSERT = 1;
    /**
     *
     */
    const ACTION_TYPE_AR_UPDATE = 2;

    /**
     * @var array
     * request scope cache for the actionHandler
     */
    private static $_handler = array(); // class objectType => $handler


    /**
     * @param string $objectType
     * @param string $actionFeedHandlerImpl
     */
    static public function registerFeedHandler($objectType = '', $actionFeedHandlerImpl = '')
    {
        AppComponent::settings()->set('action_feed', $objectType, $actionFeedHandlerImpl);
    }


    /**
     * @static
     * @param string $objectType
     */
    static public function unRegisterFeedHandler($objectType = '')
    {
        // AppComponent::settings()->delete('action_feed',$objectType);
        AppComponent::settings()->set('action_feed', $objectType, '');
    }

    /**
     * @param string $objectType
     * @return IActionFeedHandler|mixed
     */
    static public function getActionFeedHandler($objectType)
    {

        if (isset(self::$_handler[$objectType])) {
            return self::$_handler[$objectType];
        } else {
            $actionFeedClass = AppComponent::settings()->get('action_feed', $objectType, false);
            if (!empty($actionFeedClass)) {
                $feedHandler = Yii::createComponent(array('class' => $actionFeedClass));
            } else {
                $feedHandler = null;
            }
            self::$_handler[$objectType] = $feedHandler;
            return $feedHandler;
        }
    }


}


