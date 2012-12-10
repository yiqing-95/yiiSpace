<?php

/**
 * MultiModelForm.php
 *
 * Handling of multiple records and models in a form
 *
 * Uses the jQuery plugin RelCopy
 * @link http://www.andresvidal.com/labs/relcopy.html
 *
 * @author Joe Blocher <yii@myticket.at>
 * @copyright 2011 myticket it-solutions gmbh
 * @license New BSD License
 * @category User Interface
 * @version 4.1
 */
class MultiModelForm extends CWidget
{
    const CLASSPREFIX = 'mmf_'; //prefix for tag classes

    /**
     * The model to handle
     *
     * @var CModel $model
     */
    public $model;

    /**
     * Configuration of the form provided by the models method getMultiModelForm()
     *
     * This configuration array defines generation CForm
     * Can be a config array or a config file that returns the configuration
     *
     * @link http://www.yiiframework.com/doc/guide/1.1/en/form.builder
     * @var mixed $elements
     */
    public $formConfig = array();

    /**
     * Array of models loaded from db.
     * Created for example by $model->findAll();
     *
     * @var CModel $data
     */
    public $data;

    /**
     * The controller returns all validated items (array of model)
     * if a validation error occurs.
     * The form will then be rendered with error output.
     * $data will be ignored in this case.
     * @see method run()
     *
     * @var array $validatedItems
     */
    public $validatedItems;

    /**
     * Set to true if the error summary should be rendered for the model of this form
     *
     * @var boolean $showErrorSummary
     */
    public $showErrorSummary = false;


    /**
     * The text of the copy/clone link
     *
     * @var string $addItemText
     */
    public $addItemText = 'Add item';

    /**
     * Show the add item link as button
     *
     * @var boolean $addItemAsButton
     */
    public $addItemAsButton = false;

    /**
     * Alert text if options['limit']>0 and the limit is reached
     * See the options property below
     * @var string
     */
    public $limitText = 'The limit is reached';

    /**
     * Show 'Add item' link and empty item in errormode
     *
     * @var boolean $allowAddOnError
     */
    public $showAddItemOnError = true;


    /**
     * If false, the addItem link and empty row will not be displayed
     * @var bool
     */
    public $allowAddItem = true;

    /**
     * If false, the removeItem will not be displayed
     * @var bool
     */
    public $allowRemoveItem = true;

    /**
     * The text for the remove link
     * Can be an image tag too.
     * Leave empty to disable removing.
     *
     * @var string $removeText
     */
    public $removeText = 'Remove';

    /**
     * The confirmation text before remove an item
     * Set to null/empty to disable confirmation
     *
     * @var string $removeText
     */
    public $removeConfirm = 'Delete this item?';

    /**
     * The htmlOptions for the remove link
     *
     * @var array $removeHtmlOptions
     */
    public $removeHtmlOptions = array();

    /**
     * Show elements as table
     * If set to true, $fieldsetWrapper, $rowWrapper and $removeLinkWrapper will be ignored
     *
     * @var boolean
     */
    public $tableView = false;

    /**
     * The htmlOptions for the table tag
     *
     * @var array $tableHtmlOptions
     */
    public $tableHtmlOptions = array();

    /**
     * Items are rendered as <tfoot><tr><td>Item1</td><td>Item2</td> ...</tr></tfoot>
     *
     * @var string $tableFootCells
     */
    public $tableFootCells = array();


    /**
     * Set this attribute to enable manual sorting by drag/drop of the multiple items
     * Uses the CJuiSortable widget
     *
     * @var string the name of the attribute
     */
    public $sortAttribute;


    /**
     * The options property of the zii.widgets.jui.CJuiSortable
     *
     * @link http://www.yiiframework.com/doc/api/1.1/CJuiWidget#options-detail
     * @link http://jqueryui.com/demos/sortable/
     *
     * @var array
     */
    public $sortOptions = array(
        'placeholder' => 'ui-state-highlight',
        'opacity' => 0.8,
        'cursor' => 'move'
    );
    /**
     * Render elements in bootstrap layout
     * @var bool
     */
    public $bootstrapLayout = false;

    /**
     * The wrapper for each fieldset
     *
     * @var array $fieldsetWrapper
     */
    public $fieldsetWrapper = array(
        'tag' => 'div',
        'htmlOptions' => array('class' => 'view'), //'fieldset' is unknown in the default css context of form.css
    );

    /**
     * The wrapper for a row
     *
     * @var array $rowWrapper
     */
    public $rowWrapper = array(
        'tag' => 'div',
        'htmlOptions' => array('class' => 'row'),
    );

    /**
     * The wrapper for the removeLink
     *
     * @var array $fieldsetWrapper
     */
    public $removeLinkWrapper = array(
        'tag' => 'span',
        'htmlOptions' => array(),
    );


    /**
     * Hide the empty copyTemplate, show on Add Item click
     *
     * @var bool
     */
    public $hideCopyTemplate = true;

    /**
     * Set a limit on adding items
     * @var int
     */
    public $limit = 0;

    /**
     * The javascript code jsBeforeClone,jsAfterClone ...
     * This allows to handle widgets on cloning.
     * Important: 'this' is the current handled jQuery object
     * For CJuiDatePicker and extension 'datetimepicker' see prepared php-code below: afterNewIdDatePicker,afterNewIdDateTimePicker
     *
     * Usage if you have CJuiDatePicker to clone (assume your form elements are defined in the array $formConfig):
     * 'jsAfterNewId' => MultiModelForm::afterNewIdDateTimePicker($formConfig['elements']['mydatefield']),
     *
     */
    public $jsBeforeClone; // 'jsBeforeClone' => "alert(this.attr('class'));";
    public $jsAfterClone; // 'jsAfterClone' => "alert(this.attr('class'));";
    public $jsBeforeNewId; // 'jsBeforeNewId' => "alert(this.attr('id'));";
    public $jsAfterNewId; // 'jsAfterNewId' => "alert(this.attr('id'));";

    /**
     * Available options for the jQuery plugin RelCopy
     *
     * string excludeSelector - A jQuery selector used to exclude an element and its children
     * integer limit - The number of allowed copies. Default: 0 is unlimited
     * string append - Additional HTML to attach at the end of each copy.
     * string copyClass - A class to attach to each copy
     * boolean clearInputs - Option to clear each copies text input fields or textarea
     *
     * @link http://www.andresvidal.com/labs/relcopy.html
     *
     * @var array $options
     */
    public $options = array();

    /**
     * The assets url
     *
     * @var string $_assets
     */
    private $_assets;

    /**
     * Internal record count
     * @var integer
     */
    private $_recordCount;

    /**
     * Support for CJuiDatePicker
     * Set 'jsAfterNewId'=MultiModelForm::afterNewIdDateTimePicker($myFormConfig['elements']['mydate'])
     * if you use at least one datepicker.
     *
     * The options will be assigned from the config array of the element
     *
     * @param array $element
     * @return string
     */
    public static function afterNewIdDatePicker($element)
    {
        $options = isset($element['options']) ? $element['options'] : array();
        $jsOptions = CJavaScript::encode($options);

        $language = isset($element['language']) ? $element['language'] : '';
        if (!empty($language))
            $language = "jQuery.datepicker.regional['$language'],";

        return "if(this.hasClass('hasDatepicker')) {this.removeClass('hasDatepicker'); this.datepicker(jQuery.extend({showMonthAfterYear:false}, $language {$jsOptions}));};";
    }

    /**
     * Support for extension datetimepicker
     * @link http://www.yiiframework.com/extension/datetimepicker/
     *
     * @param array $element
     * @return string
     */
    public static function afterNewIdDateTimePicker($element)
    {
        $options = isset($element['options']) ? $element['options'] : array();
        $jsOptions = CJavaScript::encode($options);

        $language = isset($element['language']) ? $element['language'] : '';
        if (!empty($language))
            $language = "jQuery.datepicker.regional['$language'],";

        return "if(this.hasClass('hasDatepicker')) {this.removeClass('hasDatepicker').datetimepicker(jQuery.extend($language {$jsOptions}));};";
    }

    /**
     * Support for CJuiAutoComplete: not working - needs review
     *
     * @param array $element
     * @return string
     */

    public static function afterNewIdAutoComplete($element)
    {
        $options = isset($element['options']) ? $element['options'] : array();
        if (isset($element['sourceUrl']))
            $options['source'] = CHtml::normalizeUrl($element['sourceUrl']);
        else
            $options['source'] = $element['source'];

        $jsOptions = CJavaScript::encode($options);

        //return "this.autocomplete($jsOptions);"; //works for non-autocomplete elements
        //return "if(this.hasClass('ui-autocomplete-input')) this.autocomplete($jsOptions);";
        //return "if(this.hasClass('ui-autocomplete-input')) $('#'+this.attr('id')).autocomplete($jsOptions);";
        //return "if(this.hasClass('ui-autocomplete-input')) $('#'+this.attr('id')).autocomplete('destroy').autocomplete($jsOptions);";
        //return "if(this.hasClass('ui-autocomplete-input')) $('#'+this.attr('id')).unbind().removeClass('ui-autocomplete-input').removeAttr('autocomplete').removeAttr('role').removeAttr('aria-autocomplete').removeAttr('aria-haspopup').autocomplete($jsOptions);";
        //return "if(this.hasClass('ui-autocomplete-input')) this.unbind().removeClass('ui-autocomplete-input').removeAttr('autocomplete').removeAttr('role').removeAttr('aria-autocomplete').removeAttr('aria-haspopup').autocomplete($jsOptions);";
    }

    /**
     * This static function should be used in the controllers update action
     * The models will be validated before saving
     *
     * If a record is not valid, the invalid model will be set to $model
     * to display error summary
     *
     * @param mixed $model CActiveRecord or other CModel
     * @param array $validatedItems returns the array of validated records
     * @param array $deleteItems
     * @param array $masterValues attributes to assign before saving
     * @param array $formData (default = $_POST)
     * @return boolean
     */
    public static function save($model, &$validatedItems, &$deleteItems = array(), $masterValues = array(), $formData = null)
    {
        //validate if empty: means no validation has been done
        $doValidate = empty($validatedItems) && empty($deleteItems);

        if (!isset($formData))
            $formData = $_POST;

        $sortAttribute = !empty($formData[self::CLASSPREFIX . 'sortAttribute']) ? $formData[self::CLASSPREFIX . 'sortAttribute'] : null;
        $sortIndex = 0;

        if ($doValidate)
        {
            //validate and assign $masterValues
            if (!self::validate($model, $validatedItems, $deleteItems, $masterValues, $formData))
                return false;
        }

        if (!empty($validatedItems))
            foreach ($validatedItems as $item)
            {
                if (!$doValidate) //assign $masterValues
                {
                    if (!empty($masterValues))
                        $item->setAttributes($masterValues, false);
                }

                //if sortable, assign the sortAttribute
                if (!empty($sortAttribute))
                {
                    $sortIndex++;
                    $item->$sortAttribute = $sortIndex;
                }

                if (!$item->save())
                    return false;
            }

        //$deleteItems = array of primary keys to delete
        if (!empty($deleteItems))
            foreach ($deleteItems as $pk)
                if (!empty($pk))
                {
                    //array doesn't work with activerecord?
                    if (count($pk == 1))
                    {
                        $vals = array_values($pk);
                        $pk = $vals[0];
                    }

                    $model->deleteByPk($pk);
                }

        return true;
    }

    /**
     * Validates submitted formdata
     * If a record is not valid, the invalid model will be set to $model
     * to display error summary
     *
     * @param mixed $model
     * @param array $validatedItems returns the array of validated records
     * @param array $deleteItems returns the array of model for deleting
     * @param array $masterValues attributes to assign before saving
     * @param array $formData (default = $_POST)
     * @return boolean
     */
    public static function validate($model, &$validatedItems, &$deleteItems = array(), $masterValues = array(), $formData = null)
    {
        $widget = new MultiModelForm;
        $widget->model = $model;

        $widget->checkModel();

        if (!$widget->initItems($validatedItems, $deleteItems, $masterValues, $formData))
            return false; //at least one item is not valid
        else
            return true;
    }

    /**
     * Converts the submitted formdata into an array of model
     *
     * @param array $formData the postdata $_POST submitted by the form
     * @param array $validatedItems the items which were validated
     * @param array $deleteItems the items to delete
     * @param array $masterValues assign additional masterdata before save
     * @return array array of model
     */
    public function initItems(&$validatedItems, &$deleteItems, $masterValues = array(), $formData = null)
    {
        if (!isset($formData))
            $formData = $_POST;

        $result = true;
        $newItems = array();

        $validatedItems = array(); //bugfix: 1.0.2
        $deleteItems = array();

        $modelClass = get_class($this->model);

        if (!isset($formData) || empty($formData[$modelClass]))
            return true;

        //----------- NEW (on validation error) -----------

        if (isset($formData[$modelClass]['n__']))
        {
            foreach ($formData[$modelClass]['n__'] as $idx => $attributes)
            {
                $model = new $modelClass;
                $model->attributes = $attributes;

                if (!empty($masterValues))
                    $model->setAttributes($masterValues, false); //assign mastervalues

                // validate
                if (!$model->validate())
                    $result = false;

                $validatedItems[] = $model;
            }

            unset($formData[$modelClass]['n__']);
        }

        //----------- UPDATE -----------

        $allExistingPk = isset($formData[$modelClass]['pk__']) ? $formData[$modelClass]['pk__'] : null; //bugfix: 1.0.1

        if (isset($formData[$modelClass]['u__']))
        {
            foreach ($formData[$modelClass]['u__'] as $idx => $attributes)
            {
                $model = new $modelClass('update');

                //should work for CModel, mongodb models... too
                if (method_exists($model, 'setIsNewRecord'))
                    $model->setIsNewRecord(false);

                $model->attributes = $attributes;

                if (!empty($masterValues))
                    $model->setAttributes($masterValues, false); //assign mastervalues

                //ensure to assign primary keys (when pk is unsafe or not defined in rules)
                if (is_array($allExistingPk))
                {
                    $primaryKeys = $allExistingPk[$idx];
                    $model->setAttributes($primaryKeys, false);
                }

                // validate
                if (!$model->validate())
                    $result = false;

                $validatedItems[] = $model;

                // remove from $allExistingPk
                if (is_array($allExistingPk))
                    unset($allExistingPk[$idx]);
            }

            unset($formData[$modelClass]['u__']);
        }

        //----------- DELETE -----------

        // add remaining primarykeys to $deleteItems (reindex)
        if (is_array($allExistingPk))
            foreach ($allExistingPk as $idx => $delPks)
                $deleteItems[] = $delPks;

        // remove handled formdata pk__
        unset($formData[$modelClass]['pk__']);

        //----------- Check for cloned elements by jQuery -----------
        if(!empty($formData[$modelClass])) //has cloned elements
        {
            // use the first item as reference
            $refAttribute = key($formData[$modelClass]);
            $refArray = array_shift($formData[$modelClass]);

            if (!empty($refArray))
                foreach ($refArray as $idx => $value)
                {
                    // check continue if all values are empty
                    if (empty($value))
                    {
                        $allEmpty = true;
                        foreach ($formData[$modelClass] as $attrKey => $values)
                        {
                            if (is_array($values[$idx])) //bugfix v2.1.1 have to check empty array items too
                            {
                                $isEmpty = true;
                                foreach ($formData[$modelClass][$attrKey] as $item)
                                {
                                    if (!empty($item[$idx]))
                                    {
                                        $isEmpty = false;
                                        break;
                                    }
                                }
                            }
                            else
                                $isEmpty = empty($values[$idx]);

                            $allEmpty = $isEmpty && $allEmpty;
                            if (!$allEmpty)
                                break;
                        }

                        if ($allEmpty)
                            continue;
                    }

                    $model = new $modelClass;
                    $model->$refAttribute = $value;

                    foreach ($formData[$modelClass] as $attrKey => $values)
                    {
                        //v2.2 support for checkboxlist / radiolist
                        if (is_array($values[$idx]))
                        {
                            $arrayAttribute = array();
                            foreach ($formData[$modelClass][$attrKey] as $item)
                            {
                                if (!empty($item[$idx]))
                                    $arrayAttribute[] = $item[$idx];
                            }

                            $model->$attrKey = $arrayAttribute;
                        }
                        else
                            $model->$attrKey = $values[$idx];
                    }

                    //assign mastervalues without checking rules for new records
                    $model->setAttributes($masterValues, false);

                    // validate
                    if (!$model->validate())
                        $result = false;

                    $validatedItems[] = $model;
                }
        }

        return $result;
    }

    /**
     * Get the primary key as array (key => value)
     *
     * @param CModel $model
     * @return array
     */
    public function getPrimaryKey($model)
    {
        $result = array();

        if ($model instanceof CActiveRecord)
        {
            $pkValue = $model->primaryKey;
            if (!empty($pkValue))
            {
                $pkName = $model->primaryKey();
                if (empty($pkName))
                    $pkName = $model->tableSchema->primaryKey;

                $result = array($pkName => $pkValue);
            }
        }
        else // when working with EMongoDocument
            if (method_exists($model, 'primaryKey'))
            {
                $pkName = $model->primaryKey();
                $pkValue = $model->$pkName;
                if (empty($pkValue))
                    $result = array($pkName => $pkValue);
            }

        return $result;
    }


    /**
     * Get the copyClass
     *
     * @return string
     */
    public function getCopyClass()
    {
        if (isset($this->options['copyClass']))
            return $this->options['copyClass'];
        else
        {
            $selector = $this->id . '_copy';
            $this->options['copyClass'] = $selector;
            return $selector;
        }
    }


    /**
     * @since 3.2
     * @return string
     */
    public function getCopyFieldsetId()
    {
        return $this->id .'_copytemplate';
    }

    /**
     * The link for removing a fieldset
     *
     * @return string
     */
    public function getRemoveLink($isCopyTemplate=false)
    {
        if($isCopyTemplate && !$this->hideCopyTemplate)
            return '';

        if (empty($this->removeText) || !$this->allowRemoveItem) //added v3.1
            return '';

        $onClick = '$(this).parent().parent().remove(); mmfRecordCount--; return false;';

        if($isCopyTemplate && $this->hideCopyTemplate)
        {
            $copyId = $this->getCopyFieldsetId();
            $onClick = 'if($(this).parent().parent().attr("id")=="'.$copyId.'") {clearAllInputs($("#'.$copyId.'"));$(this).parent().parent().hide()} else ' . $onClick;
        }

        if (!empty($this->removeConfirm))
            $onClick = "if(confirm('{$this->removeConfirm}')) " . $onClick;

        $htmlOptions = array_merge($this->removeHtmlOptions, array('onclick' => $onClick));
        $htmlOptions['class'] = isset($htmlOptions['class']) ? $htmlOptions['class'].' '.self::CLASSPREFIX.'removelink' : self::CLASSPREFIX.'removelink';

        $link = CHtml::link($this->removeText, '#', $htmlOptions);

        return CHtml::tag($this->removeLinkWrapper['tag'],
            $this->removeLinkWrapper['htmlOptions'], $link);
    }

    /**
     * Check if rows has to be sortable
     * Works only if not is as tableView because the submitted $_POST data are not in the correct sorted order
     * Sorting in tableView needs more investigation/workaround ...
     *
     * @return bool
     */
    public function isSortable()
    {
        return !empty($this->sortAttribute) && !$this->tableView;
    }

    /**
     * Initialize the widget: register scripts
     */
    public function init()
    {
        $this->removeLinkWrapper['htmlOptions']['class'] = !empty($this->removeLinkWrapper['htmlOptions']['class']) ?
            $this->removeLinkWrapper['htmlOptions']['class'] .' '.self::CLASSPREFIX.'removelink' :
            self::CLASSPREFIX.'removelink';

        if ($this->tableView)
        {
            $this->fieldsetWrapper = array('tag' => 'tr', 'htmlOptions' => array('class' => self::CLASSPREFIX . 'row'));
            $this->rowWrapper = array('tag' => 'td', 'htmlOptions' => array('class' => self::CLASSPREFIX . 'cell'));
            $this->removeLinkWrapper = $this->rowWrapper;
            if($this->bootstrapLayout)
            {
               if(!isset($this->tableHtmlOptions['class']))
                   $this->tableHtmlOptions['class'] = 'table '.self::CLASSPREFIX . 'table';
        }
        }
        else
        if ($this->bootstrapLayout)
        {
            $this->rowWrapper = array('tag' => 'div', 'htmlOptions' => array('class' => 'control-group '.self::CLASSPREFIX.'row'));
        }

        $this->_recordCount = 0;

        $this->checkModel();
        $this->registerClientScript();
        parent::init();
    }


    /**
     * Check the model instance on init / after create
     * Add all model attributes as hidden and visible=false if they are not part of the formConfig
     * Need this because on update all attributes have to be submitted, no 'loadModel' is called
     */
    protected function checkModel()
    {
        if (is_string($this->model))
            $this->model = new $this->model;

        if (isset($this->model) && isset($this->formConfig))
        {
            // add undefined attributes in the form config as hidden fields and attribute visible = false
            if (isset($this->formConfig) && !empty($this->formConfig['elements']))
                foreach ($this->model->attributes as $attribute => $value)
                {
                    if (!array_key_exists($attribute, $this->formConfig['elements']))
                        $this->formConfig['elements'][$attribute] = array('type' => 'hidden', 'visible' => false);
                }
        }
    }


    /**
     * @return array the javascript options
     */
    protected function getClientOptions()
    {
        if (empty($this->options))
            $this->options = array();

        if (!empty($this->removeText) && !$this->hideCopyTemplate)
        {
            $append = $this->getRemoveLink();
            $this->options['append'] = empty($this->options['append']) ? $append : $append . ' ' . $this->options['append'];
        }

        if (!empty($this->jsBeforeClone))
            $this->options['beforeClone'] = $this->jsBeforeClone;

        if (!empty($this->jsAfterClone))
            $this->options['afterClone'] = $this->jsAfterClone;

        if (!empty($this->jsBeforeNewId))
            $this->options['beforeNewId'] = $this->jsBeforeNewId;

        if (!empty($this->jsAfterNewId))
            $this->options['afterNewId'] = $this->jsAfterNewId;

        $this->options['limitText'] = $this->limitText;

        return CJavaScript::encode($this->options);

    }

    /**
     * The id selector for jQuery.sortable
     *
     * @return string
     */
    protected function getSortSelectorId()
    {
        return self::CLASSPREFIX . 'sortable';
    }


    /**
     * Registers the relcopy javascript file.
     */
    public function registerClientScript()
    {
        $cs = Yii::app()->getClientScript();

        $this->_assets = Yii::app()->assetManager->publish(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets');

        $cs->registerCoreScript('jquery');
        $cs->registerScriptFile($this->_assets . '/js/jquery.relcopy.yii.4.0.js');

        $options = $this->getClientOptions();
        $cs->registerScript(__CLASS__ . '#' . $this->id, "jQuery('#{$this->id}').relCopy($options);");

        //add the script for jQuery.sortable
        if ($this->isSortable())
        {
            $cs->registerCoreScript('jquery.ui');
            $cssFile = $cs->getCoreScriptUrl() . '/jui/css/base/jquery-ui.css';
            $cs->registerCssFile($cssFile);

            $options = CJavaScript::encode($this->sortOptions);
            Yii::app()->getClientScript()->registerScript(__CLASS__ . '#' . $this->id . 'Sortable', "jQuery('#{$this->getSortSelectorId()}').sortable({$options});");
        }
    }

    /**
     * Render the top of the table: AddLink, Table header
     */
    public function renderTableBegin($renderAddLink)
    {
        $form = new MultiModelRenderForm($this->formConfig, $this->model);
        $form->parentWidget = $this;

        //add link as div
        if ($renderAddLink)
        {
            $addLink = $form->getAddLink();
            echo CHtml::tag('div', array('class' => self::CLASSPREFIX . 'addlink'), $addLink);
        }

        $tableHtmlOptions = array_merge(array('class' => self::CLASSPREFIX . 'table'), $this->tableHtmlOptions);

        //table
        echo CHtml::tag('table', $tableHtmlOptions, false, false);

        //thead
        $form->renderTableHeader();

        //tfoot
        if (!empty($this->tableFootCells))
        {
            $cells = '';
            foreach ($this->tableFootCells as $cell)
            {
                $cells .= CHtml::tag('td', array('class' => self::CLASSPREFIX . 'cell'), $cell);
            }

            $cells = CHtml::tag('tr', array('class' => self::CLASSPREFIX . 'row'), $cells);
            echo CHtml::tag('tfoot', array(), $cells);
        }

        //tbody
        $tbodyOptions = $this->isSortable() ? array('id' => $this->getSortSelectorId()) : array();
        echo CHtml::tag('tbody', $tbodyOptions, false, false);
    }

    /**
     * Check if limit is set and reached
     * @return bool
     */
    public function limitReached()
    {
        $limit = !empty($this->options['limit']) ? $this->options['limit'] : 0;

        return $limit>0 ? ($limit - $this->_recordCount) <= 0 : false;
    }


    /**
     * Renders the active form if a model and formConfig is set
     * $this->data is array of model
     */
    public function run()
    {
        if (empty($this->model) || empty($this->formConfig))
            return;

        //form is displayed again with some invalid models
        $isErrorMode = !empty($this->validatedItems);
        $showAddLink = $this->allowAddItem && (!$isErrorMode || ($isErrorMode && $this->showAddItemOnError));

        $this->formConfig['activeForm'] = array('class' => 'MultiModelEmbeddedForm');

        $idx = 0;
        $errorPk = null;


        if ($isErrorMode)
        {
            if ($this->showErrorSummary)
                echo CHtml::errorSummary($this->validatedItems);

            $data = $this->validatedItems;
        }
        else
            $data = $this->data; //from the db


        if ($this->tableView)
            $this->renderTableBegin($showAddLink);

        if ($this->isSortable())
        {
            //render the name of the sortAttribute as hidden input
            //used in MultiModelForm::save
            echo CHtml::hiddenField(self::CLASSPREFIX . 'sortAttribute', $this->sortAttribute);

            if (!$this->tableView)
                echo CHtml::openTag('div', array('id' => $this->getSortSelectorId()));
        }

        // existing records
        if (is_array($data) && !empty($data))
        {
            $this->_recordCount = count($data);

            foreach ($data as $model)
            {
                $form = new MultiModelRenderForm($this->formConfig, $model);
                $form->index = $idx;
                $form->parentWidget = $this;

                $form->primaryKey = $this->getPrimaryKey($model);

                if (!$this->tableView)
                {
                    if ($showAddLink && $idx == 0) // no existing data rendered
                        echo $form->renderAddLink();
                }

                // render pk outside of removeable tag, for checking records to delete
                // see method initItems()
                echo $form->renderHiddenPk('[pk__]');
                echo $form->render();

                $idx++;
            }
        }

        //if form is displayed first time or in errormode and want to show 'Add item' (and a 'CopyTemplate')
        if($showAddLink)
        {
            // add an empty fieldset as CopyTemplate
            $form = new MultiModelRenderForm($this->formConfig, $this->model);
            $form->index = $idx;
            $form->parentWidget = $this;
            $form->isCopyTemplate = true;

            if (!$this->tableView)
            {
                if ($idx == 0) // no existing data rendered
                    echo $form->renderAddLink();
            }

            echo $form->render();
        }

        echo CHtml::script('mmfRecordCount='.$this->_recordCount);

        if ($this->tableView)
        {
            echo CHtml::closeTag('tbody');
            echo CHtml::closeTag('table');
        }
        elseif ($this->isSortable())
            echo CHtml::closeTag('div');
    }
}

/**
 * The CForm to render the input form
 */
class MultiModelRenderForm extends CForm
{
    public $parentWidget;
    public $index;
    public $isCopyTemplate;
    public $primaryKey;
    /**
     * Modified for bootstrapLayout
     */
    public function renderButtons()
    {
        if($this->parentWidget->bootstrapLayout)
        {
            $output='';
            foreach($this->getButtons() as $button)
                $output.=$this->renderElement($button);
            return $output!=='' ? "<div class=\"form-actions\">".$output."</div>\n" : '';
        }
        else
            parent::renderButtons();
    }

    /**
     * Modified for bootstrapLayout
     */
    public function renderElement($element)
    {
        if($this->parentWidget->bootstrapLayout) //begin bootstrapLayout
        {
            if(is_string($element))
            {
                if(($e=$this[$element])===null && ($e=$this->getButtons()->itemAt($element))===null)
                    return $element;
                else
                    $element=$e;
            }
            if($element->getVisible())
            {
                if($element instanceof CFormInputElement)
                {
                    if($element->type==='hidden')
                        return "<div style=\"display:none\">\n".$element->render()."</div>\n";
                    else
                        return "<div class=\"controls field_{$element->name}\">\n".$element->render()."</div>\n";
                }
                else if($element instanceof CFormButtonElement)
                    return $element->render()."\n";
                else
                    return $element->render();
            }
            return '';
        } //end bootstrapLayout
        else
            parent::renderElement($element);
    }

    /**
     * Wraps a content with row wrapper
     *
     * @param string $content
     * @return string
     */
    protected function getWrappedRow($content)
    {
        return CHtml::tag($this->parentWidget->rowWrapper['tag'],
            $this->parentWidget->rowWrapper['htmlOptions'], $content);
    }

    /**
     * Wraps a content with fieldset wrapper
     *
     * @param string $content
     * @return string
     */
    protected function getWrappedFieldset($content)
    {
        $htmlOptions = $this->parentWidget->fieldsetWrapper['htmlOptions'];

        if($this->isCopyTemplate)
        {
            $htmlOptions['id'] = $this->parentWidget->getCopyFieldsetId();
            if($this->parentWidget->hideCopyTemplate)
                $htmlOptions['style'] = !empty($htmlOptions['style'])? $htmlOptions['style'] . ' display:none;' : 'display:none;';
        }

        return CHtml::tag($this->parentWidget->fieldsetWrapper['tag'],$htmlOptions, $content);
    }

    /**
     * Returns the generated label from Yii form builder
     * Needs to be replaced by the real attributeLabel
     * @see method  renderFormElements()
     *
     * @param string $prefix
     * @param string $attributeName
     * @return string
     */
    protected function getAutoCreatedLabel($prefix, $attributeName)
    {
        return ($this->model->generateAttributeLabel('[' . $prefix . '][' . $this->index . ']' . $attributeName));
    }

    /**
     * Renders the table head
     *
     * @return string
     */
    public function renderTableHeader()
    {
        $cells = '';

        foreach ($this->getElements() as $element)
        {
            if ($element->visible && isset($element->type) && $element->type != 'hidden') //bugfix v3.1
            {
                $text = empty($element->label) ? '&nbsp;' : $element->label;
                $options = array();

                if ($element->getRequired())
                {
                    $options = array('class' => CHtml::$requiredCss);
                    $text .= CHtml::$afterRequiredLabel;
                }

                $cells .= CHtml::tag('th', $options, $text);
            }
        }

        if(!empty($cells))
        {
            //add an empty column instead of remove link
            $cells .= CHtml::tag('th', array(), '&nbsp');

            $row = $this->getWrappedFieldset($cells);
            echo CHtml::tag('thead', array(), $cells);
        }

    }


    /**
     * Check if elem is a array type
     *
     * @param string $type
     * @return boolean
     */
    protected function isElementArrayType($type)
    {
        switch ($type)
        {
            case 'checkboxlist':
            case 'radiolist':
                return true;
            default:
                return false;
        } // switch
    }

    /**
     * Renders the label for this input.
     * The default implementation returns the result of {@link CHtml activeLabelEx}.
     * @return string the rendering result
     */
    public function renderElementLabel($element,$htmlOptions=array())
    {
        $class='';

        $options = array_merge($htmlOptions,array(
            'label'=>$element->getLabel(),
            'required'=>$element->getRequired()
        ));

        if($this->parentWidget->bootstrapLayout)
        {

            switch($element->type)
            {
                case 'checkbox':
                case 'checkboxlist':
                    $class='checkbox';

                case 'radio':
                case 'radiolist':
                    $class='radio';

                default:
                    $class='control-label';
            }
        }

        if(!empty($class))
            $options['class']=$class;

        if(!empty($element->attributes['id']))
        {
            $options['for'] = $element->attributes['id'];
        }

        return CHtml::activeLabel($element->getParent()->getModel(), $element->name, $options);
    }

    /**
     * Renders a single form element
     * Remove the '[]' from the label
     *
     * @return string
     */
    protected function renderFormElements()
    {
        $output = '';

        $elements = $this->getElements();

        foreach ($elements as $element)
        {
            if (isset($element->name)) //element is an attribute of the model
            {
                $elemName = $element->name;

                if($this->parentWidget->bootstrapLayout && !$this->parentWidget->tableView)
                    $element->layout = "{label}<div class=\"controls\">{input}\n{hint}\n{error}</div>";

                $elemLabel=$this->parentWidget->tableView ? '' : $this->renderElementLabel($element);
                $replaceLabel=array('{label}'=>$elemLabel);
                $element->label = false; //no label on $element->render()
                $element->layout = strtr($element->layout,$replaceLabel);

                $doRender = false;
                if ($this->isCopyTemplate && $element->visible) // new fieldset
                {
                    //Array types have to be rendered as array in the CopyTemplate
                    $element->name = $this->isElementArrayType($element->type) ? $elemName . '[][]' : $elemName . '[]';
                    $doRender = true;
                }
                elseif (!empty($this->primaryKey))
                { // existing fieldsets update

                    $prefix = 'u__';
                    $element->name = '[' . $prefix . '][' . $this->index . ']' . $elemName;
                    $doRender = true;
                }
                else
                { //in validation error mode: the new added items before
                    if ($element->visible)
                    {
                        $prefix = 'n__';
                        $element->name = '[' . $prefix . '][' . $this->index . ']' . $elemName;
                        $doRender = true;
                    }
                }

                if($doRender)
                {
                    $elemOutput = $element->render();
                    $output .= $element->type == 'hidden' ? $elemOutput : $this->getWrappedRow($elemOutput);
                }
            }
            else  //CFormStringElement...
                $output .= $element->render();
        }

        $output .= $this->parentWidget->getRemoveLink($this->isCopyTemplate);

        return $output;
    }

    /**
     * Renders the primary key value as hidden field
     * Need determine which records to delete
     *
     * @param string $classSuffix
     * @return string
     */
    public function renderHiddenPk($classSuffix = '[pk__]')
    {
        foreach ($this->primaryKey as $key => $value)
        {
            $modelClass = get_class($this->parentWidget->model);
            $name = $modelClass . $classSuffix . '[' . $this->index . ']' . '[' . $key . ']';
            return CHtml::hiddenField($name, $value);
        }
    }

    /**
     * Get the add item link or button
     *
     * @return string
     */
    public function getAddLink()
    {
        if($this->parentWidget->addItemAsButton)
        {
            echo CHtml::htmlButton($this->parentWidget->addItemText,
                  array('id' => $this->parentWidget->id,
                        'rel' => '.' . $this->parentWidget->getCopyClass()
                    ));
        }
        else
        {
            return CHtml::tag('a',
                array('id' => $this->parentWidget->id,
                    'href' => '#',
                    'rel' => '.' . $this->parentWidget->getCopyClass()
                ),
                $this->parentWidget->addItemText
            );
        }
    }

    /**
     * Renders the link 'Add' for cloning the DOM element
     *
     * @return string
     */
    public function renderAddLink()
    {
        $tag = $this->parentWidget->rowWrapper['tag'];
        $htmlOptions = $this->parentWidget->rowWrapper['htmlOptions'];

        $htmlOptions['class'] = !empty($htmlOptions['class']) ? $htmlOptions['class'] .' mmf_additem' : 'mmf_additem';

        return CHtml::tag($tag,$htmlOptions,$this->getAddLink());
    }

    /**
     * Renders the CForm
     * Each fieldset is wrapped with the fieldsetWrapper
     *
     * @return string
     */
    public function render()
    {
        $elemOutput = $this->renderBegin();
        $elemOutput .= $this->renderFormElements();
        $elemOutput .= $this->renderEnd();
        // wrap $elemOutput
        $wrapperClass = $this->parentWidget->fieldsetWrapper['htmlOptions']['class'];

        if ($this->isCopyTemplate)
        {
            $class = empty($wrapperClass)
                ? $this->parentWidget->getCopyClass()
                : $wrapperClass . ' ' . $this->parentWidget->getCopyClass();
        }
        else
            $class = $wrapperClass;

        $this->parentWidget->fieldsetWrapper['htmlOptions']['class'] = $class;
        return $this->getWrappedFieldset($elemOutput);
    }
}

/**
 * MultiModelEmbeddedForm
 *
 * A CActiveForm with no output of the form begin and close tag
 * In Yii 1.1.6 the form end/close is the only output of the methods init() and run()
 * Needs review in upcoming releases
 *
 */
class MultiModelEmbeddedForm extends CActiveForm
{
    /**
     * Initializes the widget.
     * Don't render the open tag
     */
    public function init()
    {
        ob_start();
        parent::init();
        ob_get_clean();
    }

    /**
     * Runs the widget.
     * Don't render the close tag
     */
    public function run()
    {
        ob_start();
        parent::run();
        ob_get_clean();
    }
}