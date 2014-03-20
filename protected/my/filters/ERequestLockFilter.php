<?php

/**
 * TODO : to be continue .............
 *
 * Created by PhpStorm.
 * User: yiqing
 * Date: 14-3-16
 * Time: 上午8:56
 */
class ERequestLockFilter extends CFilter
{

    /**
     * whether or not this is the second time that you do the lock checking !
     * such as :  you call a controller in another controller but both config the requestLockFilter!
     * this flag is able to prevent you from entering the same request checking scope again .
     *
     * TODO  if you want config a different lock-time for some controller this var can be an array
     *        checked[$lockKey] = true ;
     *
     * @var bool
     */
    public static $checked = false ;
    /**
     * the seconds request locked .
     *
     * @var int
     */
    public $lockTime = 7;

    /**
     *
     * @var string
     */
    public $lockKey = 'request';

    /**
     * @var string the method which should be locked some times. This should be either 'POST', 'GET' ,'PUT'...... .
     * 'ANY'  means you want perform a locking check for all  request !
     * Defaults to 'POST'.
     */
    public $method = 'POST';

    /**
     * the underline lock storage locker which should implements the interface : IRequestLocker
     *
     * @var array
     */
    public $locker = array(
        'class' => 'ESessionRequestLocker',

    );

    /**
     * @var IRequestLocker
     */
    protected $_lockerObj;

    /**
     * lazy init the lockerObj with the locker config array
     *
     * @return \IRequestLocker
     */
    public function getLockerObj()
    {
        $this->_lockerObj = Yii::createComponent($this->locker);
        return $this->_lockerObj;
    }

    /**
     * Performs the pre-action filtering.
     * @param CFilterChain $filterChain
     * @throws CHttpException
     * @internal param \the $CFilterChain filter chain that the filter is on.
     * @return boolean whether the filtering process should continue and the action
     * should be executed.
     */
    protected function preFilter($filterChain)
    {
        // check if it is the second entering !
        if(self::$checked == true){
            return true ;
        }

        switch (strtoupper($this->method)) {
            // ....?
            case 'ANY':
                $method = '';
                break;
            case 'GET':
                $method = 'isGet';
                break;

            case 'DELETE':
                $method = 'isDelete';
                break;
            case 'PUT':
                $method = 'isPut';
                break;
            case 'AJAX':
                $method = 'isAjax';
                break;
            case 'FLASH':
                $method = 'isFlash';
                break;
            case 'PATCH':
                $method = 'isPatch';
                break;

            /**
             * ........
             */
            default:
                $method = 'isPost';
        }

        // ANY will always perform the lock checking
        if (empty($method)) {
            $this->doLockCheck();
        } // determine if the request is GET ?
        elseif ($method == 'isGet') {
            // TODO ... please check  if this implement is ok ?
            $request = Yii::app()->request;
            if (
                !$request->getIsDeleteRequest()
                || !$request->getIsPatchRequest()
                || !$request->getIsPutRequest()
                || !$request->getIsPostRequest()
                // || !$request->getIsFlashRequest()
            ) {
                $this->doLockCheck();
            }
        } else {
            $method = 'get' . ucfirst($method) . 'Request';
            if (Yii::app()->request->{$method}()) {
                $this->doLockCheck();
            }
        }
        return true;
    }

    /**
     * @throws CHttpException
     */
    protected function doLockCheck()
    {
        $this->_lockerObj = $this->getLockerObj();

        $lockKey = $this->lockKey ;

        if ($this->_lockerObj->isLocked($lockKey)) {
            // TODO here you can leave some callback mechanism for using a custom handler .
            throw new CHttpException(400, self::t('the request come too fast ,please be patient \n try it latter !'));
        } else {
            $this->_lockerObj->lock($lockKey, $this->lockTime);
        }
        //  set the done flag
        //  or may be this :  self::$checked[$lockKey] = true ;
        self::$checked = true ;
    }

    public static function  t($message, $params = array(), $language = null)
    {
        $messageSourceApplicationComponentId = __CLASS__;
        if (!Yii::app()->hasComponent($messageSourceApplicationComponentId)) {
            Yii::app()->setComponent($messageSourceApplicationComponentId,
                array(
                    'class' => 'CPhpMessageSource',
                    //  'language' => 'en_us',
                    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'messages',
                )
            );
        }
        $category = 'requestLock';

        return Yii::t($category, $message, $params, $messageSourceApplicationComponentId, $language);

        /** @var  CMessageSource $messageSource */
        /*
        $messageSource = Yii::app()->getComponent($messageSourceApplicationComponentId);
        return $messageSource->translate($category,$message,$language);
        */

    }
}

/**
 * Interface IRequestLocker
 */
interface IRequestLocker
{
    /**
     * @param string $key
     * @param int $lifeTime
     * @return bool is the lock successfully added to the key ?
     */
    public function lock($key = '', $lifeTime = 10);

    /**
     * @param string $key
     * @return bool check if the key has been locked ?
     */
    public function isLocked($key = '');

    /**
     * unlock the specified key
     *
     * @param string $key
     * @return void|bool
     */
    public function unlock($key = '');
}

class ESessionRequestLocker implements IRequestLocker
{

    const LOCK_KEY_PREFIX = '__REQ_LOCK_';

    /**
     * @param string $key
     * @param int $lifeTime
     * @return bool is the lock successfully added to the key ?
     */
    public function lock($key = '', $lifeTime = 10)
    {
        $lockKey = self::LOCK_KEY_PREFIX . $key;

        if (isset($_SESSION[$lockKey]) && intval($_SESSION[$lockKey]) > time()) {
            return false;
        } else {
            $lifeTime = $lifeTime ? $lifeTime : 10;
            $_SESSION[$lockKey] = time() + intval($lifeTime);
            return true;
        }
    }

    /**
     * @param string $key
     * @return bool check if the key has been locked ?
     */
    public function isLocked($key = '')
    {
        $lockKey = self::LOCK_KEY_PREFIX . $key;

        return isset($_SESSION[$lockKey]) && intval($_SESSION[$lockKey]) > time();
    }

    /**
     * unlock the specified key
     *
     * @param string $key
     * @return void|bool
     */
    public function unlock($key = '')
    {
        $lockKey = self::LOCK_KEY_PREFIX . $key;

        unset($_SESSION[$lockKey]);
    }
}