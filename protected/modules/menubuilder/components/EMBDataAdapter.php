<?php
/**
 * EMBDataAdapter.php
 *
 * The base class for the dataAdapters
 *
 * @author Joe Blocher <yii@myticket.at>
 * @copyright Copyright &copy; myticket it-solutions gmbh
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package menubuilder
 * @category User Interface
 * @version 1.0
 */
abstract class EMBDataAdapter
{
    /**
     * The class of a menu model
     *
     * @var string
     */
    public $menuClass;

    /**
     * The class of a menu item model
     *
     * @var string
     */
    public $menuItemClass;

    /**
     * Internal variables for memory caching
     */
    protected $_menu=array();
    protected $_menuItem=array();
    protected $_menuItems=array();
    protected $_menus=array();

    /**
     * These methods must be implemented in inherited dataAdapters
     */
    abstract protected function findAllMenuItems($menuId);
    abstract protected function findAllMenus($scenarios=null);
    abstract protected function findMenu($menuId);
    abstract protected function findMenuItem($itemId);
    abstract public function deleteMenuItemsByMenuId($menuId);
    abstract public function installMenus($resetAll=false,&$error='',$fromDefault=false);
    abstract public function installMenuItems($resetAll=false,&$error='',$fromDefault=false);
    abstract public function saveAsDefault();
    abstract protected function exportToZip($zip);
    abstract protected function importFromZip($zip,&$error = '');

    /**
     * Append an array of items
     * Used on install, import
     *
     * @param $items
     * @param $class
     * @return int
     */
    public function appendFromArray($items,$class,&$error = '')
    {
        $imported = 0;
        $error='';
        if (!empty($items))
        {
            foreach ($items as $item)
            {
                $model = new $class;
                $model->setAttributes($item,false);

                if($model->hasAttribute('labels') && is_string($model->labels)) //maybe on import: labels assigned as string
                    $model->labels=array(Yii::app()->language=>$model->labels);

                if($model->hasAttribute('titles') && is_string($model->titles)) //maybe on import: titles assigned as string
                    $model->titles=array(Yii::app()->language=>$model->titles);

                $saved = $model->save();
                if($saved)
                    $imported++;
                else
                {
                    $error=$class.' [RecNo '.($imported+1).'] '.CHtml::errorSummary($model);
                    return $imported;
                }
            }
        }

        return $imported;
    }

    /**
     * Load a menu with the specified menuid
     *
     * @param $menuId
     * @param bool $adminMode
     * @param null $scenarios
     * @param null $userRoles
     * @param bool $loadDefault
     * @return mixed
     */
    public function loadMenu($menuId,$adminMode=false,$scenarios=null,$userRoles=null,$loadDefault=true)
    {
        $cacheId = md5('loadMenu'.$menuId.$adminMode . serialize($scenarios) . serialize($scenarios));
        if(!isset($this->_menu[$cacheId]))
        {
            if(empty($menuId) && $loadDefault)
                $menuId = $this->getDefaultMenuId($adminMode);

            $model = self::getAccessCheckedModels($this->findMenu($menuId),$adminMode,$scenarios,$userRoles,true);
            $this->_menu[$cacheId] = $model;
        }

        return $this->_menu[$cacheId];
    }

    /**
     * Load a menuitem with the specified itemid
     *
     * @param $itemId
     * @param bool $adminMode
     * @param null $scenarios
     * @param null $userRoles
     * @param bool $skipCheckAccess
     * @return mixed
     */
    public function loadMenuItem($itemId,$adminMode=false,$scenarios=null,$userRoles=null,$skipCheckAccess=false)
    {
        $cacheId = md5('loadMenuItem'.$itemId.$adminMode . serialize($scenarios) . serialize($scenarios));

        if(!isset($this->_menuItem[$cacheId]))
        {
            $item = $this->findMenuItem($itemId);
            if($skipCheckAccess)
                $this->_menuItem[$cacheId] = $item;
            else
                $this->_menuItem[$cacheId] =  self::getAccessCheckedModels($item,$adminMode,$scenarios,$userRoles,false);
        }

        return $this->_menuItem[$cacheId];
    }

    /**
     * Load items for a menu
     * Items with no menuid assigned will be loaded too.
     *
     * @param $menuId
     * @param bool $adminMode
     * @param null $scenarios
     * @param null $userRoles
     * @param bool $skipCheckAccess
     * @return mixed
     */
    public function loadMenuItems($menuId,$adminMode=false,$scenarios=null,$userRoles=null,$skipCheckAccess=false)
    {
        if(empty($menuId))
            $menuId = $this->getDefaultMenuId($adminMode);

        $cacheId = md5('loadMenuItems'.$menuId.$adminMode . serialize($scenarios) . serialize($scenarios) . (string)$skipCheckAccess);

        if(!isset($this->_menuItems[$cacheId]))
        {
            $models = $this->findAllMenuItems($menuId);
            if($skipCheckAccess)
                $this->_menus[$cacheId] = $models;
            else
               $this->_menuItems[$cacheId] =  self::getAccessCheckedModels($models,$adminMode,$scenarios,$userRoles);
        }

        return $this->_menuItems[$cacheId];
    }


    /**
     * Load the available menus
     *
     * @param $adminMode
     * @param null $scenarios
     * @param null $userRoles
     * @param bool $skipCheckAccess
     * @return mixed
     */
    public function loadMenus($adminMode,$scenarios=null,$userRoles=null,$skipCheckAccess=false)
    {
        $cacheId = md5('loadMenus'.$adminMode . serialize($scenarios) . serialize($userRoles) . (string)$skipCheckAccess);
        if(!isset($this->_menus[$cacheId]))
        {
            $models=$this->findAllMenus($scenarios);

            if($skipCheckAccess)
                $this->_menus[$cacheId] = $models;
            else
                $this->_menus[$cacheId] =  self::getAccessCheckedModels($models,$adminMode,$scenarios,$userRoles,true);
        }
        return $this->_menus[$cacheId];
    }

    /**
     * Load the available menuid depending on adminMode, scenarios and userroles
     *
     * @param $adminMode
     * @param null $scenarios
     * @param null $userRoles
     * @return array
     */
    public function loadAllMenuIds($adminMode,$scenarios=null,$userRoles=null)
    {
        $ids=array();
        $models=self::getAccessCheckedModels($this->findAllMenus(),$adminMode,$scenarios,$userRoles,true);
        if(!empty($models))
        {
            foreach($models as $model)
                $ids[]=$model->menuid;
        }
        return $ids;
    }

    /**
     * Check if a menu with the specified menuId exists
     *
     * @param $menuId
     * @return bool
     */
    public function menuidExists($menuId)
    {
        if(empty($menuId))
            return false;

        $menu = $this->findMenu($menuId);
        return !empty($menu);
    }

    /**
     * Called in beforeDelete of a menuitem model
     *
     * @param $itemId
     */
    public function removeItemFromMenus($itemId)
    {
        $menus = $this->loadMenus(true, null, null, true);

        foreach ($menus as $menu)
        {
            $existing = EMBNestedConfigUtil::extractExistingIds($menu->nestedconfig);
            if (in_array($itemId, $existing))
            {
                $menu->nestedconfig = EMBNestedConfigUtil::removeId($menu->nestedconfig, $itemId);
                $menu->save();
            }
        }
    }

    /**
     * Remove unused nested ids
     * Maybe this happens when in a cms a page with a menuitem is deleted
     *
     * @param null $menuId
     */
    public function cleanUpNestedConfig($menuId=null)
    {
        $menus = $this->loadMenus(true,null,null,true);
        if(!empty($menus))
        {
            $nestedItems = array();
            $allItems = array();
            //get all nested and all menuitemids in every menu
            foreach($menus as $menu)
            {
                if(isset($menuId) && $menuId != $menu->menuid)
                    continue;

                $nestedConfig = $menu->nestedconfig;
                $nestedItems[$menu->menuid] = array_merge($nestedItems,EMBNestedConfigUtil::extractExistingIds($nestedConfig));

                $menuItems = $this->loadMenuItems($menuId,true,null,null,true);
                if(!empty($menuItems))
                {
                    foreach($menuItems as $menuItem)
                      if(!in_array($menuItem->itemid,$allItems))
                        $allItems[]=$menuItem->itemid;
                }
            }

            foreach($menus as $menu)
            {
                if(isset($nestedItems[$menu->menuid]))
                {
                    $removed = false;
                    foreach($nestedItems[$menu->menuid] as $nestedItemId)
                    {
                        if(!in_array($nestedItemId,$allItems))
                        {
                            $menu->nestedconfig = EMBNestedConfigUtil::removeId($menu->nestedconfig,$nestedItemId);
                            $removed = true;
                        }
                    }

                    if($removed)
                        $menu->save();
                }
            }
        }
    }

    /**
     * Get the first found available menu
     *
     * @param $adminMode
     * @return null
     */
    public function getDefaultMenuId($adminMode)
    {
        $menus = $this->loadMenus($adminMode);
        return empty($menus) ? null : $menus[0]->menuid;
    }

    /**
     * Save the nestedConfig of a menu
     *
     * @param $menuId
     * @param $nestedConfig
     * @return bool
     */
    public function saveNestedConfig($menuId,$nestedConfig)
    {
        $model = $this->loadMenu($menuId,true);

        if(!empty($model))
        {
            $model->nestedconfig=$nestedConfig;
            return $model->save();
        }

        return false;
    }

    /**
     * Check access for menus/items
     *
     * @param $models
     * @param $adminMode
     * @param null $scenarios
     * @param null $userRoles
     * @param bool $isMenu
     * @return array|bool|null
     */
    public static function getAccessCheckedModels($models,$adminMode,$scenarios=null,$userRoles=null,$isMenu=false)
    {
        if($models == null)
            return false;

        $items = array();
        $modelsIsObject = is_object($models);
        if(!empty($models))
        {
            if($modelsIsObject)
                $models = array($models);

            $dataFilterClass=Yii::app()->getModule('menubuilder')->dataFilterClass;
            foreach($models as $model)
            {
                if($dataFilterClass::checkRoles($model,$adminMode,$userRoles,$isMenu) && $dataFilterClass::checkScenarios($model,$scenarios))
                    $items[]=$model;
            }
        }

        if(!empty($items))
            return $modelsIsObject ? $items[0] : $items;
        else
            return $modelsIsObject ? null : array();
    }

    /**
     * Create a zip backup file used on export
     *
     * @param string $name
     * @return bool|string
     */
    public function zipbackup($name='emb_backup')
    {
        $name = $name . date('_Ymd_Hi_s').'.zip';
        $zipFile = MenubuilderModule::getInstance()->getDataPath().DIRECTORY_SEPARATOR.$name;

        $zip = new ZipArchive;
        if ($zip->open($zipFile, ZipArchive::CREATE) === true)
        {
            $this->exportToZip($zip);
            return is_file($zipFile) ? $zipFile : false;
        }
        return false;
    }

    /**
     * Import menus/items from a zip archive
     *
     * @param $zipFile
     * @return bool
     */
    public function zipimport($zipFile,&$error = '')
    {
        if(!is_file($zipFile))
            return false;

        $zip = new ZipArchive;
        if ($zip->open($zipFile) === true)
        {
            $result = $this->importFromZip($zip,$error);
            $zip->close();
            return $result && empty($error);
        }
        return false;
    }
}