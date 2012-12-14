<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-12-14
 * Time: 下午4:40
 * To change this template use File | Settings | File Templates.
 */
class ThumbVotingWidget extends CWidget
{


    /**
     * @var string the tag name for the view container. Defaults to 'div'.
     */
    public $tagName = 'div';
    /**
     * @var array the HTML options for the view container tag.
     */
    public $htmlOptions = array();


    /**
     * @var string available mode : updown2 |updown1| plus
     */
    public $mode = 'updown2';

    //.............................................
    /**
     * @var int
     */
    public $upValue = 0;
    /**
     * @var int
     */
    public $downValue = 0;

    /**
     * @var CActiveRecord
     */
    public $model;

    /**
     * @var string
     */
    public $objectName = __CLASS__;

    /**
     * @var int
     */
    public $objectId = 1;
    //.............................................

    /**
     * @var string
     */
    public $actionUrl = '/voting/default/vote';
    //-----------------------------------

    /**
     * @var string
     */
    public $baseUrl;

    /**
     * @var bool
     */
    public $debug = YII_DEBUG;

    /**
     * @var \CClientScript
     */
    protected $cs;

    /**
     * @var array|string
     * -------------------------
     * the options will be passed to the underlying plugin
     *   eg:  js:{key:val,k2:v2...}
     *   array('key'=>$val,'k'=>v2);
     * -------------------------
     */
    public $options = array();

    /**
     * @var string
     */
    public $selector;

    /**
     * @return ThumbVotingWidget
     */
    public function publishAssets()
    {
        if (empty($this->baseUrl)) {
            $assetsPath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets';
            if ($this->debug == true) {
                $this->baseUrl = Yii::app()->assetManager->publish($assetsPath, false, -1, true);
            } else {
                $this->baseUrl = Yii::app()->assetManager->publish($assetsPath);
            }
        }
        return $this;
    }


    /**
     *
     */
    public function init()
    {
        if(!empty($this->model)){
            $this->objectName = get_class($this->model);
            $this->objectId = $this->model->getPrimaryKey();
        }

        if(empty($this->objectName) ||($this->objectId === null)){
            throw new CException("this widget need to adhere to a model or pass the ObjectClass and ObjectId for using !");
        }

        parent::init();
        $this->cs = Yii::app()->getClientScript();
        // publish assets and register css/js files
        $this->publishAssets();

       // $this->registerScriptFile('voting.js');
        $this->registerScriptFile('voting_yiiSpace.js');

        $this->registerCssFile('voting.css');
        if(!in_array($this->mode,array('updown2','updown1','plus')) ){
            $this->mode = 'updown2';
        }
        if(isset($this->htmlOptions['class'])){
            $this->htmlOptions['class'] .= " vot_{$this->mode} ys-Voting" ;
        }else{
            $this->htmlOptions['class'] = "vot_{$this->mode} ys-Voting" ;
        }
        // 把数据提前 埋在html结构中 原来准备保存到options 里面但考虑到翻页问题 怕丢失
        $this->htmlOptions['object_name'] = $this->objectName;
        $this->htmlOptions['object_id'] = $this->objectId;


        $this->htmlOptions['id'] = 'vt_'.$this->getId();


        if(!is_string($this->actionUrl)){
            throw new CException(" actionUrl must be a string , but now you give a ".gettype($this->actionUrl));
        }
        $defaultOptions = array('url'=>Yii::app()->createUrl($this->actionUrl));
        //> encode it for initializing the current jquery  plugin
        // $options = empty($this->options) ? '' : CJavaScript::encode($this->options);
        $options =  CJavaScript::encode(CMap::mergeArray($defaultOptions,$this->options));

        $jsCode = '';

        //>  the js code for setup
        $jsCode .= <<<SETUP
        jQuery('#vt_{$this->getId()}').ysVoting({$options});
SETUP;


        //> register jsCode
        $this->cs->registerScript(__CLASS__ . '#' . $this->getId(), $jsCode, CClientScript::POS_READY);
    }

    /**
     *
     */
    public function run()
    {

        echo CHtml::openTag($this->tagName, $this->htmlOptions) . "\n";
        $methodName = 'mode'.$this->mode;
        echo $this->{$methodName}();
        echo CHtml::closeTag($this->tagName);
    }

    protected function modeUpDown1(){
        $upImgUrl = $this->baseUrl.'/votup.png';
        $downImgUrl =  $this->baseUrl.'/votdown.png';

        $votingValue = $this->upValue - abs($this->downValue);

        $content =<<<EOD
       <h4 class="voting-value">{$votingValue}</h4>
       <span>
        <img class="thumbUp" title="Vote Up" alt="Vote Up" src="{$upImgUrl}">
        <img class="thumbDown" title="Vote Down" alt="Vote Down" src="{$downImgUrl}">
        </span>
        <div id="nupdown">
        <b id="nvup" class="up-value">{$this->upValue}</b> &nbsp; &nbsp;
        <b id="nvdown" class="down-value">{$this->downValue}</b>
        </div>
EOD;
        return $content;
    }

    protected function modeUpDown2(){
        $upImgUrl = $this->baseUrl.'/votup.png';
        $downImgUrl =  $this->baseUrl.'/votdown.png';

        $totalVotes = $this->upValue+ abs($this->downValue);
        $votingValue = $this->upValue - abs($this->downValue);
        $content =<<<EOD
        <div id="nvotes">
        Votes: <b class="total-votes">{$totalVotes}</b>
        </div>
        <h4 class="voting-value">{$votingValue}</h4>
        <span>
        <img class="thumbUp" title="Vote Up" alt="Vote Up" src="{$upImgUrl}">
        <img class="thumbDown" title="Vote Down" alt="Vote Down" src="{$downImgUrl}">
        </span>
EOD;
        return $content;
    }

    protected function modePlus(){
        $imgUrl = $this->baseUrl.'/votplus.gif';
        $votingValue = $this->upValue ;
        $content =<<<EOD
        <h4 class="voting-value">{$votingValue}</h4>
        <span>
        <img class="plus" title="Vote" alt="1" src="{$imgUrl}">
        </span>
EOD;
        return $content;
    }


    /**
     * @param string $name
     * @param mixed $value
     * @return mixed|void
     */
    public function __set($name, $value)
    {
        try {
            //shouldn't swallow the parent ' __set operation
            parent::__set($name, $value);
        } catch (Exception $e) {
            $this->options[$name] = $value;
        }
    }

    /**
     * @param $fileName
     * @param int $position
     * @return ThumbVotingWidget
     * @throws InvalidArgumentException
     */
    protected function registerScriptFile($fileName, $position = CClientScript::POS_END)
    {
        if (is_string($fileName)) {
            $jsFiles = explode(',', $fileName);
        } elseif (is_array($fileName)) {
            $jsFiles = $fileName;
        } else {
            throw new InvalidArgumentException('you must give a string or array as first argument , but now you give' . var_export($fileName, true));
        }
        foreach ($jsFiles as $jsFile) {
            $jsFile = trim($jsFile);
            $this->cs->registerScriptFile($this->baseUrl . '/' . ltrim($jsFile, '/'), $position);
        }
        return $this;
    }

    /**
     * @param $fileName
     * @return ThumbVotingWidget
     * @throws InvalidArgumentException
     */
    protected function registerCssFile($fileName)
    {
        $cssFiles = func_get_args();
        foreach ($cssFiles as $cssFile) {
            if (is_string($cssFile)) {
                $cssFiles2 = explode(',', $cssFile);
            } elseif (is_array($cssFile)) {
                $cssFiles2 = $cssFile;
            } else {
                throw new InvalidArgumentException('you must give a string or array as first argument , but now you give' . var_export($cssFiles, true));
            }
            foreach ($cssFiles2 as $css) {
                $this->cs->registerCssFile($this->baseUrl . '/' . ltrim($css, '/'));
            }
        }
        // $this->cs->registerCssFile($this->assetsUrl . '/vendors/' .$fileName);
        return $this;
    }

    /**
     * @param array $cssSettings
     * @param bool $withCurlyBrace
     * @return string
     */
    public function genCssFromArray($cssSettings = array(), $withCurlyBrace = true)
    {
        $cssCodes = '';
        foreach ($cssSettings as $k => $v) {
            $cssCodes .= "{$k}:{$v}; \n";
        }
        if ($withCurlyBrace === true) {
            $cssCodes = '{' . "\n" . $cssCodes . '}';
        }
        return $cssCodes;
    }

    /**
     * @param string $cssString
     * @return array
     */
    public function getArrayFromCssString($cssString = '')
    {
        $rtn = array();
        //remove  {   and  }  if exists
        $cssString = rtrim(trim($cssString), '}');
        $cssString = ltrim($cssString, '{');
        //remove  all comments and space
        $text = preg_replace('!/\*.*?\*/!s', '', $cssString);
        $text = preg_replace('/\n\s*\n/', "", $text);
        // pairs handle
        $pairs = explode(';', $text);
        foreach ($pairs as $pair) {
            $colonPos = strpos($pair, ':');
            if (($k = trim(substr($pair, 0, $colonPos))) !== '') {
                $rtn[$k] = substr($pair, $colonPos + 1);
            }
        }
        return $rtn;
    }

    /**
     * @static
     * @param bool $hashByName
     * @return string
     * return this widget assetsUrl
     */
    public static function getAssetsUrl($hashByName = false)
    {
        // return CHtml::asset(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets', $hashByName);
        return Yii::app()->getAssetManager()->publish(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets', $hashByName, -1, YII_DEBUG);
    }
}