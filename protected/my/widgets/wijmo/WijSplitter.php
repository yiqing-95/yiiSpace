<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 11-12-31
 * Time: 下午4:49
 * To change this template use File | Settings | File Templates.
 */
class WijSplitter extends EWijmoWidget
{

    /**
     * @var array
     * css style for the splitter
     *  #splitter
     *  {
     *  height: 200px;
     *   width: 200px;
     *    }
     */
    public $css = array();


    public function init()
    {
        parent::init();
        $this->registerCssFile('themes/wijmo/jquery.wijmo.wijsplitter.css');
        $this->registerScriptFile('wijmo/jquery.wijmo.wijutil.js,
                wijmo/jquery.wijmo.wijsplitter.js', CClientScript::POS_HEAD);
    }


    public function run()
    {
        if (!empty($this->css)) if (is_string($this->css)) {
            $this->cs->registerCss(__CLASS__ . '-' . $this->getId(), $this->css);
        } else if (is_array($this->css)) {
            $cssStr = '';
            foreach ($this->css as $selector => $rule) {
                $cssRule = $this->genCssFromArray($rule);
                $cssStr .= "{$selector}:{$cssRule} \n";
            }
            $this->cs->registerCss(__CLASS__ . '-' . $this->getId(), $cssStr);
        }

        $options = empty($this->options) ? '' : CJavaScript::encode($this->options);
        $jsCode = <<<SET_UP
             $("{$this->selector}").wijsplitter({$options});
SET_UP;

        $this->cs->registerScript(__CLASS__ . '#' . $this->getId(), $jsCode, CClientScript::POS_READY);

    }
}
