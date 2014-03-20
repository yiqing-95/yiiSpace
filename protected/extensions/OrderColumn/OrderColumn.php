<?php

class OrderColumn extends CGridColumn {

    public $ajaxUrl;
    public $pk;
    public $cssClass = 'order_link';
    public $name;
    private $_upIcon;
    private $_downIcon;

    public function init() {
        $assetsDir = dirname(__FILE__) . "/assets";
        $gridId = $this->grid->getId();
        $this->ajaxUrl = array(Yii::app()->controller->id . "/order");


        $this->_upIcon = Yii::app()->assetManager->publish($assetsDir . "/up.gif");
        $this->_downIcon = Yii::app()->assetManager->publish($assetsDir . "/down.gif");

        Yii::app()->clientScript->registerCoreScript('jquery');

        $script = <<<SCRIPT
            $(document).ready(function() {
                $('.{$this->cssClass}').live('click', function(e) {
                    var link    = $(this).attr('href');
                    $.ajax({
                        cache: false,
                        dataType: 'json',
                        type: 'get',
                        url: link,
                        success: function(data) {
                            \$.fn.yiiGridView.update('$gridId');
                        }

                    });
                    return false;
                });

            });
SCRIPT;

        Yii::app()->clientScript->registerScript(__CLASS__ . "#{$this->cssClass}", $script, CClientScript::POS_END);
        Yii::app()->clientScript->registerCssFile(Yii::app()->assetManager->publish($assetsDir . "/orderColumn.css"));
    }

    public function renderDataCellContent($row, $data) {
        $value = CHtml::value($data, $this->name);
        $this->ajaxUrl['pk'] = $data->primaryKey;
        $this->ajaxUrl['name'] = $this->name;
        $this->ajaxUrl['value'] = $value;
        $this->ajaxUrl['move'] = 'up';
        $up = CHtml::link(CHtml::image($this->_upIcon), $this->ajaxUrl, array('class' => $this->cssClass));

        $this->ajaxUrl['move'] = 'down';
        $down = CHtml::link(CHtml::image($this->_downIcon), $this->ajaxUrl, array('class' => $this->cssClass));
        echo CHtml::tag('span', array(
            'style' => 'margin-bottom:3px',
                ), $up);

        echo CHtml::tag('span', array(
            'style' => 'float: left;',
                ), $down);
    }

}

?>