<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-2-14
 * Time: 下午5:33
 * To change this template use File | Settings | File Templates.
 */
Yii::import('zii.widgets.CPortlet');
class YsPortlet extends CPortlet
{

    /**
     * @var string
     */
    public $skin = 'skin2';

    /**
     * @var string
     */
    public  $baseUrl ;

    /**
     * @var bool
     */
    public $debug  ;

    /**
     * @var array the HTML attributes for the portlet container tag.
     */
    public $htmlOptions=array('class'=>'portlet ',
       // 'style'=>'width:500px'
    );

    public function init(){
      parent::init();

        if (!isset($this->debug)) {
            $this->debug = defined(YII_DEBUG) ? YII_DEBUG : true;
        }
       // $this->htmlOptions = CMap::mergeArray($this->htmlOptions,array('style'=>'width:500px'));
        $this->publishAssets();
        $this->registerClientScript();
        //构造皮肤路径并发布
        $skinCss = $this->skin. '/css/box.css';
        Yii::app()->getClientScript()->registerCssFile($this->baseUrl.'/'.$skinCss);
    }
    
    public function publishAssets()
    {
        if(empty($this->baseUrl)){
            $assetsDir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets';
            $this->baseUrl = Yii::app()->getAssetManager()->publish($assetsDir, false, -1, $this->debug);
        }
        return $this;
    }


    public  function  registerClientScript(){
        Yii::app()->getClientScript()->
            registerScript(__CLASS__,'
      function toggleText(selector,text1,text2){
        //  alert("yeee"+$(selector).html());
        if( $.trim($(selector).html())==text1){
               $(selector).html(text2)
        }else{
            $(selector).html(text1);
        }
      }
      ',CClientScript::POS_HEAD);
        cs()->registerCss(__METHOD__,".{$this->contentCssClass}{width:100%;}");
    }

    /**
     * Renders the decoration for the portlet.
     * The default implementation will render the title if it is set.
     */
    protected function renderDecoration()
    {
        if ($this->title !== null) {
            echo "<div class=\"{$this->decorationCssClass}\">\n";
            echo     "<div class=\"{$this->titleCssClass}\">
                        {$this->title}
                        <a href='javascript:;' class='portlet-toggler' style='float: right;'
                         onclick='$(this).parent().parent().next().toggle();toggleText(this,\"+\",\"-\");
                           '>-</a>
                       </div>\n";
            echo   "</div>\n";
        }
    }

}
