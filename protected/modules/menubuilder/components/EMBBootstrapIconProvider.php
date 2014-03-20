<?php
/**
 * EMBBootstrapIconProvider.php
 *
 * Provides the icons for the menus/items
 * Uses the icons from bootstrap, but no need to have bootstrap installed.
 *
 * Uses the multipleselectbox jQuery plugin from Dreamltf to generate the dropDownList with the icons
 *
 * @link http://plugins.jquery.com/project/jquerymultipleselectbox
 * @link http://code.google.com/p/jquerymultipleselectbox/
 *
 *
 * @author Joe Blocher <yii@myticket.at>
 * @copyright Copyright &copy; myticket it-solutions gmbh
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package menubuilder
 * @category User Interface
 * @version 1.0
 */

class EMBBootstrapIconProvider implements IEMBIconProvider
{
    protected static $_counter = 0;

    public static $icons = array(
        'icon-glass',
        'icon-music',
        'icon-search',
        'icon-envelope',
        'icon-heart',
        'icon-star',
        'icon-star-empty',
        'icon-user',
        'icon-film',
        'icon-th-large',
        'icon-th',
        'icon-th-list',
        'icon-ok',
        'icon-remove',
        'icon-zoom-in',
        'icon-zoom-out',
        'icon-off',
        'icon-signal',
        'icon-cog',
        'icon-trash',
        'icon-home',
        'icon-file',
        'icon-time',
        'icon-road',
        'icon-download-alt',
        'icon-download',
        'icon-upload',
        'icon-inbox',
        'icon-play-circle',
        'icon-repeat',
        'list-alt',
        'icon-lock',
        'icon-flag',
        'icon-headphones',
        'icon-volume-off',
        'icon-volume-down',
        'icon-volume-up',
        'icon-qrcode',
        'icon-barcode',
        'icon-tag',
        'icon-tags',
        'icon-book',
        'icon-bookmark',
        'icon-print',
        'icon-camera',
        'icon-font',
        'icon-bold',
        'icon-italic',
        'icon-text-height',
        'icon-text-width',
        'icon-align-left',
        'icon-lign-center',
        'icon-align-right',
        'icon-align-justify',
        'icon-list',
        'icon-indent-left',
        'icon-indent-right',
        'icon-facetime-video',
        'icon-picture',
        'icon-pencil',
        'icon-map-marker',
        'icon-adjust',
        'icon-edit',
        'icon-share',
        'icon-check',
        'icon-move',
        'icon-step-backward',
        'icon-fast-backward',
        'icon-backward',
        'icon-play',
        'icon-pause',
        'icon-stop',
        'icon-forward',
        'icon-fast-forward',
        'icon-step-forward',
        'icon-eject',
        'icon-pause',
        'icon-chevron-left',
        'icon-chevron-right',
        'icon-plus-sign',
        'icon-minus-sign',
        'icon-remove-sign',
        'icon-ok-sign',
        'icon-question-sign',
        'icon-info-sign',
        'icon-screenshot',
        'icon-remove-circle',
        'icon-ok-circle',
        'icon-ban-circle',
        'icon-arrow-left',
        'icon-arrow-right',
        'icon-arrow-up',
        'icon-arrow-down',
        'icon-share-alt',
        'icon-resize-full',
        'icon-resize-small',
        'icon-plus',
        'icon-minus',
        'icon-asterisk',
        'icon-exclamation-sign',
        'icon-gift',
        'icon-leaf',
        'icon-fire',
        'icon-eye-open',
        'icon-eye-close',
        'icon-warning-sign',
        'icon-plane',
        'icon-calendar',
        'icon-random',
        'icon-comment',
        'icon-magnet',
        'icon-chevron-up',
        'icon-chevron-down',
        'icon-retweet',
        'icon-shopping-cart',
        'icon-folder-close',
        'icon-folder-open',
        'icon-resize-vertical',
        'icon-resize-horizontal',
        'icon-hdd',
        'icon-bullhorn',
        'icon-bell',
        'icon-certificate',
        'icon-thumbs-up',
        'icon-thumbs-down',
        'icon-hand-right',
        'icon-hand-left',
        'icon-hand-up',
        'icon-hand-down',
        'icon-circle-arrow-right',
        'icon-circle-arrow-left',
        'icon-circle-arrow-up',
        'icon-circle-arrow-down',
        'icon-globe',
        'icon-wrench',
        'icon-tasks',
        'icon-filter',
        'icon-briefcase',
        'icon-fullscreen'
    );


    /**
     * Register the clientscript
     */
    public function registerClientScript()
    {
        $module = Yii::app()->getModule('menubuilder');
        $module->registerBootstrapIconsCss();

        $cs = Yii::app()->getClientScript();
        $assets = $module->getAssetsPath();

        $cs->registerCssFile($assets . '/multipleselectbox.css');
        $cs->registerScriptFile($assets . '/jquery.multipleselectbox-min.js');
    }

    /**
     * Render the javascript code for the dropDownList
     *
     * @see http://archive.plugins.jquery.com/project/jquerymultipleselectbox
     *      https://code.google.com/p/jquerymultipleselectbox/
     *
     * @param $cs
     */
    public function outputJsScript()
    {
        $counter = self::$_counter;

        $script = <<<EOP
    var controlDropDown$counter = $("#controlDropDown-$counter");
    var iconView$counter = $("#iconView-$counter");

    var dropDownContainer$counter = $("#MultipleSelectBoxDropDown-$counter").multipleSelectBox({
        submitField : controlDropDown$counter,
        maxLimit : 1,
        onSelectEnd : function(e,selectedArray) {
            $(this).slideUp("slow");
            if(selectedArray.length > 0)
              iconView$counter.attr('class',selectedArray[0].childNodes[0].getAttribute('class'));
        }
    });

    controlDropDown$counter.click(function(e) {
        e.stopPropagation();
        dropDownContainer$counter.slideDown("slow");
        $(document).one("click", function() {
            dropDownContainer$counter.slideUp("slow");
        });
    });
EOP;


        Yii::app()->getClientScript()->registerScript('iconselectbox-' . $counter, $script);
    }


    /**
     * Add the icon the a label
     *
     * @param $icon
     * @param $label
     * @param bool $enforceWrap
     * @return string
     */
    public static function getIconLabel($icon, $label, $enforceWrap = false)
    {
        if ($enforceWrap || (!empty($icon) && in_array($icon, self::$icons)))
            return '<i class="' . $icon . '"></i> ' . $label;
        else
            return $label;
    }

    /**
     * Get the dropDownList of the icons
     *
     * @param $model
     * @param $attribute
     * @param array $htmlOptions
     * @return string
     */
    public function dropDownList($model, $attribute, $htmlOptions = array())
    {
        self::$_counter++;
        $this->outputJsScript();

        $icon = $model->$attribute;
        if (empty($icon))
            $icon = 'no-icon';


        $name = get_class($model) . "[$attribute]";

        $htmlOptions['id'] = 'controlDropDown-' . self::$_counter;

        $htmlOptions['value'] = $this->getIconLabel($icon, $icon, true);
        $htmlOptions['style'] = 'border:none; cursor:pointer;width:160px;';


        $iconView = CHtml::tag('span', array('id' => 'iconView-' . self::$_counter, 'class' => $icon));

        $input = CHtml::textField($name, $icon, $htmlOptions);

        $output = CHtml::tag('div', array(), $input . $iconView);


        $output .= CHtml::openTag('ul', array('class' => 'dropdown-menu', 'id' => 'MultipleSelectBoxDropDown-' . self::$_counter, 'style' => 'display: none;')) . "\n";

        sort(self::$icons);
        $icons = array_merge(array('no-icon'),self::$icons);
        foreach ($icons as $icon)
        {
            $output .= CHtml::tag('li', array(), $this->getIconLabel($icon, $icon, true)) . "\n";
        }
        $output .= '</ul>';
        return $output;
    }


}
