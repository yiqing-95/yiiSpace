<?php
/**
 * CDwzWidget class file.
 *
 * @author dufei22 <dufei22@gmail.com>
 * @license http://www.yiiframework.com/license/
 */

/**
 * @author yiqing95 <yiqing_95@qq.com>
 */
class DwzWidget extends CWidget
{
    /**
     * @var string
     * the dwz assets url contain( dwz[the vendor dir]  and my [the customize assets]dir )
     */
    public $assetsUrl ;

    /**
     * @var string
     * the custom css and js parent folder
     */
    public $myBaseUrl = '';

    /**
     * 资源文件所在的文件夹（js、themes等文件夹所在的地方。）
     * 后面的jscriptUrl和themesUrl都是基于这个目录的，dwz.frag.xml也是在这个目录下
     * @var string
     */
    public $dwzBaseUrl;
    /**
     * @var string
     */
    public $dwzScriptUrl;
    /**
     * @var string
     */
    public $dwzThemeUrl;
    /**
     * 主题名，默认default
     * @var string
     */
    public $theme = 'default';

    /**
     * js名称，默认是dwz.min.js包含所有dwz所用的脚本，如果用数组指定则会引用所有数组指定的js
     * @var string
     */
    public $scriptFile = 'dwz.min.js';

    /**
     * 核心css文件，默认core是主题目录下的css/core.css
     * @var string
     */
    public $coreCssFile = 'core.css';
    /**
     * ie修复css文件，默认core是主题目录下的css/ieHack.css
     * @var string
     */
    public $ieHackCssFile = 'ieHack.css';
    /**
     * css名称，默认是style.css在主题目录中。如果是数组则会引用数组中的css
     * @var string
     */
    public $cssFile = 'style.css';

    /**
     * 配置DWZ的js选项，备用，此项目前没作用
     * @var array
     */
    public $options = array();

    /**
     * yii的htmlOptions选项
     * @var array
     */
    public $htmlOptions = array();
    /**
     * @var string
     */
    public $tagName = 'div';

    /**
     * @var bool
     */
    public $debug = YII_DEBUG ;

    /**
     * 初始化
     */
    public function init()
    {
        $this->resolvePackagePath();
        $this->registerCoreScripts();
        $this->registerScripts();
        parent::init();
    }

    /**
     *
     */
    public function registerScripts()
    {
        $dwzInit = <<<JS_INIT
$(function(){
				DWZ.init('{$this->dwzBaseUrl}/dwz.frag.xml', {
					callback:function(){
						initEnv();
						$('#themeList').theme({themeBase:"{$this->dwzThemeUrl}"});
					}
				});
			});
			if ($.browser.msie) {
				window.setInterval('CollectGarbage();', 10000);
			}
JS_INIT;

        Yii::app()->getClientScript()->registerScript(__CLASS__, $dwzInit);
    }

    protected function resolvePackagePath()
    {
        if ($this->assetsUrl === null) {
            $basePath = Yii::getPathOfAlias('ext.dwz.source');
            $this->assetsUrl = Yii::app()->getAssetManager()->publish($basePath, false, -1,YII_DEBUG);

            $this->dwzBaseUrl = $this->assetsUrl .'/dwz';
            $this->myBaseUrl = $this->assetsUrl .'/my';

            if ($this->dwzScriptUrl === null){
                $this->dwzScriptUrl = $this->dwzBaseUrl . '/js/src';
            }

            if ($this->dwzThemeUrl === null){
                $this->dwzThemeUrl = $this->dwzBaseUrl . '/themes';
            }

        }
    }

    /**
     *
     */
    protected function registerCoreScripts()
    {
        $cs = Yii::app()->getClientScript();

        if (is_string($this->cssFile)){
            $cs->registerCssFile($this->dwzThemeUrl . '/' . $this->theme . '/' . $this->cssFile);
        }elseif(is_array($this->cssFile)) {
            foreach ($this->cssFile as $cssFile){
                $cs->registerCssFile($this->dwzThemeUrl . '/' . $this->theme . '/' . $cssFile);
            }
        }
        $cs->registerCssFile($this->dwzThemeUrl . '/css/' . $this->coreCssFile);

        // fix some css for yii
        $cs->registerCssFile($this->myBaseUrl . '/css/myfix.css');

        // for ie browser !
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE')){
            $cs->registerCssFile($this->dwzThemeUrl . '/css/' . $this->ieHackCssFile);
        }


        $cs->registerCoreScript('jquery');
        if($this->debug == true){
            $cs->registerScriptFile($this->dwzScriptUrl . '/speedup.js');
            $cs->registerScriptFile($this->dwzScriptUrl . '/jquery.cookie.js');
            $cs->registerScriptFile($this->dwzScriptUrl . '/jquery.validate.js');
            $cs->registerScriptFile($this->dwzScriptUrl . '/jquery.bgiframe.js');
        }else{
            // minimize all js ??
            $cs->registerScriptFile($this->dwzScriptUrl . '/speedup.js');
            $cs->registerScriptFile($this->dwzScriptUrl . '/jquery.cookie.js');
            $cs->registerScriptFile($this->dwzScriptUrl . '/jquery.validate.js');
            $cs->registerScriptFile($this->dwzScriptUrl . '/jquery.bgiframe.js');
        }
        if (is_string($this->scriptFile)){
            $this->registerScriptFile($this->scriptFile);
        }elseif(is_array($this->scriptFile)) {
            foreach ($this->scriptFile as $scriptFile){
                $this->registerScriptFile($scriptFile);
            }
        }

        $cs->registerScriptFile($this->dwzScriptUrl . '/dwz.regional.zh.js');
        //  for custom purpose
        $cs->registerScriptFile($this->myBaseUrl . '/js/custom.js');
    }

    /**
     * @param $fileName
     * @param int $position
     */
    protected function registerScriptFile($fileName, $position = CClientScript::POS_HEAD)
    {
        // Yii::app()->getClientScript()->registerScriptFile($this->dwzScriptUrl . '/' . $fileName, $position);
        Yii::app()->getClientScript()->registerScriptFile($this->dwzBaseUrl . '/js/' . $fileName, $position);
    }
}