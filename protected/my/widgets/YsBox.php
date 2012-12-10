<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-11-12
 * Time: 上午11:09
 * To change this template use File | Settings | File Templates.
 */
Yii::import('bootstrap.widgets.TbBox');
class YsBox extends TbBox
{
    /**
     * Renders a header buttons to display the configured actions
     */
    public function renderButtons()
    {
        if (empty($this->headerButtons))
            return;

        echo '<div class="bootstrap-toolbar pull-right">';

        if (!empty($this->headerButtons) && is_array($this->headerButtons)) {
            foreach ($this->headerButtons as $button) {
                if (is_string($button)) {
                    echo $button;
                } else {
                    $options = $button;
                    $button = $options['class'];
                    unset($options['class']);

                    if (strpos($button, 'TbButton') === false)
                        throw new CException('message');

                    if (!isset($options['htmlOptions']))
                        $options['htmlOptions'] = array();

                    $class = isset($options['htmlOptions']['class']) ? $options['htmlOptions']['class'] : '';
                    $options['htmlOptions']['class'] = $class . ' pull-right';

                    $this->controller->widget($button, $options);
                }

            }
        } elseif (!empty($this->headerButtons) && is_string($this->headerButtons)) {
            echo $this->headerButtons;
        }

        echo '</div>';
    }

}
