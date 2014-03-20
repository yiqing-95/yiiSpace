<?php
/**
 * EMBListWidget.php
 *
 * Renders the nestable list with the menu item
 *
 * Uses the Nestable jQuery Plugin by David Bushell
 * @link http://dbushell.com/
 * @link https://github.com/dbushell/Nestable
 *
 * @see assets/jquery.nestable.js
 *
 *
 * @author Joe Blocher <yii@myticket.at>
 * @copyright Copyright &copy; myticket it-solutions gmbh
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package menubuilder
 * @category User Interface
 * @version 1.0
 */


class EMBListWidget extends CWidget
{
    /**
     * Max. Depth for nestable items
     * @var int
     */
    public $maxDepth=3;

    /**
     * The group option for jquery.nestable
     * @var int
     */
    public $group;

    /**
     * The label template for rendering the label in the nestable list
     * Adds the item label, info about userroles, adminroles, scenarios and the edit link
     *
     * @var string
     */
    public $labelTemplate = '<a href="{url}" target="_blank">&raquo;</a> {label} <span style="font-size: 80%;">[R: {userroles}][A: {adminroles}][S: {scenarios}]</span><span class="right">{edit}</span>';

    /**
     * The text for the edit link
     * @var string
     */
    public $editItemText='Edit';

    /**
     * Prefix for the labels
     *
     * @var array
     */
    public $attributesPrefix = array();

    /**
     * Instance of the menubuilder itemsProvider
     * @var
     */
    public $itemsProvider;

    /**
     * Use this cssFile instead of assets/nestable.css
     * @var
     */
    public $cssFile;

    /**
     * Publish an use the jquery.json2 lib for older browsers
     * @var bool
     */
    public $useJson2=true;

    /**
     * Configurable layout
     */
    public $title = 'Nestable items';
    public $wrapperTag = 'div';
    public $wrapperCssClass = 'emb-nestablelist';
    public $content;
    public $template = '{content}<h4>{title}</h4>{nestablelist}';

    /**
     * The options submitted to the nestable jquery plugin
     * Possible options see below: $defaultOptions
     */
    public $options=array();

    /**
     * Internal uses variables: don't touch
     */

    protected $defaultOptions = array(
        'listNodeName' => 'ol', // The HTML element to create for lists (default `'ol'`)
        'itemNodeName' => 'li', // The HTML element to create for list items (default `'li'`)
        'rootClass' => 'dd', // The class of the root element `.nestable()` was used on (default `'dd'`)
        'listClass' => 'dd-list', // The class of all list elements (default `'dd-list'`)
        'itemClass' => 'dd-item', // The class of all list item elements (default `'dd-item'`)
        'dragClass' => 'dd-dragel', // The class applied to the list element that is being dragged (default `'dd-dragel'`)
        'handleClass' => 'dd-handle', // The class of the content element inside each list item (default `'dd-handle'`)
        'collapsedClass' => 'dd-collapsed', // The class applied to lists that have been collapsed (default `'dd-collapsed'`)
        'placeClass' => 'dd-placeholder', // The class of the placeholder element (default `'dd-placeholder'`)
        'emptyClass' => 'dd-empty', // The class used for empty list placeholder elements (default `'dd-empty'`)
        'expandBtnHTML' => '<button data-action="expand">Expand></button>', // The HTML text used to generate a list item expand button (default `'<button data-action="expand">Expand></button>'`)
        'collapseBtnHTML' => '<button data-action="collapse">Collapse</button>', // The HTML text used to generate a list item collapse button (default `'<button data-action="collapse">Collapse</button>'`)
    );

    /**
     * Internal used
     */
    protected $_form;
    protected static $_scriptsPublished;

    /**
     * Get a nestable.js option
     * Returns the defaultOption if not set
     *
     * @param $name
     * @return mixed
     */
    protected function getOption($name)
    {
        return isset($this->options[$name]) ? $this->options[$name] : $this->defaultOptions[$name];
    }

    /**
     * Find a menu item model by itemid
     *
     * @param $id
     * @return mixed
     */
    public function findModel($itemId)
    {
        return $this->itemsProvider->findModel($itemId);
    }

    /**
     * Get the label for a model
     */
    protected function getLabel($model)
    {
        if(empty($this->labelTemplate))
            return 'Item ' . $model->itemid;

        $template = $this->labelTemplate;

        if(strpos($template,'{edit}') !== false)
        {
            if(!$model->getIsNewRecord())
            {
                $hiddenName = EMBConst::HIDDENNAME_EDITITEM;
                $htmlOptions['onclick'] = "$('#{$hiddenName}').val('".$model->itemid."');nestedConfigToHidden();$('#".EMBConst::FORMID."').submit();";
                $link=CHtml::link($this->editItemText,'#',$htmlOptions);
                $template = str_replace('{edit}',$link,$template);
            }
            else
                $template = str_replace('{edit}','',$template);
        }

        return $this->itemsProvider->getLabel($model,$template,$this->attributesPrefix);
    }


    /**
     * Output a template by calling the outputSection method
     *
     * @param $template
     */
    protected function outputTemplate($template)
    {
        ob_start();
        echo preg_replace_callback("/{(\w+)}/", array($this, 'outputSection'), $template);
        ob_end_flush();
    }


    /**
     * Renders a section.
     * Call the render... methods defined in the template property of this class
     */
    protected function outputSection($matches)
    {
        $method='render'.$matches[1];
        if(method_exists($this,$method))
        {
            $this->$method();
            $html=ob_get_contents();
            ob_clean();
            return $html;
        }
        else
            return $matches[0];
    }

    /**
     * Renders the nestable list for based on the nestedConfig of the itemProvider
     */
    public function renderNestableList()
    {
        $output = $this->getNestedListOutput($this->itemsProvider->nestedConfigToArray(),true);
        echo CHtml::tag('div', array('class' => $this->getOption('rootClass'),'id'=>$this->getRootId()),$output);
    }

    /**
     * Render the save button
     */
    public function renderSaveButton()
    {
        $this->outputSubmitButton('Save',$this->saveButtonLabel,$this->saveButtonHtmlOptions);

    }

    /**
     * Render the preview button
     */
    public function renderPreviewButton()
    {
       $this->outputSubmitButton('Preview',$this->previewButtonLabel,$this->previewButtonHtmlOptions);
    }

    /**
     * Render the title
     */
    public function renderTitle()
    {
        echo $this->title;
    }

    /**
     * Render the content
     */
    public function renderContent()
    {
        if(!empty($this->content))
        {
            if(is_array($this->content))
                foreach($this->content as $content)
                    echo $content;
            else
               echo $this->content;
        }
    }


    /**
     * The id for the nestable.js root element
     *
     * @return string
     */
    protected function getRootId()
    {
      return $this->getId().'_nestable';
    }

    /**
     * Register the client scripts
     */
    public function registerClientScript()
    {
        $options = array();

        if(isset($this->maxDepth))
            $options['maxDepth'] = $this->maxDepth;
        if(isset($this->group))
            $options['group'] = $this->group;

        $options = array_merge($options,$this->options);
        $jsOptions = CJavaScript::encode($options);
        $selector = '#'.$this->getId().'_nestable';
        $script = "jQuery('{$selector}').nestable($jsOptions);";
        $cs = Yii::app()->getClientScript();

        if(!self::$_scriptsPublished)
        {
            $assets = Yii::app()->getModule('menubuilder')->getAssetsPath();

            $cs->registerCssFile($assets . '/nestable.css');

            if (!empty($this->cssFile))
                $cs->registerCssFile($this->cssFile);
            else
                $cs->registerCssFile($assets . '/menubuilder.css');

            $cs->registerCoreScript('jquery');
            $cs->registerScriptFile($assets . '/jquery.nestable.js');

            if($this->useJson2)
                $cs->registerScriptFile($assets . '/jquery.json-2.4.min.js');

            self::$_scriptsPublished = true;
        }

        $cs->registerScript('nestable_'.$this->getId(), $script);
    }


    /**
     * Output the nestable items
     *
     * @param $nestedConfigArray
     * @param $parentVisible
     * @return string
     */
    protected function getNestedListOutput($nestedConfigArray,$parentVisible)
    {
        $output = array();

        $nestedModels = array();
        foreach ($nestedConfigArray as $key => $value)
            if (isset($value['id']))
            {
                if (($model = $this->findModel($value['id'])) !== false)
                {
                    $handleClass = $this->getOption('handleClass');

                    //modify the handle area depending on locked
                    $isLocked = strpos($handleClass,'locked') !== false;
                    $menuInvisible = strpos($handleClass,'emb-invisiblemenu') !== false;
                    $dragArea='';

                    if(!$isLocked)
                       $dragHandle = CHtml::tag('div', array('class' => $handleClass .' dd3-handle'), 'Drag');
                    else
                    {
                        $dragHandle = '';
                        $dragAreaClass = 'emb-locked';
                        if($menuInvisible)
                            $dragAreaClass .= ' emb-invisiblemenu';
                        $dragArea = CHtml::tag('div',array('class'=>$dragAreaClass),'&nbsp');
                    }

                    $contentClass = 'dd3-content';
                    if(!$model->visible || !$parentVisible)
                        $contentClass .= ' emb-invisiblemenuitem';

                    $content = $dragArea.CHtml::tag('div', array('class' => $contentClass), $this->getLabel($model));

                    if (!empty($value['children']))
                        $content .= $this->getNestedListOutput($value['children'],$model->visible);

                    $nestedModels[] = CHtml::tag($this->getOption('itemNodeName'), array('class' => $this->getOption('itemClass') .' dd3-item', 'data-id' => $value['id']), $dragHandle.$content);
                }
            }

        if(count($nestedModels)>0)
        {
           $output[] = CHtml::openTag($this->getOption('listNodeName'), array('class' => $this->getOption('listClass')));
           $output = array_merge($output,$nestedModels);
           $output[] = CHtml::closeTag($this->getOption('listNodeName'));
        }
        else
           $output[] = "<div class=\"{$this->getOption('emptyClass')}\"></div>";

        return implode(PHP_EOL, $output);
    }

    /**
     * Run the widget
     */
    public function run()
    {
        $this->registerClientScript();

        if(!empty($this->wrapperTag))
            echo CHtml::openTag($this->wrapperTag,array('class'=>$this->wrapperCssClass));

        $this->outputTemplate($this->template);

        if(!empty($this->wrapperTag))
            echo CHtml::closeTag($this->wrapperTag);
    }
}