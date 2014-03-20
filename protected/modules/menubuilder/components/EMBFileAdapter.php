<?php
/**
 * EMBFileAdapter.php
 *
 * The dataadapter for the filebased storage
 *
 * @author Joe Blocher <yii@myticket.at>
 * @copyright Copyright &copy; myticket it-solutions gmbh
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package menubuilder
 * @category User Interface
 * @version 1.0
 */
class EMBFileAdapter extends EMBDataAdapter
{
    /**
     * The class of a menu model
     *
     * @var string
     */
    public $menuClass = 'EMBFileMenu';

    /**
     * The class of a menu item model
     *
     * @var string
     */
    public $menuItemClass = 'EMBFileMenuItem';

    /**
     * Sort the menus by the attribute sortposition
     *
     * @param $a
     * @param $b
     * @return int
     */
    protected function sortMenus($a, $b)
    {
        if ($a->sortposition == $b->sortposition)
        {
            $title_a = $a->getI8NAttribute('titles',null);
            $title_b = $b->getI8NAttribute('titles',null);
            return ($title_a < $title_b) ? -1 : 1;
        }
        else
            return ($a->sortposition < $b->sortposition) ? -1 : 1;
    }

    /**
     * Find all menu items with empty or the specified menuid
     *
     * @param $menuId
     * @return array
     */
    protected function findAllMenuItems($menuId)
    {
        $class = $this->menuItemClass;
        $condition = array(array(array('menuid' => $menuId), 'OR', array('menuid' => '')));
        return $class::model()->findAll($condition);
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
            if (is_string($scenarios))
                $scenarios = array($scenarios);

            $count = count($scenarios);

            $conditions = array();
            for ($i = 0; $i < $count; $i++)
            {
                $conditions[] = array('scenarios %' => $scenarios[$i]);
                if ($i < $count - 1)
                    $conditions[] = 'OR';
            }
        } else
            $conditions = null;

        $class = $this->menuClass;
        $result = $class::model()->findAll($conditions);
        usort($result,array($this,'sortMenus'));
        return $result;
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
        return $class::model()->find(array(array('menuid' => $menuId)));
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
        return $class::model()->find(array(array('itemid' => $itemId)));
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
        return $class::model()->deleteAll(array(array('menuid' => $menuId)));
    }


    /**
     * Install the menus from data.installmenus file
     *
     * @param bool $resetAll
     * @param string $error
     * @param bool $fromDefault
     * @return int
     */
    public function installMenus($resetAll = false, &$error = '',$fromDefault=false)
    {
        $imported = 0;
        $menuBuilder = MenubuilderModule::getInstance();

        if (!$menuBuilder->checkInstall && !$resetAll)
            return false;


        $class = $this->menuClass;
        $menuModel = new $class;

        $file = Yii::getPathOfAlias($menuModel->fileName()) . '.php';

        if ($resetAll && is_file($file))
            unlink($file);

        if (!is_file($file))
        {
            file_put_contents($file, '<?php return array(); ?>');

            if($fromDefault)
            {
                $installFile = $menuBuilder->getDefaultMenusConfigFile();
                if(!is_file($installFile))
                    $installFile = $menuBuilder->getInstallMenusConfigFile();
            }
            else
                $installFile = $menuBuilder->getInstallMenusConfigFile();

            $menus = $menuBuilder->itemsFromFile($installFile);
            $this->appendFromArray($menus,$class,$error);

            return empty($error);
        }

        return false;
    }

    /**
     * Install the menuitems from data.installmenuitems file
     *
     * @param bool $resetAll
     * @param string $error
     * @param bool $fromDefault
     * @return int
     */
    public function installMenuItems($resetAll = false, &$error = '',$fromDefault=false)
    {
        $imported = 0;
        $menuBuilder = MenubuilderModule::getInstance();

        if (!$menuBuilder->checkInstall && !$resetAll)
            return false;

        $class = $this->menuItemClass;
        $menuItemsModel = new $class;

        $file = Yii::getPathOfAlias($menuItemsModel->fileName()) . '.php';

        if ($resetAll && is_file($file))
            unlink($file);

        if (!is_file($file))
        {
            file_put_contents($file, '<?php return array(); ?>');

            if($fromDefault)
            {
                $installFile = $menuBuilder->getDefaultMenuItemsConfigFile();
                if(!is_file($installFile))
                    $installFile = $menuBuilder->getInstallMenuItemsConfigFile();
            }
            else
                $installFile = $menuBuilder->getInstallMenuItemsConfigFile();

            $menuItems = $menuBuilder->itemsFromFile($installFile);
            $imported = $this->appendFromArray($menuItems,$class,$error);
        }

        return false;
    }

    /**
     * Save all menus/items as the default file
     *
     * @return bool
     */
    public function saveAsDefault()
    {
        $class = $this->menuItemClass;
        $model = new $class;
        $menuBuilder = MenubuilderModule::getInstance();
        $source = Yii::getPathOfAlias($model->fileName()) . '.php';
        $dest = $menuBuilder->getDefaultMenuItemsConfigFile();

        if (copy($source, $dest))
        {
            $class = $this->menuClass;
            $model = new $class;
            $source = Yii::getPathOfAlias($model->fileName()) . '.php';
            $dest = $menuBuilder->getDefaultMenusConfigFile();

            return copy($source, $dest);
        }
        else
            return false;
    }

    /**
     * Export to a zip archive
     *
     * @param $zip
     */
    protected function exportToZip($zip)
    {
        $menuBuilder = MenubuilderModule::getInstance();

        $class = $this->menuClass;
        $model = new $class;
        $menuSource = Yii::getPathOfAlias($model->fileName()) . '.php';
        $menuFile = $menuBuilder->getDataPath() . DIRECTORY_SEPARATOR. 'exp_menus.php';

        if (!copy($menuSource, $menuFile))
            return;

        $class = $this->menuItemClass;
        $model = new $class;
        $menuItemsSource = Yii::getPathOfAlias($model->fileName()) . '.php';
        $menuItemsFile = $menuBuilder->getDataPath() . DIRECTORY_SEPARATOR. 'exp_menuitems.php';

        if (!copy($menuItemsSource, $menuItemsFile))
            return;

        $zip->addFile($menuFile, basename($menuFile));
        $zip->addFile($menuItemsFile, basename($menuItemsFile));
        $ok = $zip->close();
        if ($ok)
        {
            // unlink($menuFile);
            // unlink($menuItemFile);
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
        if($zip->locateName('exp_menus.php',ZIPARCHIVE::FL_NODIR) !== false && $zip->locateName('exp_menuitems.php',ZIPARCHIVE::FL_NODIR) !== false)
        {
            return $zip->extractTo(MenubuilderModule::getInstance()->getDataPath());
        }
        else
            return false;
    }
}