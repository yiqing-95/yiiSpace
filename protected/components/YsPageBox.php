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
     * {header}{body}{footer}
     */
    public $template = '{header}{body}';

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

    //-------------------------------------------------\\
    /**
     * this is quick method for creating a panel
     * should be used together with the endPanel method !
     * @param array $options
     */
    static  public function beginPanel($options=array()){
        $defaults = array(
            'template'=>'{body}',
        );
        $options = empty($options)?$defaults:CMap::mergeArray($defaults,$options);
        Yii::app()->controller->beginWidget(__CLASS__,$options);
    }
    /**
     * this is quick method for creating a panel
     * should be used together with the endPanel method !
     * @param array $options|string
     */
    static  public function beginPanelWithHeader($options=array()){
        $defaults = array(
            'template'=>'{header}{body}',
        );
        if(is_string($options)){
            $defaults['header'] = $options ;

        }
            $options = is_string($options)? $defaults:CMap::mergeArray($defaults,$options);

        Yii::app()->controller->beginWidget(__CLASS__,$options);
    }

    static public function endPanel(){
        Yii::app()->controller->endWidget();
    }
    //-------------------------------------------------\\
}