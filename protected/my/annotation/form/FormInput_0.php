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
        /**
         * @var array Core input types (alias=>CHtml method name)
         */
        public static $coreTypes=array(
                'text'=>'activeTextField',
                'hidden'=>'activeHiddenField',
                'password'=>'activePasswordField',
                'textarea'=>'activeTextArea',
                'file'=>'activeFileField',
                'radio'=>'activeRadioButton',
                'checkbox'=>'activeCheckBox',
                'listbox'=>'activeListBox',
                'dropdownlist'=>'activeDropDownList',
                'checkboxlist'=>'activeCheckBoxList',
                'radiolist'=>'activeRadioButtonList',
        );

        /**
         * @var string the type of this input. This can be a widget class name, a path alias of a widget class name,
         * or a input type alias (text, hidden, password, textarea, file, radio, checkbox, listbox, dropdownlist, checkboxlist, or radiolist).
         * If a widget class, it must extend from {@link CInputWidget} or (@link CJuiInputWidget).
         */
        public $type;
        /**
         * @var string name of this input
         */
        public $name;
        /**
         * @var string hint text of this input
         */
        public $hint;
        /**
         * @var array the options for this input when it is a list box, drop-down list, check box list, or radio button list.
         * Please see {@link CHtml::listData} for details of generating this property value.
         */
        public $items=array();
        /**
         * @var array the options used when rendering the error part. This property will be passed
         * to the {@link CActiveForm::error} method call as its $htmlOptions parameter.
         * @see CActiveForm::error
         * @since 1.1.1
         */
        public $errorOptions=array();
        /**
         * @var boolean whether to allow AJAX-based validation for this input. Note that in order to use
         * AJAX-based validation, {@link CForm::activeForm} must be configured with 'enableAjaxValidation'=>true.
         * This property allows turning on or off  AJAX-based validation for individual input fields.
         * Defaults to true.
         * @since 1.1.7
         */
        public $enableAjaxValidation=true;
        /**
         * @var boolean whether to allow client-side validation for this input. Note that in order to use
         * client-side validation, {@link CForm::activeForm} must be configured with 'enableClientValidation'=>true.
         * This property allows turning on or off  client-side validation for individual input fields.
         * Defaults to true.
         * @since 1.1.7
         */
        public $enableClientValidation=true;
        /**
         * @var string the layout used to render label, input, hint and error. They correspond to the placeholders
         * "{label}", "{input}", "{hint}" and "{error}".
         */
        public $layout="{label}\n{input}\n{hint}\n{error}";

    //---------<扩展属性用来设这htmlOptions>---------------------------------------------------------------------
    //由于是通过反射来做的所以没办法欺骗了  不能通过魔术方法来设置额外属性了 只能规规矩矩的把htmlOption中的属性搬过来
    //或者 自己另外定义一个HtmlOptions标记
    public $maxlength;

  //---------<扩展属性用来设这htmlOptions>---------------------------------------------------------------------
}
