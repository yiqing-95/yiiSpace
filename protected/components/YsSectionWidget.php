<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-10-29
 * Time: 下午8:39
 * To change this template use File | Settings | File Templates.
 * ---------------------------------------------------------------
 * widget that is composed of different part ,and you can use template to
 * layout the order or presentation
 * ---------------------------------------------------------------
 */
class YsSectionWidget extends CWidget
{

    /**
     * @var string
     */
    public $tagName = 'div';

    /**
     * @var array
     */
    public $htmlOptions = array();

    /**
     * @var string
     */
    public $template ;


    /**
     *
     */
    public function run()
    {
        if(empty($this->template)){
            return ;
        }
        echo CHtml::openTag($this->tagName, $this->htmlOptions) . "\n";

        $this->renderContent();

        echo CHtml::closeTag($this->tagName);
    }

    /**
     *
     */
    public function renderContent()
    {
        ob_start();
        echo preg_replace_callback("/{(\w+)}/", array($this, 'renderSection'), $this->template);
        ob_end_flush();
    }


    /**
     * @param $matches
     * @return string
     */
    protected function renderSection($matches)
    {
        $method = 'render' . $matches[1];
        if (method_exists($this, $method)) {
            $this->$method();
            $html = ob_get_contents();
            ob_clean();
            return $html;
        }
        else
            return $matches[0];
    }

}