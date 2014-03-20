<?php
/**
 * EMBDbAdapter.php
 *
 * The dataadapter for the MySql database
 *
 * @author Joe Blocher <yii@myticket.at>
 * @copyright Copyright &copy; myticket it-solutions gmbh
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package menubuilder
 * @category User Interface
 * @version 1.0
 */
class EMBDbAdapter extends EMBDataAdapter
{
    /**
     * The class of a menu model
     *
     * @var string
     */
    public $menuClass = 'EMBDbMenu';

    /**
     * The class of a menu item model
     *
     * @var string
     */
    public $menuItemClass = 'EMBDbMenuItem';

    /**
     * Internal used
     * @var array
     */
    protected $_languages;

    /**
     * Find all menu items with empty or the specified menuid
     *
     * @param $menuId
     * @return array
     */
    protected function findAllMenuItems($menuId)
    {
        $class = $this->menuItemClass;
        return $class::model()->findAll("menuid=NULL OR menuid='' OR menuid=:menuId", array(':menuId' => $menuId));
    }

    /**
     * Find all menus for the specified scenarios
     *
     * @param $scenarios
     * @return array
     */
    protected function findAllMenus($scenarios = null)
    {
        if (isset($scenarios))
        {
            $condition = '';
            if (is_string($scenarios))
                $scenarios = array($scenarios);

            $count = count($scenarios);

            for ($i = 0; $i < $count; $i++)
            {
                $scenario = $scenarios[$i];
                $condition .= "(scenarios LIKE '%$scenario%') OR ";
            }
            $condition = substr($condition, 0, -4);

            $criteria = array('condition'=>$condition,'order'=>'sortposition');
        } else
            $criteria = array('order'=>'sortposition');

        $class = $this->menuClass;
        return $class::model()->findAll($criteria);
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
        return $class::model()->find('menuid=:menuId', array(':menuId' => $menuId));
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
        return $class::model()->find('itemid=:itemId', array(':itemId' => $itemId));
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
        return $class::model()->deleteAll('menuid=:menuId', array(':menuId' => $menuId));
    }

    /**
     * Reset the languages variable
     */
    public function resetLanguages()
    {
        $this->_languages = null;
    }

    /**
     * Load all records from the emb_language table
     * and initialize the languages array
     *
     * @return array
     */
    protected function getAllLanguages()
    {
        if (!isset($this->_languages))
        {
            $sql = 'SELECT * FROM emb_language';
            $rows = Yii::app()->db->createCommand($sql)->queryAll();

            $menuLanguages = array();
            $menuitemLanguages = array();
            foreach ($rows as $row)
            {
                if (!empty($row['itemid']))
                {
                    if (!isset($menuitemLanguages[$row['itemid']]))
                        $menuitemLanguages[$row['itemid']] = array(EMBConst::DB_LANGUAGETYPE_MENUITEMLABEL => array(), EMBConst::DB_LANGUAGETYPE_MENUITEMDESCRIPTION => array());

                    $menuitemLanguages[$row['itemid']][$row['type']][$row['language']] = $row['text'];
                } else
                    if (!empty($row['menuid']))
                    {
                        if (!isset($menuLanguages[$row['menuid']]))
                            $menuLanguages[$row['menuid']] = array(EMBConst::DB_LANGUAGETYPE_MENUTITLE => array(), EMBConst::DB_LANGUAGETYPE_MENUDESCRIPTION => array());

                        $menuLanguages[$row['menuid']][$row['type']][$row['language']] = $row['text'];
                    }
            }

            $this->_languages = array('menu' => $menuLanguages, 'menuitem' => $menuitemLanguages);

        }

        return $this->_languages;
    }

    /**
     * Get an item text the languages array
     *
     * @param $context
     * @param $id
     * @param $type
     * @return array
     */
    public function getLanguageAttributes($context, $id, $type)
    {
        $languages = $this->getAllLanguages();
        if (isset($languages[$context])
            && isset($languages[$context][$id])
            && isset($languages[$context][$id][$type])
        )
            return $languages[$context][$id][$type];
        else
            return array();
    }

    /**
     * Install the menus from data.installmenus file
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

        if (!$menuBuilder->checkInstall && !$resetAll)
            return false;

        if (!$this->checkCreateMenuTable() && !$resetAll)
            return false;

        if ($resetAll)
            $this->emptyTableMenu();

        $class = $this->menuClass;

        if ($fromDefault)
        {
            $installFile = $menuBuilder->getDefaultMenusConfigFile('db_');
            if (!is_file($installFile))
                $installFile = $menuBuilder->getInstallMenusConfigFile();
        } else
            $installFile = $menuBuilder->getInstallMenusConfigFile();

        $menus = $menuBuilder->itemsFromFile($installFile);
        $imported = $this->appendFromArray($menus, $class,$error);

        return $imported;
    }

    /**
     * Install the menuitems from data.installmenuitems file
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

        if (!$menuBuilder->checkInstall && !$resetAll)
            return false;

        if (!$this->checkCreateMenuItemsTable() && !$resetAll)
            return false;

        if ($resetAll)
            $this->emptyTableMenuItem();

        $class = $this->menuItemClass;

        if ($fromDefault)
        {
            $installFile = $menuBuilder->getDefaultMenuItemsConfigFile('db_');
            if (!is_file($installFile))
                $installFile = $menuBuilder->getInstallMenuItemsConfigFile();
        } else
            $installFile = $menuBuilder->getInstallMenuItemsConfigFile();

        $menuItems = $menuBuilder->itemsFromFile($installFile);
        $imported = $this->appendFromArray($menuItems,$class,$error);

        return $imported;
    }

    /**
     * Overrides the method of the base class
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
        if (!empty($items))
        {
            foreach ($items as $item)
            {
                $model = new $class;
                $model->setAttributes($item, false);

                if ($model instanceof EMBDbMenu)
                {
                    if (is_string($item['titles']))
                        $model->titles = array(Yii::app()->language => $item['titles']);
                    else
                        $model->titles = $item['titles'];

                    if(!empty($item['descriptions']))
                    {
                        if (is_string($item['descriptions']))
                            $model->descriptions = array(Yii::app()->language => $item['descriptions']);
                        else
                            $model->descriptions = $item['descriptions'];
                    }
                } else
                    if ($model instanceof EMBDbMenuItem)
                    {
                        if (is_string($item['labels']))
                            $model->labels = array(Yii::app()->language => $item['labels']);
                        else
                            $model->labels = $item['labels'];

                        if(!empty($item['descriptions']))
                        {
                            if (is_string($item['descriptions']))
                                $model->descriptions = array(Yii::app()->language => $item['descriptions']);
                            else
                                $model->descriptions = $item['descriptions'];
                        }

                    }

                $saved = $model->save();
                if ($saved)
                    $imported++;
                else
                {
                    $error = $class . ' [RecNo ' . ($imported + 1) . '] ' . CHtml::errorSummary($model);
                    return $imported;
                }
            }
        }

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
        $models = $class::model()->findAll();
        $items = array();
        foreach ($models as $model)
        {
            $attributes = $model->getAttributes();
            $attributes['descriptions'] = $model->descriptions;
            if ($model instanceof EMBDbMenuItem)
                $attributes['labels'] = $model->labels;
            else
                $attributes['titles'] = $model->titles;

            $items[] = $attributes;
        }

        return $items;
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
        return $this->saveToFile('db_' . $menuBuilder->defaultMenuConfigFile, 'db_' . $menuBuilder->defaultMenuItemsConfigFile);
    }

    /**
     * Export to a zip archive
     *
     * @param $zip
     */
    protected function exportToZip($zip)
    {
        $menuFileName = 'exp_dbmenus';
        $menuItemFileName = 'exp_dbmenuitems';

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
        if ($zip->locateName('exp_dbmenus.php', ZIPARCHIVE::FL_NODIR) !== false && $zip->locateName('exp_dbmenuitems.php', ZIPARCHIVE::FL_NODIR) !== false)
        {
            $result = $zip->extractTo(MenubuilderModule::getInstance()->getDataPath());
            if ($result)
            {
                $menuBuilder = MenubuilderModule::getInstance();
                $this->emptyTableMenu();
                $file = $menuBuilder->getDataPath() . DIRECTORY_SEPARATOR . 'exp_dbmenus.php';
                $items = $menuBuilder->itemsFromFile($file);
                $this->appendFromArray($items, $this->menuClass,$error);
                if(empty($error))
                {
                    $this->emptyTableMenuItem();
                    $file = $menuBuilder->getDataPath() . DIRECTORY_SEPARATOR . 'exp_dbmenuitems.php';
                    $items = $menuBuilder->itemsFromFile($file);
                    $this->appendFromArray($items, $this->menuItemClass,$error);
                    return empty($error);
                }
                else
                    return false;


            } else
                return $result;
        } else
            return false;
    }

    /**
     * Check if the menu and languages table exists.
     * If not, create the table in the db.
     *
     * @return bool
     */
    protected function checkCreateMenuTable()
    {
        $db = Yii::app()->db;
        preg_match("/dbname=([^;]*)/", $db->connectionString, $matches);
        $dbName = $matches[1];

        $sql = "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '" . $dbName . "' AND table_name = 'emb_menu'";
        $command = $db->createCommand($sql);
        if ($command->queryScalar() == 0)
        {
            $sql = "CREATE TABLE emb_menu (
                            menuid VARCHAR(40) NOT NULL,
                            nestedconfig VARCHAR(5000) NULL DEFAULT NULL,
                            visible TINYINT(3) UNSIGNED NOT NULL DEFAULT '1',
                            locked TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
                            sortposition TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
                            maxdepth TINYINT(3) UNSIGNED NOT NULL DEFAULT '3',
                            descriptionashint TINYINT(3) UNSIGNED NOT NULL DEFAULT '1',
                            icon VARCHAR(40) NULL DEFAULT NULL,
                            scenarios VARCHAR(1000) NULL DEFAULT NULL,
                            userroles VARCHAR(1000) NULL DEFAULT NULL,
                            adminroles VARCHAR(1000) NULL DEFAULT NULL,
                            created TIMESTAMP NULL DEFAULT NULL,
                            createduser VARCHAR(40) NULL DEFAULT NULL,
                            modified TIMESTAMP NULL DEFAULT NULL,
                            modifieduser VARCHAR(40) NULL DEFAULT NULL,
                            PRIMARY KEY (menuid)
                        )";
            $command = Yii::app()->db->createCommand($sql);
            $command->execute();

            $sql = "CREATE TABLE IF NOT EXISTS emb_language (
                        menuid VARCHAR(40) NOT NULL DEFAULT '',
                        itemid INT(10) UNSIGNED NOT NULL DEFAULT '0',
                        type TINYINT(3) UNSIGNED NOT NULL,
                        language VARCHAR(10) NOT NULL,
                        text VARCHAR(80) NOT NULL,
                        PRIMARY KEY (menuid, itemid, type, language)
                    )";
            $command = Yii::app()->db->createCommand($sql);
            $command->execute();

            return true;
        }

        return false;
    }

    /**
     * Check if the menuitems table exists.
     * If not, create the table in the db.
     *
     * @return bool
     */
    protected function checkCreateMenuItemsTable()
    {
        $db = Yii::app()->db;
        preg_match("/dbname=([^;]*)/", $db->connectionString, $matches);
        $dbName = $matches[1];

        $sql = "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '" . $dbName . "' AND table_name = 'emb_menuitem'";
        $command = $db->createCommand($sql);
        if ($command->queryScalar() == 0)
        {
            $sql = "CREATE TABLE emb_menuitem (
                                itemid INT(10) NOT NULL AUTO_INCREMENT,
                                menuid VARCHAR(40) NOT NULL,
                                url VARCHAR(300) NOT NULL,
                                descriptionashint TINYINT(3) UNSIGNED NOT NULL DEFAULT '1',
                                active TINYINT(3) UNSIGNED NOT NULL DEFAULT '1',
                                visible TINYINT(3) UNSIGNED NOT NULL DEFAULT '1',
                                itemOptions VARCHAR(300) NULL DEFAULT NULL,
                                submenuOptions VARCHAR(300) NULL DEFAULT NULL,
                                linkOptions VARCHAR(300) NULL DEFAULT NULL,
                                ajaxOptions VARCHAR(300) NULL DEFAULT NULL,
                                template VARCHAR(300) NULL DEFAULT NULL,
                                target VARCHAR(40) NULL DEFAULT NULL,
                                icon VARCHAR(40) NULL DEFAULT NULL,
                                scenarios VARCHAR(1000) NULL DEFAULT NULL,
                                userroles VARCHAR(1000) NULL DEFAULT NULL,
                                createduser VARCHAR(40) NULL DEFAULT NULL,
                                modifieduser VARCHAR(40) NULL DEFAULT NULL,
                                created TIMESTAMP NULL DEFAULT NULL,
                                modified TIMESTAMP NULL DEFAULT NULL,
                                PRIMARY KEY (itemid)
                            )";
            $command = Yii::app()->db->createCommand($sql);
            $command->execute();

            return true;
        }

        return false;
    }

    /**
     * Delete all records from menu and languages table
     */
    protected function emptyTableMenu()
    {
        $sql = 'DELETE FROM emb_menu';
        Yii::app()->db->createCommand($sql)->execute();

        $sql = 'DELETE FROM emb_language WHERE type=' . EMBConst::DB_LANGUAGETYPE_MENUTITLE . ' OR type=' . EMBConst::DB_LANGUAGETYPE_MENUDESCRIPTION;
        Yii::app()->db->createCommand($sql)->execute();
    }

    /**
     * Delete all records from menuitem table
     */
    protected function emptyTableMenuItem()
    {
        $sql = 'DELETE FROM emb_menuitem';
        Yii::app()->db->createCommand($sql)->execute();

        $sql = 'DELETE FROM emb_language WHERE type=' . EMBConst::DB_LANGUAGETYPE_MENUITEMLABEL . ' OR type=' . EMBConst::DB_LANGUAGETYPE_MENUITEMDESCRIPTION;
        Yii::app()->db->createCommand($sql)->execute();
    }
}