<?php
/**
 *  
 * User: yiqing
 * Date: 13-5-6
 * Time: 上午1:13
 * To change this template use File | Settings | File Templates.
 * -------------------------------------------------------
 * -------------------------------------------------------
 */

class CascadeCollapsible extends CWidget{

    /**
     * @var string
     */
    public $openText = 'show';
    /**
     * @var string
     */
    public $closeText = 'close';
    /**
     * @var string
     */
    public $body = '';

    public function init(){
         parent::init();
        ob_start();
        ob_implicit_flush(false);
    }

    public function run(){
        $body = ob_get_clean();
        if(!empty($body)){
            $this->body = $body;
        }
        $openText = $this->openText;
        $closeText = $this->closeText;
        $body = $this->body;

        $template = <<<TPL
<div class="collapsible">
                                                        <div class="header collapse-trigger">
                                                            <span class="icon icon-collapse"></span>
                                                            <a href="#">
                                                                <span class="collapsed-only">{$openText}</span>
                                                                <span class="uncollapsed-only">{$closeText}</span> panel
                                                            </a>
                                                        </div>
                                                        <div class="body collapse-section">
                                                            <div class="cell">
                                                                {$body}
                                                            </div>
                                                        </div>
                                                    </div>
TPL;

        echo $template ;
    }


}