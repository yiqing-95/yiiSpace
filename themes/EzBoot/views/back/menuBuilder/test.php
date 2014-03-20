<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-1-6
 * Time: 下午7:51
 * To change this template use File | Settings | File Templates.
-----------------------------------------------------------------------------
$level=0;

foreach($categories as $n=>$category)
{
    if($category->level==$level)
        echo CHtml::closeTag('li')."\n";
    else if($category->level>$level)
        echo CHtml::openTag('ul')."\n";
    else
    {
        echo CHtml::closeTag('li')."\n";

        for($i=$level-$model->level;$i;$i--)
        {
            echo CHtml::closeTag('ul')."\n";
            echo CHtml::closeTag('li')."\n";
        }
    }

    echo CHtml::openTag('li');
    echo CHtml::encode($category->title);
    $level=$category->level;
}

for($i=$level;$i;$i--)
{
    echo CHtml::closeTag('li')."\n";
    echo CHtml::closeTag('ul')."\n";
}
 * ----------------------------------------------------------------------
 *  */

$level=0;

foreach($descendants as $n=>$node)
{
    if($node->level==$level){
        echo CHtml::closeTag('li')."\n";
    }else if($node->level>$level){
        echo CHtml::openTag('ul')."\n";
    }else{
        echo CHtml::closeTag('li')."\n";
        for($i=$level-$node->level;$i;$i--)
        {
            echo CHtml::closeTag('ul')."\n";
            echo CHtml::closeTag('li')."\n";
        }
    }

    echo CHtml::openTag('li');
    if($node->isLeaf()){
        echo CHtml::link($node->menu->label,$node->menu->calcUrl(),array('class'=>'menu_leaf'));
    }else{
        echo CHtml::encode($node->menu->label);
    }
    $level=$node->level;
}

for($i=$level;$i;$i--)
{
    echo CHtml::closeTag('li')."\n";
    echo CHtml::closeTag('ul')."\n";
}