<?php

/**
 * TODO 允许自定义api var 就可以实现多实例了！
 *
 * 注意session续接问题： Forgery Session 使用这个扩展哦!
 *
 * modified by tony
 *
 * @see http://www.ramirezcobos.com/2010/12/26/more-than-one-instance-of-swfupload-yii-extension/
 * Class CSwfUpload
 */
class CSwfUpload extends CWidget
{

    /**
     * @return string
     */
    public static function getBaseUrl()
    {
        $assets = dirname(__FILE__) . '/swfupload';
        return $baseUrl = Yii::app()->assetManager->publish($assets);
    }

    /**
     * @var array
     */
    public $postParams = array();
    /**
     * @var array
     */
    public $config = array();
    // static counter to avoid instances
    // name conflict
    private static $_counter = 0;

    /**
     * @var string
     */
    public $apiVar = 'swfu';

    /**
     * @var string
     */
    public $sessionName;

    /**
     *
     */
    public function run()
    {
        $baseUrl = self::getBaseUrl();

        $cs = Yii::app()->getClientScript();
        // isScriptRegistered makes sure we dont register the script twice
        if (!$cs->isScriptFileRegistered(__CLASS__ . 'swfuv', CClientScript::POS_HEAD))
            $cs->registerScript(__CLASS__ . 'swfuv', "var swfuPath='" . $baseUrl . "';", CClientScript::POS_HEAD);
        if (!$cs->isScriptFileRegistered($baseUrl . '/swfupload.js', CClientScript::POS_HEAD))
            $cs->registerScriptFile($baseUrl . '/swfupload.js', CClientScript::POS_HEAD);
        if (!$cs->isScriptFileRegistered($baseUrl . '/handlers.js', CClientScript::POS_END))
            $cs->registerScriptFile($baseUrl . '/handlers.js', CClientScript::POS_END);
        if (!$cs->isCssFileRegistered($baseUrl . '/swfupload.css'))
            $cs->registerCssFile($baseUrl . '/swfupload.css');

        if (empty($this->sessionName)) {
            $this->sessionName = Yii::app()->session->sessionName;
        }
        $postParams = array($this->sessionName => session_id());


        if (isset($this->postParams)) {
            $postParams = array_merge($postParams, $this->postParams);
        }
        $config = array(
            'post_params' => $postParams,
            'flash_url' => $baseUrl . '/swfupload.swf',
            'button_image_url' =>
                $baseUrl . '/images/SmallSpyGlassWithTransperancy_17x18.png',
        );
        $config = array_merge($config, $this->config);
        $config = CJavaScript::encode($config);
        // update our static variable in order to create
        // a unique variable javascript name
        self::$_counter++;
        // we could just use the same static variable to
        // register the script name but I thought this
        // is not bad solution to show
        // PLEASE SEE THE USE OF THE STATIC VARIABLE
        // TO CREATE THE SWFUPLOAD OBJECT
        /*
        $cs->registerScript(__CLASS__.sha1(self::$_counter), "
        var swfu".self::$_counter." = new SWFUpload($config);
        ");
        */
        $cs->registerScript(__CLASS__ . sha1(self::$_counter), "
        window.{$this->apiVar};
            {$this->apiVar} = new SWFUpload($config);
        ");

    }
}