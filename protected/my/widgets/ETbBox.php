<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 13-1-7
 * Time: 下午8:49
 * To change this template use File | Settings | File Templates.
 */
Yii::import('bootstrap.widgets.TbBox');
class ETbBox extends TbBox
{
    /**
     * Renders a header buttons to display the configured actions
     */
    public function renderButtons()
    {
        if (empty($this->headerButtons))
            return;

        echo '<div class="bootstrap-toolbar pull-right">';

        if(!empty($this->headerButtons) && is_array($this->headerButtons))
        {
            foreach($this->headerButtons as $button)
            {
                if(is_string($button)){
                    echo $button ;
                }elseif(is_array($button)){
                    $options = $button;
                    $buttonClass = $options['class'];
                    unset($options['class']);

                    if(strpos($buttonClass, 'TbButton') === false){
                        $this->controller->widget($buttonClass, $options);
                    }else{
                        if(!isset($options['htmlOptions']))
                            $options['htmlOptions'] = array();

                        $class = isset($options['htmlOptions']['class']) ? $options['htmlOptions']['class'] : '';
                        $options['htmlOptions']['class'] = $class .' pull-right';
                        $this->controller->widget($buttonClass, $options);
                    }
                }else{
                    throw new CException('the header should be string or array !');
                }

            }
        }

        echo '</div>';
    }
}
