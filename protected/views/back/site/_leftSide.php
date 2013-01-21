<?php
$level = 0;

foreach ($descendants as $n => $node) {
    if ($node->level == $level) {
        echo CHtml::closeTag('li') . "\n";
    } else if ($node->level > $level) {
        if($node->level == 3){
            echo CHtml::openTag('ul', array('class' =>'nav nav-tabs nav-stacked main-menu')) . "\n";
        }else{
            echo CHtml::openTag('ul', array('level' => $node->level)) . "\n";
        }

    } else {
        echo CHtml::closeTag('li') . "\n";
        for ($i = $level - $node->level; $i; $i--) {
            echo CHtml::closeTag('ul') . "\n";
            echo CHtml::closeTag('li') . "\n";
        }
    }
    if($node->level == 2){
        echo CHtml::openTag('li', array('id' => '_menu' . $node->id, 'level' => $node->level,'class'=>'nav-header hidden-tablet'));
    }else{
        echo CHtml::openTag('li', array('id' => '_menu' . $node->id, 'level' => $node->level));
    }
    if($node->level == 2){
        echo CHtml::encode($node->label);
    }else{
        //顶级菜单不显示
        echo CHtml::link(CHtml::encode($node->label), $node->calcUrl(), array('id' => $node->id));
    }


    $level = $node->level;
}

for ($i = $level; $i; $i--) {
    echo CHtml::closeTag('li') . "\n";
    echo CHtml::closeTag('ul') . "\n";
}
?>