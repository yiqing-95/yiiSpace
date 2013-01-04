<?php 
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-1-6
 * Time: 下午10:13
 * To change this template use File | Settings | File Templates.
 */

$level = 0;

foreach ($descendants as $n => $node)
{
    if ($node->level == $level) {
        echo CHtml::closeTag('li') . "\n";
    } else if ($node->level > $level) {
        echo CHtml::openTag('ul',array('level'=>$node->level)) . "\n";
    } else {
        echo CHtml::closeTag('li') . "\n";
        for ($i = $level - $node->level; $i; $i--)
        {
            echo CHtml::closeTag('ul') . "\n";
            echo CHtml::closeTag('li') . "\n";
        }
    }

    echo CHtml::openTag('li',array('id'=>'_menu'.$node->id,'level'=>$node->level));

    if($node->level != 2){
        //顶级菜单不显示
        echo CHtml::link(CHtml::encode($node->label), $node->calcUrl(), array('id' => $node->id));
    }

    $level = $node->level;
}

for ($i = $level; $i; $i--)
{
    echo CHtml::closeTag('li') . "\n";
    echo CHtml::closeTag('ul') . "\n";
}
?>
