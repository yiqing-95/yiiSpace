<?php
/**
 * EMBBaseWidget.php
 *
 * The base class for
 *
 * @author Joe Blocher <yii@myticket.at>
 * @copyright Copyright &copy; myticket it-solutions gmbh
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package menubuilder
 * @category User Interface
 * @version 1.0
 */

abstract class EMBBaseWidget extends CWidget
{
    /**
     * Set to false to exlude this output from caching
     * @var bool
     */
    public $cached = true;

    /**
     * The template for a label output
     * @var string
     */
    public $labelDetailTemplate = '{label}';

    /**
     * Text if no valid items are found
     *
     * @var string
     */
    public $emptyText='';

    /**
     * The scenarios
     *
     * @var mixed string|array
     */
    public $scenarios;

    /**
     * Global ajax settings for every item
     * @var array
     */
    public $ajaxOptions = array();

    /**
     * Wether to assign ajax as onclick of the item or bind in document-ready
     *
     * @var bool
     */
    public $useAjaxOnClick = false;


    /**
     * Don't use this attributes
     * They are only for simulating userroles and languages
     * @see the views in the directory admin/menupreviews
     */
    public $userRoles;
    public $language;
    public $nestedConfig;
    public $adminMode=false;

    /**
     * The menuids for which the items will be rendered
     * If not set, all menuIds with the specified scenarios will be collected
     * @var string or array
     */
    public $menuIds;

    /**
     * evaluateExpression callbacks
     * @see readme.md
     * @var mixed string or array
     */
    public $onVisible;
    public $onActive;
    public $onLabel;
    public $onUrl;

    /**
     * Internal used
     */
    protected $_menuBuilder;
    protected $_itemProviderOptions;

    /**
     * @see EMBMenuItem, EMBList
     * @return mixed
     */
    abstract public function renderOutput();
    abstract public function getItems();

    /**
     * Get the menubuilder module instance
     *
     * @return CModule
     */
    public function getMenuBuilder()
    {
        if(!isset($this->_menuBuilder))
            $this->_menuBuilder = Yii::app()->getModule('menubuilder');

        return $this->_menuBuilder;
    }

    /**
     * Get the options for the itemProvider
     *
     * @return array
     */
    public function getItemProviderOptions()
    {
        if (!isset($this->_itemProviderOptions))
        {
            $this->_itemProviderOptions = array();
            $this->_itemProviderOptions['scenarios'] = $this->scenarios;
            $this->_itemProviderOptions['userRoles'] = $this->userRoles;
            $this->_itemProviderOptions['language'] = $this->language;
            $this->_itemProviderOptions['ajaxOptions'] = $this->ajaxOptions;
            $this->_itemProviderOptions['useAjaxOnClick'] = $this->useAjaxOnClick;
            $this->_itemProviderOptions['adminMode'] = $this->adminMode;

            if (isset($this->nestedConfig))
                $this->_itemProviderOptions['nestedConfig'] = $this->nestedConfig;
            if (isset($this->onVisible))
                $this->_itemProviderOptions['onVisible'] = $this->onVisible;
            if (isset($this->onActive))
                $this->_itemProviderOptions['onActive'] = $this->onActive;
            if (isset($this->onLabel))
                $this->_itemProviderOptions['onLabel'] = $this->onLabel;
            if (isset($this->onUrl))
                $this->_itemProviderOptions['onUrl'] = $this->onUrl;
        }

        return $this->_itemProviderOptions;
    }

    /**
     * Check to load items from the cache
     *
     * @param null $cachedId
     * @return bool
     */
    protected function itemsFromCache(&$cachedId = null)
    {
        if ($this->cached && $this->getMenuBuilder()->getCacheEnabled())
        {
            $cachePrefix = 'items'.md5(serialize($this));
            $cachedId = $this->getMenuBuilder()->getCacheId($cachePrefix, $this->menuIds, $this->getItemProviderOptions());
            return Yii::app()->embCache->get($cachedId);
        }

        return false;
    }

    /**
     * Save the items to the cache
     *
     * @param $cacheId
     * @param $items
     */
    protected  function itemsToCache($cacheId,$items)
    {
        if ($cacheId !== null)
        {
            Yii::app()->embCache->set($cacheId, $items, MenubuilderModule::getInstance()->cacheDuration);
        }
    }

    /**
     * Run the widget
     *
     * @throws CException
     */
    public function run()
    {
        if(!isset($this->menuIds) && !isset($this->scenarios))
            throw new CException('Attribute menuIds and/or scenarios must be set');

        ob_start();
        $this->renderOutput();
        $output = ob_get_clean();

        if(!empty($output))
            echo !empty($output) ? $output : $this->emptyText;
    }


}