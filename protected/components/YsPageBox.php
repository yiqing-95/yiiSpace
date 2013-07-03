<?php
/**
 *  封装类似yii portlet的组件
 * 这里使用的是CascadeFramework
 * @see http://jslegers.github.io/cascadeframework/components.html
 * User: yiqing
 * Date: 13-4-15
 * Time: 下午2:19
 * To change this template use File | Settings | File Templates.
 * -------------------------------------------------------
 * -------------------------------------------------------
 */

class YsPageBox extends YsSectionWidget{

    /**
     * @var string
     */
    public $header = '';

    /**
     * @var string
     */
    public $body = '';

    /**
     * @var string
     */
    public $footer = '';

    /**
     * @var string
     */
    public $template = '{header}{body}{footer}';

    /**
     * @var bool if true you can give any body structure
     * and i won't render the body structure ,you are responsible  for that
     */
    public $freeBody = false ;


    public function init(){
       if(isset($this->htmlOptions['class'])){
           $this->htmlOptions['class'] .= 'cell panel';
       }else{
           $this->htmlOptions['class'] = 'cell panel';
       }
        parent::init();
        ob_start();
        ob_implicit_flush(false);
    }

    public function run(){

        $body = ob_get_clean();
        if(!empty($body)){
            $this->body = $body;
        }
        parent::run();
    }

    public function  renderHeader(){
       echo '<div class="header">',
        $this->header,
        '</div>';
    }

    public function renderBody(){
        if($this->freeBody){
            echo $this->body ;
        }else{
            echo '<div class="body">',
            $this->body,
            '</div>';
        }
    }

    public function renderFooter(){
        echo '<div class="footer">',
        $this->footer,
        '</div>';
    }
}