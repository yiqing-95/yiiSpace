<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 13-1-8
 * Time: 下午1:38
 * To change this template use File | Settings | File Templates.
 */
Yii::import('bootstrap.widgets.TbPager');
class TbMixPager extends TbPager
{

    public $alignment = TbPager::ALIGNMENT_RIGHT;

    public function run(){
        $this->registerClientScript();
        $buttons=$this->createPageButtons();
        if(empty($buttons))
            return;
        echo $this->header;
        echo CHtml::tag('ul',$this->htmlOptions,implode("\n",$buttons));
        echo $this->footer;
        echo $this->controller->widget('my.widgets.TbJumpPager',
             array('pages' => $this->pages,
                 'ajaxUpdate' => true,
                 'cssClass' => 'pull-right'),
             true);
    }
}
