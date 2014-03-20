<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-4-5
 * Time: 下午12:32
 *------------------------------------------------------------
 *                 _            _
 *                (_)          (_)
 *        _   __  __   .--. _  __   _ .--.   .--./)
 *       [ \ [  ][  |/ /'`\' ][  | [ `.-. | / /'`\;
 *        \ '/ /  | || \__/ |  | |  | | | | \ \._//
 *      [\_:  /  [___]\__.; | [___][___||__].',__`
 *       \__.'            |__]             ( ( __))
 *
 *--------------------------------------------------------------
 * To change this template use File | Settings | File Templates.
 * -------------------------------------------------------------------
 * 用来发布管理 公共资源：css,iamges,js  归组管理
 *  site(js,iamges,css);  user(js,iamges,css)
 * ------------------------------------------------------------------
 * 默认使用同index.php 在统一目录下的public文件夹作为根目录
 * 如果你设置了$publicDirAsAssetsRoot = false ；为假的话那么将使用
 * 本类同目录下同名文件夹作为asset跟来发布到前端的assets目录下
 * -------------------------------------------------------------------
 * 有些事在构造方法中做 有些事在 公共方法中做？
 * 公共方法中做 一般是惰性资源
 * 比如 本实例是个预加载组件 construct方法执行时机早些 此时某些依赖的
 * 资源可能还未准备好（假设 别名设定吧 那么你就不能用某些自定义别名[系统别名除外]）
 * 但它推迟到实例公共方法中确实可行的！ 参考index.php 理解设计依据吧
 * -------------------------------------------------------------------
 */
class PublicAssets extends CApplicationComponent
{
    /**
     * @var bool 如果theme允许时 那么基路径url
     * 会以当前theme baseUrl为准
     */
    public $enableTheme = true ;

    /**
     * @var CClientScript
     */
    protected $cs;

    /**
     * @var CAssetManager
     */
    protected $am;

    public function init()
    {
        parent::init();

        $this->cs = Yii::app()->getClientScript();
        $this->am = Yii::app()->getAssetManager();

    }

    /**
     *
     */
    const DEFAULT_BASEPATH = 'PublicAssets';
    /**
     * @var string
     */
    protected $_baseUrl ;

    /**
     * @var string
     */
    protected $_basePath ;

    /**
     * @var bool
     * 用最外面的public目录作为assets的跟目录
     * 不然会依本类所在目录下的同名文件夹作为根目录的
     * /PublicAssets 对于这种情行 所有该文件夹下的资源将被
     * 用assetManager发布到assets目录去！
     */
    public $publicDirAsAssetsRoot = true;

    /**
     * @return string the root directory storing the public asset files. Defaults to 'PublicAssets'.
     */
    public function getBasePath()
    {
        if ($this->_basePath === null) {
            if($this->publicDirAsAssetsRoot == true){
                $request = Yii::app()->getRequest();
                $this->setBasePath(dirname($request->getScriptFile()).DIRECTORY_SEPARATOR.'public');
            }else{
                $this->setBasePath(dirname(__FILE__) . DIRECTORY_SEPARATOR . self::DEFAULT_BASEPATH);
            }

        }

        return $this->_basePath;
    }

    /**
     * @param $value
     * @throws CException
     */
    public function setBasePath($value)
    {
        if (($basePath = realpath($value)) !== false && is_dir($basePath) && is_writable($basePath))
            $this->_basePath = $basePath;
        else
            throw new CException(Yii::t('yii', 'PublicAssets.basePath "{path}" is invalid. Please make sure the directory exists and is writable by the Web server process.',
                array('{path}' => $value)));
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        if ($this->_baseUrl === null) {
            if($this->publicDirAsAssetsRoot == true){
               if($this->enableTheme && (Yii::app()->theme !==null)){
                    $this->setBaseUrl(Yii::app()->theme->baseUrl.'/public');
               }else{
                   $request = Yii::app()->getRequest();
                   $this->setBaseUrl($request->getBaseUrl().'/public');
               }
            }else{
                $basePath = $this->getBasePath();
                $bu = $this->am->publish($basePath,true,-1,YII_DEBUG);
                $this->setBaseUrl($bu);
            }
        }
         return $this->_baseUrl;
    }

    /**
     * @param string $value
     */
    public function setBaseUrl($value = '')
    {
        $this->_baseUrl = rtrim($value, '/');
    }

   /**
    * @static
    * @return PublicAssets
    */
    public  static function instance(){
        if(!isset(Yii::app()->publicAssets)){
            Yii::app()->setComponents(array(
                'publicAssets'=>array(
                    'class'=>__CLASS__,
                ),
            ), false);
        }
        return Yii::app()->publicAssets ;
    }

    /**
     * @static
     * @param null $relativePath
     * @return string
     */
    public static function url($relativePath=null){
        $bu = self::instance()->getBaseUrl();
        if(is_null($relativePath)){
            return $bu;
        }else{
            return $bu. '/'. ltrim($relativePath,'/');
        }
    }

    /**
     * @static
     * @param string $relativeUrl 相对url
     * @param int $position 参考CClinetScript::POS_XXXX
     * @return mixed
     * @throws CException
     */
    static  public function registerScriptFile($relativeUrl='',$position=CClientScript::POS_HEAD)
    {
        $cs = Yii::app()->getClientScript();
        if(is_string($relativeUrl)){
           $relativeUrl = explode(',',$relativeUrl);
        }
        if(is_array($relativeUrl)){
            foreach($relativeUrl as $relUrl){
                $url = self::url($relUrl);
                $cs->registerScriptFile($url,$position);
            }
        }else{
            throw new  CException('param 1 must be an string or array ,but you give ' . var_export($$relativeUrl,true));
        }
    }

    /**
     * @static
     * @param string $relativeUrl
     * @throws CException
     * 区别于register的是 直接输出<script></script>标记
     */
    static  public function scriptFile($relativeUrl)
    {
        if(is_string($relativeUrl)){
            $relativeUrl = explode(',',$relativeUrl);
        }
        if(is_array($relativeUrl)){
            foreach($relativeUrl as $relUrl){
                $url = self::url($relUrl);
              echo  CHtml::scriptFile($url);
            }
        }else{
            throw new  CException('param 1 must be an string or array ,but you give ' . var_export($$relativeUrl,true));
        }
    }

    /**
     * @static
     * @param $relativeUrl
     * @throws CException
     */
    static public function cssFile($relativeUrl){
        if(is_string($relativeUrl)){
            $relativeUrl = explode(',',$relativeUrl);
        }
        if(is_array($relativeUrl)){
            foreach($relativeUrl as $relUrl){
                $url = self::url($relUrl);
                echo  CHtml::cssFile($url);
            }
        }else{
            throw new  CException('param 1 must be an string or array ,but you give ' . var_export($$relativeUrl,true));
        }
    }
   /**
    * @param $relativeUrl 相对url 相对于public目录
    * @param string $media
    * @throws CException
    */
    static  public function registerCssFile($relativeUrl,$media='')
    {
        $cs = Yii::app()->getClientScript();
        if(is_string($relativeUrl)){
            $relativeUrl = explode(',',$relativeUrl);
        }
        if(is_array($relativeUrl)){
            foreach($relativeUrl as $relUrl){
                $url = self::url($relUrl);
               $cs->registerCssFile($url,$media);
            }
        }else{
            throw new  CException('param 1 must be an string or array ,but you give ' . var_export($$relativeUrl,true));
        }
    }
}
