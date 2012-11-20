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

    public $freeHeaderActions = '';

    public $headActionsHtmlOptions = array();

    public function renderActions()
    {
        if (empty($this->headerActions) && empty($this->freeHeaderActions)) {
            return;
        }

        if (isset($this->headActionsHtmlOptions['class'])) {
            $this->headActionsHtmlOptions['class'] .= ' bootstrap-toolbar pull-right';
        } else {
            $this->headActionsHtmlOptions['class'] = 'bootstrap-toolbar pull-right';
        }

        echo CHtml::openTag('div', $this->headActionsHtmlOptions);

        if (!empty($this->freeHeaderActions)) {
            if (is_string($this->freeHeaderActions)) {
                echo $this->freeHeaderActions;
            } elseif (is_array($this->freeHeaderActions)) {
                foreach ($this->freeHeaderActions as $actionItem) {
                    if (is_string($actionItem)) {
                        echo $actionItem;
                    } else {
                        if (isset($actionItem['class'])) {
                            $className = $actionItem['class'];
                            unset($actionItem['class']);
                            $this->controller->widget($className, $actionItem);
                        }
                    }
                }
            }
        } else {

            $this->controller->widget('bootstrap.widgets.TbButtonGroup',
                array(
                    'type' => '',
                    'size' => 'mini',
                    'buttons' => array(
                        array(
                            'label' => $this->headerButtonActionsLabel,
                            'url' => '#'),
                        array(
                            'items' => $this->headerActions
                        ))
                ));
        }

        echo '</div>';
    }

}
