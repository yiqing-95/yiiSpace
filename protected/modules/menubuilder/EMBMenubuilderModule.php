<?php
/**
 * EMBMenubuilderModule.php
 *
 * UI for managing (CRUD, arrange nestable) menus/items online.
 *
 * @author Joe Blocher <yii@myticket.at>
 * @copyright Copyright &copy; myticket it-solutions gmbh
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package menubuilder
 * @category User Interface
 * @version 1.0.1
 */

class EMBMenubuilderModule extends CWebModule
{
    /**
     * The modules theme
     * The viewPath of this module is set by adding the theme as subdirectory
     *
     * @var string
     */
    public $theme = 'blueprint'; //bootstrap


    /**
     * Set true for theme blueprint and Yiibooster v >= 1.07
     *
     * @var bool
     */
    public $publicBootstrapIconsCss = true;

    /**
     * Check if the tables are created.
     * Set to false after the first execution.
     *
     * @var bool
     */
    public $checkInstall = true;

    /**
     * Cache time in seconds
     * 0 = never expire; -1 = Cache disabled by default
     * @var int
     */
    public $cacheDuration = 0;

    /**
     * The options for the cache
     * Assign different storage properties here (cachePath,cacheTableName...) if you don't want to use the main cache
     * @var array
     */
    public $cacheOptions = array();

    /**
     * Flush the menucache after insert/update/deleting menus/items
     *
     * @var bool
     */
    public $autoFlushCache = true;

    /**
     * The supported languages
     * Property with getter and setter to allow to assign in config/main.php
     * Override the setter in MenuBuilderModule if you want to set languages dynamically in runtime
     *
     * @var array ('de','en_us',...)
     */
    protected $_languages = array();

    /**
     * The supported scenarios
     * Property with getter and setter to allow to assign in config/main.php
     * Override the setter in MenuBuilderModule if you want to set supportedScenarios dynamically in runtime
     *
     * @var array
     */
    protected $_supportedScenarios = array('backend' => 'Backend', 'frontend' => 'Frontend');

    /**
     * The rendered menus/lists in the preview
     * Assign the previewMenus in the config/main.php
     *
     * @var array
     */
    public $previewMenus = array(
        'superfish' => 'Superfish',
       // 'mbmenu' => 'MbMenu',
       // 'bootstrapnavbar' => 'Bootstrap Navbar',
       // 'bootstrapmenu' => 'Bootstrap Menu',
       // 'dropdownlist' => 'Dropdownlist',
        'unorderedlist' => 'List',
    );

    /**
     * Configure predefined templates for menuItems as data for a dropDownList
     * If empty, the templatefield in advanced menuitem input will be displayes as a textField
     * @link http://www.yiiframework.com/doc/api/1.1/CMenu#items-detail (template)
     * @link http://www.yiiframework.com/doc/api/1.1/CMenu#itemTemplate-detail
     *
     * @var array (template => description, ....)
     */
    public $itemTemplates = array();

    /**
     * The menubuilder components classes
     * Override this classes to customize for your needs and assign your class in config/main.php
     *
     * @var string
     */
    public $iconProviderClass = 'EMBBootstrapIconProvider';
    public $itemsProviderClass = 'EMBItemsProvider';
    public $dataAdapterClass = 'EMBFileAdapter';
    public $dataFilterClass = 'EMBDataFilter'; //EMBRbacAccessFilter
    public $formPermissionsClass = 'EMBFormPermissions';

    /**
     * The path to the directory with the files configured as AliasPath with .
     * Ensure this directory is writeable.
     * Default to menubuilder.data
     *
     * @var string
     */
    protected $_dataFilePath; //allow set

    /**
     * The menu/items files for installing and saving defaults inside the dataFilePath
     *
     * @var string
     */
    public $installMenuConfigFile = 'installmenus';
    public $installMenuItemsConfigFile = 'installmenuitems';
    public $defaultMenuConfigFile = 'defaultmenus'; //in the config dir
    public $defaultMenuItemsConfigFile = 'defaultmenuitems'; //in the config dir

    /**
     * The modules defaultController
     *
     * @var string
     */
    public $defaultController = 'admin';


    /**
     * Internal uses variables for memory caching
     */
    protected $_dataAdapter;
    protected $_itemsProvider = array();
    protected $_iconProvider;
    protected $_menu = array();
    protected $_menuItems = array();
    protected $_supportedLanguages;
    protected $_assetsPath;
    protected $_collapseRegistered=false;

    /**
     * Get the viewPath with the current theme as subdirectory
     *
     * @return string
     */
    public function getViewPath()
    {
        return parent::getViewPath() . DIRECTORY_SEPARATOR . $this->theme;
    }

    /**
     * Get the assets path
     * @return mixed
     */
    public function getAssetsPath()
    {
        return $this->_assetsPath;
    }

    /**
     * Publish the assets
     */
    protected function publishAssets()
    {
        $dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets';
        $this->_assetsPath = Yii::app()->getAssetManager()->publish($dir);
    }

    /**
     * Register the css for the bootstrap icons
     */
    public function registerBootstrapIconsCss()
    {
        if ($this->publicBootstrapIconsCss)
        {
            $assets = $this->getAssetsPath();
            Yii::app()->getClientScript()->registerCssFile($assets . '/icons-bootstrap.css');
        }
    }


    /**
     * Register the cookie script for collapse in bootstrap theme
     */
    public function registerBootstrapCollapseCookie($targetId,$show=false)
    {
       if(!$this->_collapseRegistered)
       {
           $assets = $this->getAssetsPath();
           Yii::app()->getClientScript()->registerScriptFile($assets . '/jquery.cookie.js');
           $this->_collapseRegistered=true;
       }

       if($show)
           $script = "jQuery('#$targetId').addClass('in');";
        else { //from cookie
            $script = <<<EOP
  jQuery('#$targetId').on('shown', function(e){jQuery.cookie('cookie_collapse', jQuery(e.target).attr('id'));});
  jQuery('#$targetId').on('hidden', function(e){jQuery.removeCookie('cookie_collapse')});
  var lastCollapse = jQuery.cookie('cookie_collapse');
  if (lastCollapse=='$targetId') {jQuery('#' + lastCollapse).addClass('in');}
EOP;
        }
       Yii::app()->getClientScript()->registerScript('embcollapse-cookie#'.$targetId, $script);
    }

    /**
     * Get an instance of the configured iconProvider
     * Register the iconProviders clientscripts
     *
     * @return mixed
     */
    public function getIconProvider()
    {
        if (!isset($this->_iconProvider))
        {
            $class = $this->iconProviderClass;
            $this->_iconProvider = new $class;
            $this->_iconProvider->registerClientScript();
        }

        return $this->_iconProvider;
    }

    /**
     * Check if a cache is installed and create a cache of the same class named 'embCache'
     * Import the components an publish the assets
     */
    public function init()
    {
        parent::init();

        if ($this->cacheDuration >= 0 && Yii::app()->hasComponent('cache'))
        {
            $cacheClass = get_class(Yii::app()->components['cache']);
            $cache = new $cacheClass;

            foreach($this->cacheOptions as $key=>$value)
                $cache->$key = $value;

            Yii::app()->setComponent('embCache', $cache);
        }

        $this->setImport(array(
            'ext.menubuilder.models.*',
            'ext.menubuilder.components.*',
            'ext.menubuilder.behaviors.*',
            'ext.menubuilder.widgets.*',
            'ext.menubuilder.extensions.ArrayModel',
            'ext.menubuilder.extensions.EUserFlash',
        ));

        $this->publishAssets();
    }

    /**
     * Get an instance of this module
     *
     * @return CModule
     */
    public static function getInstance()
    {
        return Yii::app()->getModule('menubuilder');
    }


    /**
     * Check if the cache is installed
     *
     * @return bool
     */
    public function isCacheInstalled()
    {
        return Yii::app()->hasComponent('embCache');
    }

    /**
     * Check if a cache is installed an cacheDuration >= 0
     * Disable the cache by setting cacheDuration=-1;
     * Override this method if you want to enable/disabled the cache on runtime
     *
     * @return bool
     */
    public function getCacheEnabled()
    {
        return $this->cacheDuration >= 0 && $this->isCacheInstalled();
    }


    /**
     * Flush the menubuilder cache
     *
     * @return
     */
    public function flushCache($enforce=false)
    {
        if (Yii::app()->hasComponent('embCache'))
        {
            if($enforce || $this->autoFlushCache)
               Yii::app()->embCache->flush();
        }
    }

    /**
     * Setter for the path to the data files
     * Assign as aliasPath
     *
     * @param $dataFilePath
     */
    public function setDataFilePath($dataFilePath)
    {
        $this->_dataFilePath = $dataFilePath;
    }

    /**
     * Getter for the path to the data files: menubuilder.data
     *
     * @return string
     */
    public function getDataFilePath()
    {
        if (!isset($this->_dataFilePath))
            $this->_dataFilePath = $this->name . '.data';

        return $this->_dataFilePath;
    }

    /**
     * Setter for the languages
     *
     * @param $languages
     */
    public function setLanguages($languages)
    {
        $this->_languages = $languages;
    }

    /**
     * Get the configured languages
     * Always move the current Yii::app()->language to the first position
     *
     * @return array
     */
    public function getLanguages()
    {
        if (!isset($this->_supportedLanguages))
        {
            //current language always first
            $curLanguage = Yii::app()->language;
            $languages = $this->_languages;
            $key = array_search($curLanguage, $languages);
            if ($key !== false)
                unset($languages[$key]);
            array_unshift($languages, $curLanguage);

            $this->_supportedLanguages = $languages;
        }

        return $this->_supportedLanguages;
    }

    /**
     * Get a dropdownList with the configured languages
     *
     * @param $name
     * @param $select
     * @param array $htmlOptions
     * @return null|string
     */
    public function getLanguagesDropDownList($name, $select, $htmlOptions = array())
    {
        $languages = $this->getLanguages();
        if (count($languages) > 1)
        {
            $data = array();
            foreach ($languages as $language)
                $data[$language] = $language;

            return CHtml::dropDownList($name, $select, $data, $htmlOptions);
        } else
            return null;
    }

    /**
     * Get the supported user roles from the configured dataFilterClass
     *
     * @return array
     */
    public function getSupportedUserRoles()
    {
        $class = $this->dataFilterClass;
        return $class::getSupportedUserRoles();
    }

    /**
     * Setter for the supportedScenarios
     *
     * @param $supportedScenarios
     */
    public function setSupportedScenarios($supportedScenarios)
    {
        $this->_supportedScenarios = $supportedScenarios;
    }

    /**
     * Get the supported scenarios
     * Override the method to return dynamically scenarios on runtime
     *
     * @return array
     */
    public function getSupportedScenarios()
    {
        return $this->_supportedScenarios;
    }

    /**
     * Intenal used to generate different caches for different userroles and itemProviders
     *
     * @param $menuIds
     * @param $itemProviderOptions
     * @param $scenarios
     * @param $userRoles
     * @return string
     */
    public function getCacheId($prefix, $menuIds, $itemProviderOptions)
    {
        if (!isset($itemProviderOptions['userRoles']))
        {
            $dataFilterClass = new $this->dataFilterClass;
            $itemProviderOptions['userRoles'] = $dataFilterClass::getCurrentUserRoles();
        }


        if (is_array($menuIds))
            $menuIds = md5(serialize($menuIds));

        return 'emb_menu_' . md5($prefix . $menuIds .  md5(serialize($itemProviderOptions)));
    }

    /**
     * Create an itemsProvider
     * Don't use this method, it's only used internally in EMBAdminController::actionIndex
     * Use getItemsProviderData instead (@see the widgets: EMBMenu, EMBList, EMBDropDownList)
     *
     * @param null $menuId
     * @param array $options
     * @param bool $adminMode
     * @param null $menu
     * @return mixed
     */
    public function getItemsProvider(&$menuId = null, $options=array(), $adminMode=false, &$menu = null)
    {
        if(!isset($options['language']))
            $options['language'] = Yii::app()->language;

        $enforceNestedConfig = isset($options['enforceNestedConfig']) ? $options['enforceNestedConfig'] : true;
        $scenarios = isset($options['scenarios']) ? $options['scenarios'] : null;
        $userRoles = isset($options['userRoles']) ? $options['userRoles'] : null;
        $ajaxOptions = isset($options['ajaxOptions']) ? $options['ajaxOptions'] : array();
        $useAjaxOnClick = isset($options['useAjaxOnClick']) ? $options['useAjaxOnClick'] : false;

        $dataAdapter = $this->getDataAdapter();
        if (empty($menuId))
            $menuId = $dataAdapter->getDefaultMenuId($adminMode);

        $cacheId = $this->getCacheId((string)$adminMode, $menuId, $options);

        //with access check
        $menu = $dataAdapter->loadMenu($menuId, $adminMode, $scenarios, $userRoles);

        if (!isset($this->_itemsProvider[$cacheId]))
        {

            $menuVisible = !empty($menu) && ($menu->visible || $adminMode);
            $nestedConfig = $menuVisible ? $menu->nestedconfig : '';

            //with access check
            $items = $menuVisible ? $dataAdapter->loadMenuItems($menuId, $adminMode, $scenarios, $userRoles) : null;
            if (empty($items))
                $items = array();

            //create the itemsProvider
            $itemsProviderClass = $this->itemsProviderClass;
            $itemsProvider = new $itemsProviderClass;
            $itemsProvider->nestedConfig = $nestedConfig;
            $itemsProvider->enforceNestedConfig = $enforceNestedConfig;
            $itemsProvider->language = $options['language'];
            $itemsProvider->ajaxOptions = $ajaxOptions;
            $itemsProvider->useAjaxOnClick = $useAjaxOnClick;
            $itemsProvider->items = $items;

            $this->_itemsProvider[$cacheId] = $itemsProvider;
        }

        $this->_itemsProvider[$cacheId]->iconProviderClass = $this->iconProviderClass;

        return $this->_itemsProvider[$cacheId];
    }

    /**
     * Get the dataAdapter as object of the configured dataAdapterClass
     *
     * @return mixed
     */
    public function getDataAdapter()
    {
        if (!isset($this->_dataAdapter))
        {
            $class = $this->dataAdapterClass;
            $this->_dataAdapter = new $class;
        }

        return $this->_dataAdapter;
    }

    /**
     * Get the i8n menu title with the icon
     *
     * @param $menu
     * @param null $language
     * @param bool $adminMode
     * @param bool $addIcon
     * @return string
     */
    public function getMenuTitle($menu, $language = null, $adminMode = false,$addIcon=true)
    {
        if (empty($menu))
            return '';

        if(empty($language))
            $language = Yii::app()->language;

        $title = $menu->getI8NAttribute('titles',$language);

        //add roles/scenarios info
        if ($adminMode)
            $this->appendScenarioRolesInfo($menu, $title);

        if($addIcon && !empty($menu->icon) && !empty($this->iconProviderClass))
        {
           $iconProviderClass = $this->iconProviderClass;
           $title = $iconProviderClass::getIconLabel($menu->icon,$title);
        }

        return $title;
    }

    /**
     * Append information about the assigned userroles and scenarios in the admin form
     *
     * @param $item
     * @param $title
     */
    public static function appendScenarioRolesInfo($item, &$title)
    {
        $info = '';
        $roles = !empty($item->userroles) ? $item->userroles : '-';
        $info .= ' R: ' . $roles;

        $scenarios = !empty($item->scenarios) ? $item->scenarios : '-';
        $info .= ' S: ' . $scenarios;

        if (!empty($info))
            $title .= '<span class="emb-rsinfo">' . $info . '</span>';
    }

    /**
     * Load items from a file
     * The file must return an array
     *
     * @param $fileName
     * @return array|mixed
     */
    public function itemsFromFile($fileName)
    {
        return is_file($fileName) ? include($fileName) : array();
    }

    /**
     * Get the path of the data directory menubuilder.data
     *
     * @return string
     */
    public function getDataPath()
    {
        return Yii::getPathOfAlias($this->name . '.data');
    }

    /**
     * The full path a config file
     * If the name doesn't contain a '.', the path is set to menubuilder.data
     *
     * @param string $name
     * @return string
     */
    public function getConfigFile($name)
    {
        $fileAlias = strpos($name, '.') === false
            ? $this->name . '.data.' . $name
            : $name;

        return Yii::getPathOfAlias($fileAlias) . '.php';
    }

    /**
     * The full path to the install menuitems config file
     *
     * @param string $prefix
     * @return string
     */
    public function getInstallMenuItemsConfigFile()
    {
        return $this->getConfigFile($this->installMenuItemsConfigFile);
    }

    /**
     * The full path to the install menus config file
     *
     * @param string $prefix
     * @return string
     */
    public function getInstallMenusConfigFile()
    {
        return $this->getConfigFile($this->installMenuConfigFile);
    }

    /**
     * The full path to the default menuitems config file
     * Different prefixes are used by different dataAdapters
     *
     * @param string $prefix
     * @return string
     */
    public function getDefaultMenuItemsConfigFile($prefix='')
    {
        return $this->getConfigFile($prefix.$this->defaultMenuItemsConfigFile);
    }

    /**
     * The full path to the default menus config file
     * Different prefixes are used by different dataAdapters
     *
     * @param string $prefix
     * @return string
     */
    public function getDefaultMenusConfigFile($prefix='')
    {
        return $this->getConfigFile($prefix.$this->defaultMenuConfigFile);
    }

    /**
     * Creates an itemProvider and calls a itemProvider method to get the items/output
     * @see the widgets: EMBMenu::getItems, EMBList::getItems, EMBDropDownList::getItems
     *
     * @param $method
     * @param array $methodParams
     * @param null $menuIds
     * @param null $mergeAsRootIds
     * @param null $callbacks
     * @param array $itemProviderOptions
     * @return array|string
     * @throws CException
     */
    public function getItemsProviderData($method, $methodParams = array(), $menuIds = null, $mergeAsRootIds = null, $callbacks=null,$itemProviderOptions = array())
    {
        $additionalOptions = array('nestedConfig',
            'onVisible', 'onActive',
            'onLabel', 'onUrl');

        $adminMode = isset($itemProviderOptions['adminMode']) ? $itemProviderOptions['adminMode'] : false;
        $isMenu = $method == 'getCMenuItems';
        $isList = $method == 'getListData';
        $isDropDownList = $method == 'getDropDownListData';

        if(!isset($itemProviderOptions['scenarios']))
            $itemProviderOptions['scenarios']=null;
        if(!isset($itemProviderOptions['userRoles']))
            $itemProviderOptions['userRoles']=null;
        if(!isset($itemProviderOptions['language']))
            $itemProviderOptions['language']=Yii::app()->language;

        if(!isset($menuIds))
        {
            $menuIds = array();
            $menus = $this->getDataAdapter()->loadMenus($adminMode, $itemProviderOptions['scenarios'], $itemProviderOptions['userRoles']);
            if(is_array($menus))
              foreach($menus as $menu)
                $menuIds[]=$menu->menuid;
        }
        else
        if (is_string($menuIds))
            $menuIds = array($menuIds);


        $items = array();
        foreach ($menuIds as $menuId)
        {
            $menu = null;
            $itemsProvider = $this->getItemsProvider($menuId, $itemProviderOptions, $adminMode, $menu);

            foreach ($itemProviderOptions as $key => $option)
                if (in_array($key, $additionalOptions))
                    $itemsProvider->$key = $option;

            if (method_exists($itemsProvider, $method))
            {
                //add the menuid as method param if menuIdToKey=true
                if($isDropDownList)
                {
                    $menuIdToKey=$methodParams['menuIdToKey'];
                    if($menuIdToKey)
                        $methodParams['menuId']=$menu->menuid;

                    unset($methodParams['menuIdToKey']);
                }

                $result = call_user_func_array(array($itemsProvider, $method), $methodParams);
            }
            else
                throw new CException('Invalid itemsProvider method: ' . $method);

            if(isset($callbacks['onMenuItems']))
                $result = call_user_func($callbacks['onMenuItems'],$menu,$result,$itemProviderOptions['language']);

            if(!empty($result))
            {
                //-------- merge menu items
                if($isMenu) //result = array()
                {
                    if(is_array($mergeAsRootIds) && (array_key_exists($menuId,$mergeAsRootIds) || in_array($menuId,$mergeAsRootIds)) && isset($callbacks['onGetRootItem']))
                    {
                        $rootItem = call_user_func($callbacks['onGetRootItem'],$menu,$result,$itemProviderOptions['language']);
                        if(!empty($rootItem))
                            $items[]=$rootItem;
                        else
                            $items = array_merge($items, $result);
                    }
                    else
                        $items = array_merge($items, $result);
                }
                else
                if($isList)
                    $items[] = $result;
                else
                    $items = array_merge($items, $result);
            }
        }

        if($isList)
            $items = implode(PHP_EOL, $items);

        return $items;
    }

    /**
     * Get a dropDownList with all Menus
     * Visiblity of a menu is checked by user roles (and scenarios if assigned)
     * The menu title will be translated to the current language
     *
     * @param string $name
     * @param string $select
     * @param array $htmlOptions
     * @param bool $adminMode
     * @param null $scenarios
     * @param null $userRoles
     * @param null $language
     * @return string
     */
    public function getMenusDropDownList($name = 'menuId', $select = '', $htmlOptions = array(), $adminMode = false, $scenarios = null, $userRoles = null , $language  = null)
    {
        $data = array();
        $dataAdapter = $this->getDataAdapter();
        $menus = $dataAdapter->loadMenus($adminMode, $scenarios, $userRoles);
        if ($adminMode)$menus = $dataAdapter->loadMenus($adminMode, $scenarios, $userRoles);
            $name = 'menuId';

        if (!empty($menus))
        {
            if ($adminMode)
                $htmlOptions['onchange'] = "$('#" . EMBConst::HIDDENNAME_NESTEDCONFIG . "').remove();$('#" . EMBConst::FORMID . "').submit();";
            $options = array();
            foreach ($menus as $menu)
            {
                $menuId = $menu->menuid;
                $label = $this->getMenuTitle($menu, $language, false, false);

                $data[$menu->menuid] = $label;
            }

            $htmlOptions['options'] = $options;
        }

        return CHtml::dropDownList($name, $select, $data, $htmlOptions);
    }
}