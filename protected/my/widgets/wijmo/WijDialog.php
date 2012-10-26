<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-1-9
 * Time: 上午11:59
 * To change this template use File | Settings | File Templates.
 */
class WijDialog extends EWijmoWidget
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


    public function init(){
        $this->cssFile = 'themes/wijmo/jquery.wijmo.wijdialog.css';
        $this->scriptFile = array(
            'wijmo/jquery.wijmo.wijutil.js',
            'wijmo/jquery.wijmo.wijdialog.js',
        )   ;
        parent::init();
    }


    public function run(){
        if (is_string($this->css)) {
            $this->cs->registerCss(__CLASS__.'-'.$this->getId(),$this->css);
        } else if (is_array($this->css)) {
            $cssStr = '';
            foreach ($this->css as $selector => $rule) {
                $cssRule = $this->genCssFromArray($rule);
                $cssStr .= "{$selector}:{$cssRule} \n";
            }
            $this->cs->registerCss(__CLASS__.'-'.$this->getId(),$cssStr);
        }


        $options=empty($this->options) ? '' : CJavaScript::encode($this->options);
        $jsCode = <<<SET_UP
             $("{$this->selector}").wijdialog({$options});
SET_UP;

        $this->cs->registerScript(__CLASS__.'#'.$this->getId(),$jsCode,CClientScript::POS_READY);

    }
}
