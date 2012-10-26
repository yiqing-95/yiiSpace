<?php
/**
 * Created by JetBrains PhpStorm.
 * User: cztx
 * Date: 11-8-14
 * Time: 下午6:37
 * To change this template use File | Settings | File Templates.
 */
//require(Yii::getPathOfAlias('application.vendors.addendum') . DS . 'annotations.php');
class FormInput extends Annotation
{
       
}

/**
 * 不能把此注释写在上部  会引起循环解析的  所以为Annotation类
 * 写注释要格外注意
 * 以前的实现不太灵活 唯独使用json形式的赋值才算灵活：
 * -------------------------------------------------------------
 * http://code.google.com/p/addendum/wiki/ShortTutorialByExample
 *
 *Annotations can even hold arrays of values using {} syntax. For example:

        class RolesAllowed extends Annotation {}
        /** @RolesAllowed({'admin', 'web-editor'}) * 这里有个反斜杠
        class CMS {
         // some code
        }
        $reflection = new ReflectionAnnotatedClass('CMS');
        $annotation = $reflection->getAnnotation('RolesAllowed');
        $annotation->value; // contains array('admin', 'web-editor')
        Of course you can also use associative arrays.

        @Annotation({key1 = 1, key2 = 2, key3 = 3})
        Or even mix them and use nested arrays any way you like!

        @Annotation({key1 = 1, 2, 3, {4, key = 5}})
 * ----------------------------------------------------------------
 */

