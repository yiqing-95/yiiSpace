<?php
/**
 * Created by JetBrains PhpStorm.
 * User: cztx
 * Date: 11-8-14
 * Time: 下午6:40
 * To change this template use File | Settings | File Templates.
 */
 
/**
 * FormString represents a string in a form.
 * ---------------------------------------------------
 * 解析方法：
 * 
 *
 *
 */
//require(Yii::getPathOfAlias('application.vendors.addendum') . DS . 'annotations.php');
class FormString extends Annotation
{
        /**
         * @var string the string content
         */
        public $content;
}