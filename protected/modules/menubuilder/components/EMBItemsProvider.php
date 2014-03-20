<?php
/**
 * EMBItemsProvider.php
 *
 * Generate the menuitems for a CMenu, DropDownList or List
 * Override this class, add a new output method and register this in the modules config 'itemsProviderClass' => 'MyItemsProvider'
 * if out need a special output for menu that is not inherited from CMenu
 *
 *
 * @author Joe Blocher <yii@myticket.at>
 * @copyright Copyright &copy; myticket it-solutions gmbh
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package menubuilder
 * @category User Interface
 * @version 1.0
 */

class EMBItemsProvider extends CComponent
{
    /**
     * The JSON string for nested items (id=itemid of the menuitem)
     * [{"id":13},{"id":14},{"id":15,"children":[{"id":16},{"id":17},{"id":18}]} ....]
     *
     * @var string
     */
    public $nestedConfig;

    /**
     * A evaluateExpression callback
     * @var mixed string or array
     */
    public $onVisible;
    public $onActive;
    public $onLabel;
    public $onUrl;

    /**
     * The language for rendering the label
     * @var string
     */
    public $language;

    /**
     * Class of the iconProvider
     * @var string
     */
    public $iconProviderClass='EMBBootstrapIconProvider';

    /**
     * Global ajaxOptions assigned to every menuitem
     * @var array
     */
    public $ajaxOptions = array();

    /**
     * Wether to assign ajax as onclick of the item or bind in document-ready
     * @var bool
     */
    public $useAjaxOnClick=false;

    /**
     * If true: $nestedConfig is empty use all items to create a nestedConfigArray with all items at level 0
     * If false: return an empty nestedConfigArray
     *
     * @var bool
     */
    public $enforceNestedConfig = true;

    /**
     * Internal used
     */
    protected $_items = array();
    protected $_models;

    /**
     * Get the items
     *
     * @return array
     */
    public function getItems()
    {
        return $this->_items;
    }

    /**
     * Set the items and initalize the menuitem models
     *
     * @param $items
     */
    public function setItems($items)
    {
        $this->_models=null;
        $this->_items = $items;
        $this->initModels();
    }


    /**
     * Generate a label from model attributes in the format of the template
     * @param $model
     * @param $template
     * @param array $attributesPrefix
     * @param bool $encode
     * @param bool $addIcon
     * @return mixed
     */
    public function getLabel($model, $template,$attributesPrefix=array(),$encode=true,$addIcon=true)
    {
        $iconProviderClass = $this->iconProviderClass;
        preg_match_all("/{(\w+)}/", $template, $matches);

        if (is_array($matches))
        {
            foreach ($matches[1] as $idx => $attribute)
            {
                if($attribute=='label')
                {
                    $value = $model->getI8NAttribute('labels',$this->language);

                    if($encode)
                        $value = CHtml::encode($value);

                    if($addIcon && !empty($model->icon) && !empty($iconProviderClass))
                        $value = $iconProviderClass::getIconLabel($model->icon,$value);

                    $template = str_replace($matches[0][$idx], $value, $template);
                }
                else
                if (isset($model->{$attribute}))
                {
                    $value = $model->{$attribute};
                    if($attribute=='url' && strpos($value,'://') === false)
                        $value = Yii::app()->createUrl($value);

                    if(isset($attributesPrefix[$attribute]) && !empty($value))
                        $value = $attributesPrefix[$attribute] . $value;

                    $template = str_replace($matches[0][$idx], $value, $template);
                }
            }
        }

        return $template;
    }

    /**
     * Initialize the models
     *
     * @return array
     */
    public function initModels()
    {
        if (!isset($this->_models))
        {
            $this->_models = array();

            if (!empty($this->items))
                foreach ($this->items as $key => $model)
                    $this->_models[$model->itemid] = is_array($model) ? (object)$model : $model;
        }

        return $this->_models;
    }

    /**
     * Find a menuitem model
     *
     * @param $id
     * @return bool
     */
    public function findModel($id)
    {
        return isset($this->_models[$id]) ? $this->_models[$id] : false;
    }

    /**
     * Get the nestedConfig JSON string as array
     *
     * @return array|mixed
     */
    public function nestedConfigToArray()
    {
        if (empty($this->items))
            return array();

        if (!empty($this->nestedConfig))
            return CJSON::decode($this->nestedConfig);

        return $this->enforceNestedConfig ? array() : $this->getAllItemsRulesArray();
    }

    /**
     * Return all item models as array
     *
     * @return array
     */
    public function getAllItemsRulesArray()
    {
        $nestedConfigArray = array();
        foreach ($this->_models as $id => $model)
            $nestedConfigArray[]['id'] = $id;

        return $nestedConfigArray;
    }


    /**
     * Get an array of an options string like
     * class="link-class",name="AName",...
     *
     * @param $optionsStr
     */
    public function optionsStringToArray($optionsStr)
    {
        $result = array();
        if(!empty($optionsStr))
        {
          $parts=preg_split('/[;=]+/',$optionsStr);

          for($i=0;$i<count($parts);$i++)
          {
            if($i<count($parts)-1)
            {
                $result[trim($parts[$i])]=trim($parts[$i+1]);
                $i++;
            }
          }
        }
        return $result;
    }

    /**
     * Get the menuitems as CMenu output
     *
     * @param string $labelDetailTemplate
     * @return array
     */
    public function getCMenuItems($labelDetailTemplate = '{label}')
    {
        $itemAttributes = array('url','label','visible','active','template'=>'template','linkOptions','itemOptions','submenuOptions');
        return $this->_getCMenuItems($this->nestedConfigToArray(), $labelDetailTemplate, $itemAttributes);
    }

    /**
     * Get the menuitems as dropDownList data
     *
     * @param array $htmlOptions
     * @param string $labelDetailTemplate
     * @param string $keysSeparator
     * @param null $menuId
     * @return array
     */
    public function getDropDownListData($htmlOptions = array(), $labelDetailTemplate = '{label}',$keysSeparator='__',$menuId=null)
    {
        $level = 0;
        $data = array();
        $this->_initDropDownListData($data, $level,$this->nestedConfigToArray(),$labelDetailTemplate,$htmlOptions,$keysSeparator,$menuId);
        return $data;
    }

    /**
     * Get the menuitems as nested tags
     *
     * @param string $listTag
     * @param string $itemTag
     * @param array $listHtmlOptions
     * @param array $itemHtmlOptions
     * @param bool $wrapLink
     * @param array $linkHtmlOptions
     * @param string $labelDetailTemplate
     * @return string
     */
    public function getListData($listTag='ul',$itemTag='li',$listHtmlOptions = array(),$itemHtmlOptions = array(),$wrapLink=true,$linkHtmlOptions=array(),$labelDetailTemplate = '{label}')
    {
        $output = array();
        $this->_getListData($output, $listTag, $itemTag, $this->nestedConfigToArray(), $labelDetailTemplate, $listHtmlOptions, $itemHtmlOptions,$wrapLink,$linkHtmlOptions);
        return implode(PHP_EOL, $output);
    }

    /**
     * Recursive method to get the items as CMenu
     *
     * @param $nestedConfigArray
     * @param $labelDetailTemplate
     * @param $itemAttributes
     * @return array
     */
    protected function _getCMenuItems($nestedConfigArray, $labelDetailTemplate, $itemAttributes)
    {
        $output = array();
        if (!empty($nestedConfigArray))
            foreach ($nestedConfigArray as $key => $value)
                if (isset($value['id']))
                {
                    $itemId = $value['id'];
                    if (($model = $this->findModel($itemId)) !== false)
                    {
                        $visible = isset($model->visible) ? $model->visible : true;
                        if(!empty($this->onVisible))
                            $visible = $this->evaluateExpression($this->onVisible,array('visible'=>$visible,'model'=>$model,'itemsProvider'=>$this));

                        if(!$visible)
                            continue;

                        $menuItem = array();
                        $url = '';

                        foreach ($itemAttributes as $attribute)
                        {
                                switch($attribute)
                                {
                                    case 'url':
                                        $url = empty($model->{$attribute}) ? '#' : $model->{$attribute};
                                        if(strpos($url,'://') === false)
                                           $url = Yii::app()->createUrl($url);
                                        if(!empty($this->onUrl))
                                            $url = $this->evaluateExpression($this->onUrl,array('url'=>$url,'model'=>$model,'itemsProvider'=>$this));

                                        $menuItem[$attribute] = $url;
                                        break;

                                    case 'label':
                                        $menuItem[$attribute] = self::getLabel($model, $labelDetailTemplate);
                                        if(!empty($this->onLabel))
                                            $menuItem[$attribute] = $this->evaluateExpression($this->onLabel,array('label'=>$menuItem[$attribute],'model'=>$model,'itemsProvider'=>$this));
                                        break;

                                    case 'template':
                                        if(!empty($model->{$attribute}))
                                            $menuItem[$attribute] = $model->{$attribute};
                                        break;

                                    case 'linkOptions':
                                        $options=array();
                                        if(!empty($model->{$attribute}))
                                            $options=$this->optionsStringToArray($model->{$attribute});

                                        if($model->descriptionashint && !empty($model->descriptions))
                                        {
                                            $hint = array_key_exists('title',$options) ? $options['title'] .' ' : '';
                                            $options['title']=$hint.$model->getI8NAttribute('descriptions',$this->language);
                                        }

                                        if(!empty($options))
                                           $menuItem[$attribute] = $options;
                                        break;

                                    case 'itemOptions':
                                    case 'submenuOptions':
                                        if(!empty($model->{$attribute}))
                                        {
                                            $options=$this->optionsStringToArray($model->{$attribute});
                                            $menuItem[$attribute] = $options;
                                        }
                                        break;

                                    default:
                                        $menuItem[$attribute] = $model->{$attribute};
                                }
                        }


                        if(!empty($model->target))
                        {
                            if(empty($menuItem['linkOptions']))
                                $menuItem['linkOptions']=array();
                            $menuItem['linkOptions']['target'] = $model->target;
                        }


                        if(!empty($this->ajaxOptions) || !empty($model->ajaxOptions))
                        {
                            if(empty($menuItem['linkOptions']))
                                $menuItem['linkOptions']=array();

                            $options=array_merge($this->ajaxOptions,$this->optionsStringToArray($model->ajaxOptions));

                            if(empty($options['url']))
                                $options['url']=$url;

                            $menuItem['url']='#';
                            if($this->useAjaxOnClick)
                            {
                                $onclick = isset($menuItem['linkOptions']['onclick']) ? $menuItem['linkOptions']['onclick'] : '';
                                $menuItem['linkOptions']['onclick']=$onclick.CHtml::ajax($options).'return false;';
                            }
                            else
                                $menuItem['linkOptions']['ajax']=$options;
                        }

                        if(!empty($this->onActive))
                        {
                            $active = isset($menuItem['active']) ? (boolean)$menuItem['active'] : false;
                            $menuItem['active'] = $this->evaluateExpression($this->onActive,array('active'=>$active,'model'=>$model,'itemsProvider'=>$this));
                        }
                        else
                            $menuItem['active'] = (boolean)$menuItem['active'];


                        if (!empty($value['children']))
                            $menuItem['items'] = $this->_getCMenuItems($value['children'],$labelDetailTemplate, $itemAttributes);

                        $output[] = $menuItem;
                    }
                }

        return $output;
    }

    /**
     * Recursive method to the items as dropDownData
     *
     * @param $data
     * @param $level
     * @param $nestedConfigArray
     * @param $labelDetailTemplate
     * @param $htmlOptions
     * @param $keysSeparator
     * @param $menuId
     */
    protected function _initDropDownListData(&$data, &$level, $nestedConfigArray, $labelDetailTemplate, $htmlOptions,$keysSeparator,$menuId)
    {
        $raw = isset($htmlOptions['encode']) && !$htmlOptions['encode'];

        if (!empty($nestedConfigArray))
            foreach ($nestedConfigArray as $key => $value)
                if (isset($value['id'])) //= itemid
                {
                    $itemId = $value['id'];
                    if (($model = $this->findModel($itemId)) !== false)
                    {
                        $visible = isset($model->visible) ? $model->visible : true;
                        if(!empty($this->onVisible))
                            $visible = $this->evaluateExpression($this->onVisible,array('visible'=>$visible,'model'=>$model,'itemsProvider'=>$this));

                        if(!$visible)
                            continue;

                        $label = self::getLabel($model, $labelDetailTemplate,array(),true,false); //icon not supported in standard dropdownlist
                        if (!$raw)
                            $label = CHtml::encode($label);

                        if(!empty($this->onLabel))
                            $label = $this->evaluateExpression($this->onLabel,array('label'=>$label,'model'=>$model,'itemsProvider'=>$this));

                        $prefix = '';
                        for ($i = 0; $i < $level; $i++)
                            $prefix .= '&nbsp;&nbsp;';

                        //add the $menuId to the key
                        $itemKey = !empty($menuId) ? $menuId .$keysSeparator.$itemId : $itemId;
                        $data[$itemKey] = $prefix . $label;

                        if (!empty($value['children']))
                        {
                            $level++;
                            $this->_initDropDownListData($data, $level, $value['children'], $labelDetailTemplate, $htmlOptions,$keysSeparator,$menuId);
                            $level--;
                        }
                    }
                }
    }

    /**
     * Recursive method to get the items as nested tags
     *
     * @param $output
     * @param $listTag
     * @param $itemTag
     * @param $nestedConfigArray
     * @param $labelDetailTemplate
     * @param $listHtmlOptions
     * @param $itemHtmlOptions
     * @param $wrapLink
     * @param $linkHtmlOptions
     */
    protected function _getListData(&$output, $listTag, $itemTag, $nestedConfigArray, $labelDetailTemplate, $listHtmlOptions, $itemHtmlOptions,$wrapLink,$linkHtmlOptions)
    {
        $output[] = CHtml::openTag($listTag, $listHtmlOptions);

        $saveItemHtmlOptions = $itemHtmlOptions;
        $savelinkHtmlOptions = $linkHtmlOptions;

        if (!empty($nestedConfigArray))
            foreach ($nestedConfigArray as $key => $value)
                if (isset($value['id']))
                {
                    if (($model = $this->findModel($value['id'])) !== false)
                    {
                        $visible = isset($model->visible) ? $model->visible : true;
                        if(!empty($this->onVisible))
                            $visible = $this->evaluateExpression($this->onVisible,array('visible'=>$visible,'model'=>$model,'itemsProvider'=>$this));

                        if(!$visible)
                            continue;

                        if(!empty($this->onActive))
                        {
                            $hasActiveClass = !empty($itemHtmlOptions['class']) && strpos($itemHtmlOptions['class'],'active') !== false;

                            $active = $this->evaluateExpression($this->onActive,array('active'=>$hasActiveClass,'model'=>$model,'itemsProvider'=>$this));
                            if($active && !$hasActiveClass)
                            {
                                if(!empty($itemHtmlOptions['class']))
                                   $itemHtmlOptions['class'] = $itemHtmlOptions['class'].' active';
                                else
                                    $itemHtmlOptions['class']='active';
                            }
                            else
                                if(!$active && $hasActiveClass)
                                {
                                    $itemHtmlOptions['class'] = str_replace('active','',$itemHtmlOptions['class']);
                                }
                        }

                        $label=self::getLabel($model, $labelDetailTemplate);
                        if(!empty($this->onLabel))
                            $label = $this->evaluateExpression($this->onLabel,array('label'=>$label,'model'=>$model,'itemsProvider'=>$this));

                        if($wrapLink)
                        {
                            $url = empty($model->url) ? '#' : $model->url;
                            if(strpos($url,'://') === false)
                                $url = Yii::app()->createUrl($url);

                            if(!empty($this->onUrl))
                                $url = $this->evaluateExpression($this->onUrl,array('url'=>$url,'model'=>$model,'itemsProvider'=>$this));

                            $linkHtmlOptions = !empty($model->linkOptions) ? $this->optionsStringToArray($model->linkOptions) : array();

                            if(!empty($model->target))
                                $linkHtmlOptions['target'] = $model->target;

                            if($model->descriptionashint && !empty($model->descriptions))
                            {
                                $hint = array_key_exists('title',$linkHtmlOptions) ? $linkHtmlOptions['title'] .' ' : '';
                                $linkHtmlOptions['title']=$hint.$model->getI8NAttribute('descriptions',$this->language);
                            }

                            if(!empty($this->ajaxOptions) || !empty($model->ajaxOptions))
                            {
                                $ajaxOptions = isset($linkHtmlOptions['ajax']) ? $linkHtmlOptions['ajax'] : array();
                                $ajaxOptions=array_merge($this->ajaxOptions,$ajaxOptions,$this->optionsStringToArray($model->ajaxOptions));

                                if(empty($ajaxOptions['url']))
                                    $ajaxOptions['url']=$url;

                                $url='#';
                                if($this->useAjaxOnClick)
                                {
                                    $onclick = isset($linkHtmlOptions['onclick']) ? $linkHtmlOptions['onclick'] : '';
                                    $linkHtmlOptions['onclick']=$onclick.CHtml::ajax($ajaxOptions).'return false;';
                                }
                                else
                                    $linkHtmlOptions['ajax']=$ajaxOptions;
                            }

                            $label = CHtml::link($label,$url,$linkHtmlOptions);
                        }

                        $output[] = CHtml::tag($itemTag, $itemHtmlOptions, $label, $labelDetailTemplate);
                        if (!empty($value['children']))
                        {
                            $this->_getListData($output, $listTag, $itemTag,  $value['children'], $labelDetailTemplate, $listHtmlOptions, $itemHtmlOptions,$wrapLink,$linkHtmlOptions);
                        }

                        $itemHtmlOptions = $saveItemHtmlOptions;
                        $linkHtmlOptions = $savelinkHtmlOptions;
                    }
                }

        $output[] = CHtml::closeTag($listTag);
    }


}