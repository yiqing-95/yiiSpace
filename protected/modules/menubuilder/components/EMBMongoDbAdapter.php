<?php
/**
 * EMBMongoDbAdapter.php
 *
 * The dataadapter for the mongodb
 * Uses the AR implementation from Sammaye
 * http://www.yiiframework.com/extension/mongoyii
 * must be installed
 *
 * @author Joe Blocher <yii@myticket.at>
 * @copyright Copyright &copy; myticket it-solutions gmbh
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package menubuilder
 * @category User Interface
 * @version 1.0
 */

class EMBMongoDbAdapter extends EMBDataAdapter
{
    /**
     * The class of a menu model
     *
     * @var string
     */
    public $menuClass = 'EMBMongoDbMenu';

    /**
     * The class of a menu item model
     *
     * @var string
     */
    public $menuItemClass = 'EMBMongoDbMenuItem';

    /**
     * Find all menu items with empty or the specified menuid
     *
     * @param $menuId
     * @return array
     */
    protected function findAllMenuItems($menuId)
    {
        $class = $this->menuItemClass;
        $criteria = array('$or' => array(array('menuid' => $menuId), array('menuid' => ''), array('menuid' => null)));
        $cursor = $class::model()->find($criteria);
        return iterator_to_array($cursor, false);
    }

    /**
     * Find all menus for the specified scenarios
     *
     * @param $scenarios
     * @return array
     */
    protected function findAllMenus($scenarios = null)
    {
        $criteria = array();
        if (isset($scenarios))
        {
            if (is_string($scenarios))
                $scenarios = array($scenarios);

            $count = count($scenarios);
            if($count==1)
                $criteria = array('scenarios' => new MongoRegex('/' . $scenarios[0] . '/'));
            else
            {
                $parts = array();
                for ($i = 0; $i < $count; $i++)
                    $parts[] = array('scenarios' => new MongoRegex('/' . $scenarios[$i] . '/'));

                $criteria['$or'] = $parts;
            }
        } else
            $criteria = array();


        $class = $this->menuClass;
        $cursor = $class::model()->find($criteria)->sort(array('sortposition' => 1));
        return iterator_to_array($cursor, false);
    }

    /**
     * Find the menu with the specified menuId
     *
     * @param $menuId
     * @return mixed
     */
    protected function findMenu($menuId)
    {
        if (empty($menuId))
            return null;

        $class = $this->menuClass;
        return $class::model()->findOne(array('menuid' => $menuId));
    }

    /**
     * Find the menuitem with the specified itemId
     *
     * @param $itemId
     * @return mixed
     */
    protected function findMenuItem($itemId)
    {
        if (empty($itemId))
            return null;

        $class = $this->menuItemClass;
        return $class::model()->findOne(array('itemid' => $itemId));
    }

    /**
     * Delete all menuitems with the specified menuId assigned
     * Called after a menu is deleted
     *
     * @param $menuId
     * @return mixed
     */
    public function deleteMenuItemsByMenuId($menuId)
    {
        $class = $this->menuItemClass;
        return $class::model()->deleteAll(array('menuid' => $menuId));
    }

    /**
     * Check if a records exists in a collection
     *
     * @param $class
     * @return bool
     */
    public function recordExists($class)
    {
        $cursor = $class::model()->getCollection()->findOne();
        return empty($cursor) ? false : true;
    }


    /**
     * Install the menus from data.installmenus php file
     *
     * @param bool $resetAll
     * @param string $error
     * @param bool $fromDefault
     * @return int
     */
    public function installMenus($resetAll = false, &$error = '', $fromDefault = false)
    {
        $imported = 0;
        $menuBuilder = MenubuilderModule::getInstance();
        $class = $this->menuClass;

        if (!$menuBuilder->checkInstall && !$resetAll)
            return false;

        if ($this->recordExists($class) && !$resetAll)
            return false;

        if ($resetAll)
            $class::model()->getCollection()->remove();

        if ($fromDefault)
        {
            $installFile = $menuBuilder->getDefaultMenusConfigFile('mdb_');
            if (!is_file($installFile))
                $installFile = $menuBuilder->getInstallMenusConfigFile();
        } else
            $installFile = $menuBuilder->getInstallMenusConfigFile();

        $menus = $menuBuilder->itemsFromFile($installFile);
        $imported = $this->appendFromArray($menus,$class,$error);

        return $imported;
    }

    /**
     * Install the menuitems from data.installmenuitems php file
     *
     * @param bool $resetAll
     * @param string $error
     * @param bool $fromDefault
     * @return int
     */
    public function installMenuItems($resetAll = false, &$error = '', $fromDefault = false)
    {
        $imported = 0;
        $menuBuilder = MenubuilderModule::getInstance();
        $class = $this->menuItemClass;

        if (!$menuBuilder->checkInstall && !$resetAll)
            return false;

        if ($this->recordExists($class) && !$resetAll)
            return false;

        if ($resetAll)
            $class::model()->getCollection()->remove();

        if ($fromDefault)
        {
            $installFile = $menuBuilder->getDefaultMenuItemsConfigFile('mdb_');
            if (!is_file($installFile))
                $installFile = $menuBuilder->getInstallMenuItemsConfigFile();
        } else
            $installFile = $menuBuilder->getInstallMenuItemsConfigFile();

        $menuItems = $menuBuilder->itemsFromFile($installFile);
        $imported = $this->appendFromArray($menuItems,$class,$error);

        return $imported;
    }


    /**
     * Load data from the db: menus or menuitems
     *
     * @param $class
     * @return array
     */
    protected function getDataArray($class)
    {
        $cursor = $class::model()->getCollection()->find();
        return !empty($cursor) ? iterator_to_array($cursor, false) : array();
    }

    /**
     * Save db-data to a file
     *
     * @param $menuFileName
     * @param $menuItemFileName
     * @return bool
     */
    public function saveToFile($menuFileName, $menuItemFileName)
    {
        $menuBuilder = MenubuilderModule::getInstance();
        $menuFile = $menuBuilder->getDataPath() . DIRECTORY_SEPARATOR . $menuFileName . '.php';
        $menuItemFile = $menuBuilder->getDataPath() . DIRECTORY_SEPARATOR . $menuItemFileName . '.php';

        $menus = $this->getDataArray($this->menuClass);
        if (is_file($menuFile))
            unlink($menuFile);
        file_put_contents($menuFile, '<?php return array(); ?>');

        foreach ($menus as $attributes)
        {
            $item = new EMBFileMenu();
            $item->setFilename($menuFileName);
            $item->setAttributes($attributes, false);
            $item->insert();
        }

        $menuItems = $this->getDataArray($this->menuItemClass);
        if (is_file($menuItemFile))
            unlink($menuItemFile);
        file_put_contents($menuItemFile, '<?php return array(); ?>');

        foreach ($menuItems as $attributes)
        {
            $item = new EMBFileMenuItem();
            $item->setFilename($menuItemFileName);
            $item->setAttributes($attributes, false);
            $item->insert();
        }

        return true;
    }

    /**
     * Save all menus/items as the default file
     *
     * @return bool
     */
    public function saveAsDefault()
    {
        $menuBuilder = MenubuilderModule::getInstance();
        return $this->saveToFile('mdb_' . $menuBuilder->defaultMenuConfigFile, 'mdb_' . $menuBuilder->defaultMenuItemsConfigFile);
    }

    /**
     * Export to a zip archive
     *
     * @param $zip
     */
    protected function exportToZip($zip)
    {
        $menuFileName = 'exp_mdbmenus';
        $menuItemFileName = 'exp_mdbmenuitems';

        if ($this->saveToFile($menuFileName, $menuItemFileName))
        {
            $menuBuilder = MenubuilderModule::getInstance();
            $menuFile = $menuBuilder->getDataPath() . DIRECTORY_SEPARATOR . $menuFileName . '.php';
            $menuItemFile = $menuBuilder->getDataPath() . DIRECTORY_SEPARATOR . $menuItemFileName . '.php';
            $zip->addFile($menuFile, basename($menuFile));
            $zip->addFile($menuItemFile, basename($menuItemFile));
            $ok = $zip->close();
            if ($ok)
            {
                // unlink($menuFile);
                // unlink($menuItemFile);
            }
        }
    }

    /**
     * Import from a zip archive
     *
     * @param $zip
     * @return bool|int
     */
    public function importFromZip($zip,&$error = '')
    {
        if ($zip->locateName('exp_mdbmenus.php', ZIPARCHIVE::FL_NODIR) !== false && $zip->locateName('exp_mdbmenuitems.php', ZIPARCHIVE::FL_NODIR) !== false)
        {
            $result = $zip->extractTo(MenubuilderModule::getInstance()->getDataPath());
            if ($result)
            {
                $menuBuilder = MenubuilderModule::getInstance();
                $class = $this->menuClass;
                $class::model()->getCollection()->remove();
                $file = $menuBuilder->getDataPath() . DIRECTORY_SEPARATOR . 'exp_mdbmenus.php';
                $items = $menuBuilder->itemsFromFile($file);
                $this->appendFromArray($items, $this->menuClass,$error);
                if(empty($error))
                {
                    $class = $this->menuItemClass;
                    $class::model()->getCollection()->remove();
                    $file = $menuBuilder->getDataPath() . DIRECTORY_SEPARATOR . 'exp_mdbmenuitems.php';
                    $items = $menuBuilder->itemsFromFile($file);
                    $this->appendFromArray($items, $this->menuItemClass,$error);
                    return empty($error);
                }
            } else
                return $result;
        } else
            return false;
    }

}