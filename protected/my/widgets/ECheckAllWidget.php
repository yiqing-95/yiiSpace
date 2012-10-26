<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-1-31
 * Time: 下午4:53
 * To change this template use File | Settings | File Templates.
 * -----------------------------------------------------------------
 * promotion : use a hidden input field to holds the selected values
 * ------------------------------------------------------------------
 */
class ECheckAllWidget extends CWidget
{
   const LABEL_POS_BEFORE = 'before';
   const LABEL_POS_AFTER = 'after';

    /**
     * @var int
     */
    public static $count=0;
    /**
     * @var int
     * position for the label occurring relative to checkbox
     */
    public $labelPosition = self::LABEL_POS_AFTER;

    /**
     * @var string
     */
    public $label = 'check all ';

    /**
     * @var string
     */
    public $childrenSelector = ':checkbox';

    /**
     * @var string
     */
    public $callback = '';

    /**
     * @var bool
     */
    public $checked = false;

    /**
     * @var array
     */
    protected $options = array();

    /**
     * @var array
     */
    public $htmlOptions = array('class' => 'checkbox-all');


   /**
    *
    */
    public function init()
    {
        $cs = Yii::app()->getClientScript();
        cs()->registerCoreScript('jquery')
            ->registerScript(__CLASS__ . '_plugin', $this->getJsPluginCode(), CClientScript::POS_END);

        $options = CMap::mergeArray(
            array('childrenSelector' => $this->childrenSelector,
                'callback' => $this->callback,),
            $this->options);

        $options = CJavaScript::encode($options);

        if(isset($this->htmlOptions['id'])){
            $this->id  = $this->htmlOptions['id'];
        }else{
            $this->htmlOptions['id'] = empty($this->id) ? ($this->id = 'checkAll_'.self::$count++) : $this->id;
        }
        if(empty($this->htmlOptions['name'])){
            $this->htmlOptions['name'] = $this->id;
        }

        $js = <<<JS_INIT
       $("#{$this->id}").echeckAll({$options});
JS_INIT;

        Yii::app()->getClientScript()->registerScript(__CLASS__ . '#' . $this->id, $js, CClientScript::POS_READY);


        if($this->label !== false){
            $for=  $this->id ; //CHtml::getIdByName($this->id);
            $htmlLabel = CHtml::label($this->label,$for,array());
            if($this->labelPosition == self::LABEL_POS_BEFORE){
                echo $htmlLabel ;
            }
            echo CHtml::checkBox($this->htmlOptions['name'] , $this->checked == true ,$this->htmlOptions);
            if($this->labelPosition == self::LABEL_POS_AFTER){
                echo  $htmlLabel ;
            }
        }else{
            echo CHtml::checkBox($this->htmlOptions['name'] , $this->checked == true ,$this->htmlOptions);
        }
    }


    /**
     * @return string
     */
    public function getJsPluginCode()
    {
        $pluginCode = <<<PLUGIN
   ;(function ($) {
        var methods,
            checkAllSettings = []; //this is a cache variable ( key => settings )

        methods = {
            init:function (options) {
                var settings = $.extend({
                    'childrenSelector':':checkbox'
                }, options || {});

                return this.each(function () {
                    var \$this = $(this);
                    var id = \$this.attr('id');
                    //缓存下跟本id关联的插件设置
                    checkAllSettings[id] = settings;
                    // check or unCheckAll
                    $("#" + id).live('click', function () {
                        var checked = this.checked;
                        $(settings.childrenSelector).each(function () {
                            this.checked = checked;
                        });

                         if (settings.callback !== undefined) {
                            var selectedValues = $("#" + id).echeckAll("getChecked");
                            settings.callback.apply(\$this, [selectedValues]);
                        }
                    });
                    //when  children checkBox checked or unChecked
                    $(settings.childrenSelector).live('click', function () {
                      var selectedValues = $("#" + id).echeckAll("getChecked");
                        if (settings.callback !== undefined) {
                            settings.callback.apply(\$this, [selectedValues]);
                        }
                         //only  all children checked the parent one will be checked ($(settings.childrenSelector + "[checked]"))
                        \$this.prop('checked', $(settings.childrenSelector).length==selectedValues.length);
                    });
                });
            },
            getChecked:function () {
                var settings = checkAllSettings[this.attr('id')],
                    parentId = this.attr('id'),
                    checked = [];

                $(settings.childrenSelector).each(function () {
                    if (this.checked) {
                        //check if current checkBox is parent
                        if (this.id != undefined && this.id == parentId) {
                            return;
                        }
                        checked.push(this.value);
                    }
                });
                return checked;
            }
        };

        $.fn.echeckAll = function (method) {
            if (methods[method]) {
                return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
            } else if (typeof method === 'object' || !method) {
                return methods.init.apply(this, arguments);
            } else {
                $.error('Method ' + method + ' does not exist on jQuery.echeckAll');
                return false;
            }
        };

        $.fn.echeckAll.settings = checkAllSettings;
        /**
         *Returns the  values of the currently checked children.
         * @param id
         */
        $.fn.echeckAll.getChecked = function (id) {
            return $('#' + id).echeckAll('getChecked');
        };
    })(jQuery);
PLUGIN;
        return $pluginCode;
    }

    /**
     * @param $name
     * @param $value
     * @return void
     *  magic method  will treat the undeclared var  as the option which
     * passed to the underline js plugin
     */
    public function __set($name, $value)
    {
        try {
            parent::__set($name, $value);
        } catch (CException $e) {
            $this->options[$name] = $value;
        }
    }
}
