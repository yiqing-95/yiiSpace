<?php
/**
 * EMBListAdminWidget.php
 *
 * This widget renders the form for arranging the menuitems.
 * Allows  the user to drag & drop the items between the left side (available items) and the right side (nested menu items)
 *
 * @author Joe Blocher <yii@myticket.at>
 * @copyright Copyright &copy; myticket it-solutions gmbh
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package menubuilder
 * @category User Interface
 * @version 1.0
 */
class EMBListAdminWidget extends EMBListWidget
{
    /**
     * The class of the form
     * Use TbActiveForm with bootstrap theme
     * @var string
     */
    public $formClass='CActiveForm';

    /**
     * The options for the form
     *
     * @var array
     */
    public $formOptions=array(
        'enableAjaxValidation'=>false,
        'enableClientValidation'=>false,
    );

    /**
     * Internal used
     * @var
     */
    protected $_nestedModels;
    protected $_availableModels;
    protected $_form;

    /**
     * Initialize the nestedModels and availableModels
     */
    public function init()
    {
        parent::init();

        $this->_nestedModels = array();
        $this->_availableModels = $this->itemsProvider->items;

        if (!empty($this->itemsProvider->nestedConfig))
            $this->initModels(CJSON::decode($this->itemsProvider->nestedConfig));

        //register the script function: nestedConfigToHidden
        $rootClass = $this->getOption('rootClass');
        $stringify = $this->useJson2 ? 'JSON.stringify' : 'window.JSON.stringify';
        $hiddenName = EMBConst::HIDDENNAME_NESTEDCONFIG;

        $function = "$('#{$hiddenName}').val($stringify($('.{$rootClass}').nestable('serialize')));";
        $script = 'function nestedConfigToHidden(){'.$function.'}';

        Yii::app()->getClientScript()->registerScript('emb_tohidden_'.$this->getId(),$script,CClientScript::POS_END);

        $this->renderBeginForm();
    }

    /**
     * Render the form begin and add the hidden fields
     */
    protected function renderBeginForm()
    {
        $this->formOptions['htmlOptions']['id']=EMBConst::FORMID;
        $this->formOptions['htmlOptions']['enctype']='multipart/form-data'; //because of menu import
        $this->_form = $this->controller->beginWidget($this->formClass, $this->formOptions);

        $nestedConfig = $this->itemsProvider->nestedConfig;
        echo CHtml::hiddenField(EMBConst::HIDDENNAME_NESTEDCONFIG,$nestedConfig,array('id'=>EMBConst::HIDDENNAME_NESTEDCONFIG));
        echo CHtml::hiddenField(EMBConst::HIDDENNAME_EDITITEM,'',array('id'=>EMBConst::HIDDENNAME_EDITITEM));
    }

    /**
     * Getter for the form
     *
     * @return mixed
     */
    public function getForm()
    {
        return $this->_form;
    }

    /**
     * Iterate nestedConfigArray and split all models into nestedModels and availableModels
     *
     * @param $nestedConfigArray
     */
    protected function initModels($nestedConfigArray)
    {
        foreach ($nestedConfigArray as $key => $value)
            if (isset($value['id']))
            {
                if (($model = $this->findModel($value['id'])) !== false)
                {
                    $this->_nestedModels[] = $model;

                    //remove from the available models
                    foreach($this->_availableModels as $idx=>$item)
                        if($item->itemid == $value['id'])
                        {
                            unset($this->_availableModels[$idx]);
                            break;
                        }

                    if (!empty($value['children']))
                        $this->initModels($value['children']);
                }
            }
    }

    /**
     * Render the end form
     */
    protected function renderEndForm()
    {
        $this->controller->endWidget();
    }

    /**
     * Render the availabel items as EMBListWidget at the left side
     */
    public function renderAvailableItems($options=array())
    {
        $itemsProvider = clone $this->itemsProvider;
        $itemsProvider->items = $this->_availableModels;
        $itemsProvider->nestedConfig = null; //render all (available) items as unnested at level 0
        $itemsProvider->enforceNestedConfig = false; //show all available models without nestedConfig

        $options['itemsProvider'] = $itemsProvider;
        if(!isset($options['title']))
           $options['title'] = $this->title;

        $this->widget('EMBListWidget', $options);
    }


    /**
     * Render the nestable list
     */
    public function renderMenuItems()
    {
        $this->itemsProvider->items = $this->_nestedModels;
        parent::run();
    }

    /**
     * Output form end
     */
    public function run()
    {
        $this->renderEndForm();
    }


}