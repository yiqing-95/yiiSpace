<?php
/**
 * EMBAdminController.php
 *
 * The base controller to admin the menus and items
 *
 * Provided the actions:
 * - index: the admin form for menus/items
 * - saveAsDefault, restoreDefault, reinstall, export, import, flushCache in the util section of the adminform
 *
 * Don't touch this code:
 * Override the actions the accessrules, formPermissions rules ... in the inherited
 * ext.menubuilder.modules.menubuilder.controller.AdminController
 *
 * @author Joe Blocher <yii@myticket.at>
 * @copyright Copyright &copy; myticket it-solutions gmbh
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package menubuilder
 * @category User Interface
 * @version 1.0
 */

class EMBAdminController extends CController
{
    const FORMFIELD_CURRENTMENUONLY = 'EMBCurrentMenuOnly';

    public $layout = 'column1';

    /**
     * Internal used
     */
    protected $_formPermissions;
    protected $_openMenuItemForm = false;


    /**
     * The EUserFlash messages
     * Override this method if you want, but don't change the key
     * The value will be translated on setFlashMessage
     * Add the value to your messages.php
     *
     * @return array
     */
    protected function getFlashMessages()
    {
        return array(
            'nestedConfigSaved' => 'Menu structure saved',
            'nestedConfigSaveError' => 'Error on saving menu structure',
            'menuItemDeleted' => 'Menu item deleted',
            'menuItemDeleteError' => 'Error on deleting menu item',
            'menuItemUpdated' => 'Menu item updated',
            'menuItemCreated' => 'Menu item created',
            'menuCreated' => 'Menu created',
            'menuDeleted' => 'Menu deleted',
            'menuUpdated' => 'Menu updated',
            'noMenuFound'=>'No menu found: Please create a menu',
            'installedSuccess'=>'Menu and items installed successful',
            'errorOnInstall'=>'Error on installing menus/items',
            'restoreDefaultSuccess'=>'Menu and items restore successful',
            'errorOnRestoreDefault'=>'Error on restoring menus/items',
            'saveAsDefaultSuccess'=>'Saved menus/items as default',
            'saveAsDefaultError'=>'Error on saving as default',
            'backupFileError'=>'Error on creating backup file',
            'importSuccess'=>'Imported menus/items successful',
            'importError'=>'Error on importing menus/items',
            'cacheFlushed'=>'Cache flushed',
            'addedOwnRoles'=>'Added your own userroles as adminroles',
        );
    }


    /**
     * Set a EUserFlash message
     *
     * @param $key
     * @param $type
     * @throws CException
     */
    public function setFlashMessage($key,$type,$prefix='')
    {
        $messages = $this->getFlashMessages();
        if(!empty($prefix))
            $prefix = Yii::t('MenubuilderModule.messages', $prefix) . '<br/>';

        if(array_key_exists($key,$messages))
        {
            switch($type)
            {
                case 'notice':
                    EUserFlash::setNoticeMessage($prefix.Yii::t('MenubuilderModule.messages', $messages[$key]));
                    break;
                case 'error':
                    EUserFlash::setErrorMessage($prefix.Yii::t('MenubuilderModule.messages', $messages[$key]));
                    break;
                case 'success':
                    EUserFlash::setSuccessMessage($prefix.Yii::t('MenubuilderModule.messages', $messages[$key]));
                    break;
                default:
                    throw new CException(Yii::t('MenubuilderModule.messages', 'Invalid flashmessage type') .': '.$type);
            }
        }
         else
             throw new CException(Yii::t('MenubuilderModule.messages', 'Invalid flashmessage key').': '.$key);
    }


    /**
     * Render the admin form
     *
     * @throws CHttpException
     */
    public function actionIndex()
    {
        $module = $this->getModule();
        $module->cacheDuration=-1; //don't work with cached menu in menubuilder
        $dataAdapter = $module->getDataAdapter();

        if ($module->checkInstall)
        {
            $this->install();
        }

        $menuId = isset($_POST['menuId']) ? $_POST['menuId'] : $dataAdapter->getDefaultMenuId(true);

        if (empty($menuId)) //no menu for this user to admin: show the adminform with only the menu create form if create allowed
        {
            if ($this->menuActionAllowed('create'))
            {
                $this->_formPermissions = new EMBFormPermissions;
                $rules = array(array_merge(array('allow', 'users' => array('*')),
                    $this->_formPermissions->createMenuOnlyPermissions),
                    array('deny', 'users' => array('*'))
                );
                $this->_formPermissions->setRules($rules);
                $this->setFlashMessage('noMenuFound','notice');
            } else
                throw new CHttpException('400', Yii::t('MenubuilderModule.messages', 'No menu configured'));
        }

        $menuModel = $this->processMenuModel($menuId); //create the menu model
        $menuItemModel = $this->processMenuItemModel($menuId); //create the menuitem model
        $language = isset($_POST['language']) ? $_POST['language'] : Yii::app()->getLanguage();

        //itemsProvider: enforceNestedConfig,adminmode, without assigning roles/scenarios
        $itemsProvider = $module->getItemsProvider($menuId, array('language'=>$language),true);
        $itemsProvider->language = $language;

        //assign the current submitted nestedConfig
        $this->processNestedConfig($menuModel, $itemsProvider);

        $viewParams = array(
            'itemsProvider' => $itemsProvider,
            'dataAdapter' => $dataAdapter,
            'menuModel' => $menuModel,
            'menuItemModel' => $menuItemModel,
            'simulateModel' => $this->createSimulateModel(),
            'supportedUserRoles' => $module->getSupportedUserRoles(),
            'supportedScenarios' => $module->getSupportedScenarios(),
            'nestedConfigSaved' => strcmp($itemsProvider->nestedConfig,$menuModel->nestedconfig) == 0,
        );

        $this->render('index', array('viewParams' => $viewParams));
    }

    /**
     * @param $dataAdapter
     */
    public function install($reinstall=false)
    {
        $dataAdapter = $this->getModule()->getDataAdapter();
        $error = '';
        $installed = $dataAdapter->installMenus($reinstall, $error);
        if($installed === false)
            return false;

        if (empty($error))
            $dataAdapter->installMenuItems($reinstall, $error);

        if (!empty($error))
        {
            $this->setFlashMessage('errorOnInstall','error',$error);
            return false;
        }
        else
        {
            $this->setFlashMessage('installedSuccess','success');
            Yii::log('Menu and items installed', CLogger::LEVEL_WARNING);
            return true;
        }
    }

    /**
     * Reset all menus and items to the default installation
     */
    public function actionRestoreDefault()
    {
        if ($this->utilActionAllowed('restoredefault'))
        {
            $dataAdapter = $this->getModule()->getDataAdapter();
            //reinstall menus
            $error = '';
            $dataAdapter->installMenus(true, $error,true); //resetAll
            if (!empty($error))
                $this->setFlashMessage('errorOnRestoreDefault','error',$error);
            else
            {
                //reinstall menuitems
                $error = '';
                $dataAdapter->installMenuItems(true, $error,true); //resetAll
                if (!empty($error))
                    $this->setFlashMessage('errorOnRestoreDefault','error',$error);
                else {
                    $this->setFlashMessage('restoreDefaultSuccess','success');
                    Yii::log('Menu restore from default executed', CLogger::LEVEL_WARNING);
                }
            }

            $this->redirect($this->createUrl('index'));
        }
        else
            throw new CHttpException('400', 'Access denied');
    }


    /**
     * Reset all menus and items to the default installation
     */
    public function actionSaveAsDefault()
    {
        if ($this->utilActionAllowed('saveasdefault'))
        {
            if($this->getModule()->getDataAdapter()->saveAsDefault())
                $this->setFlashMessage('saveAsDefaultSuccess','success');
            else
               $this->setFlashMessage('saveAsDefaultError','error');

            $this->redirect($this->createUrl('index'));
        }
        else
            throw new CHttpException('400', 'Access denied');
    }

    /**
     * Export menus and menuitems in a zipfile
     *
     * @throws CHttpException
     */
    public function actionExport()
    {
         if ($this->utilActionAllowed('export'))
         {
             $zipFile = $this->getModule()->getDataAdapter()->zipbackup();
             if($zipFile !== false)
             {
                 $content = file_get_contents($zipFile);
                 Yii::app()->getRequest()->sendFile(basename($zipFile),$content,null,false);
                 unlink($zipFile);
                 Yii::app()->end();
             }
             else
                 $this->setFlashMessage('backupFileError','error');

             $this->redirect($this->createUrl('index'));
         }
         else
             throw new CHttpException('400', 'Access denied');
    }


    /**
     * Import menus and menuitems from a zipfile
     *
     * @throws CHttpException
     */
    public function actionImport()
    {
        if ($this->utilActionAllowed('import'))
        {
            $uploadedFile = CUploadedFile::getInstanceByName(EMBConst::FIELDNAME_IMPORT);
            if(!empty($uploadedFile))
            {
                $module = $this->getModule();
                $zipFile =$module->getDataPath().DIRECTORY_SEPARATOR.$uploadedFile->getName();
                if($uploadedFile->saveAs($zipFile))
                {
                    $error = '';
                    if($module->getDataAdapter()->zipimport($zipFile,$error))
                        $this->setFlashMessage('importSuccess','success');
                    else
                        $this->setFlashMessage('importError','error',$error);
                }
            }
            else
                $this->setFlashMessage('importError','error');

            $this->redirect($this->createUrl('index'));
        }
        else
            throw new CHttpException('400', 'Access denied');
    }


    /**
     * Flush the menu cache
     */
    public function actionFlushCache()
    {
        if ($this->utilActionAllowed('flushcache'))
        {
            $this->getModule()->flushCache(true);
            $this->setFlashMessage('cacheFlushed','success');
            $this->redirect($this->createUrl('index'));
        }
    }


    /**
     * Reinstall all menus/items
     */
    public function actionReinstall()
    {

        if ($this->utilActionAllowed('reinstall'))
        {
            $this->install(true);
            $this->redirect($this->createUrl('index'));
        }
        else
            throw new CHttpException('400', 'Access denied');
    }


    /**
     * Create the formPermissions instance
     *
     * @return EMBFormPermissions
     */
    public function getFormPermissions()
    {
        if (!isset($this->_formPermissions))
        {
            $class = $this->getModule()->formPermissionsClass;
            $this->_formPermissions = new $class;
            $this->_formPermissions->setRules($this->formPermissionRules());
        }

        return $this->_formPermissions;
    }


    /**
     * The rules for the form permissions analogous to accessrules
     * Available rules
     * @see EMBFormPermissions
     *
     * @return array
     */
    public function formPermissionRules()
    {
        return array();
    }

    /**
     * Check if the access to an array of menu attributes is allowed
     * Used to check the submitted $_POST vars in the method processMenuModel
     *
     * @param $attributes array
     * @return array
     */
    protected function checkMenuAttributesAllowed($attributes)
    {
        $result = array();
        foreach($attributes as $key=>$value)
            if($this->menuFieldAllowed($key))
                $result[$key]=$value;

        return $result;
    }

    /**
     * Check if the access to an array of menuitem attributes is allowed
     * Used to check the submitted $_POST vars in the method processMenuItemModel
     *
     * @param $attributes array
     * @return array
     */
    protected function checkMenuItemAttributesAllowed($attributes)
    {
        $result = array();
        foreach($attributes as $key=>$value)
            if($this->menuitemFieldAllowed($key))
                $result[$key]=$value;

        return $result;
    }

    /**
     * Check if a menu action is allowed
     *
     * @param $action
     * @return bool
     */
    public function menuActionAllowed($action)
    {
        return $this->getFormPermissions()->isAllowed('menu', $action);
    }

    /**
     * Check if a menu field is allowed
     *
     * @param $name
     * @return bool
     */
    public function menuFieldAllowed($name)
    {
        return $this->getFormPermissions()->isAllowed('menuFields', $name);
    }

    /**
     * Check if a menu item action is allowed
     *
     * @param $action
     * @return bool
     */
    public function menuitemActionAllowed($action)
    {
        return $this->getFormPermissions()->isAllowed('menuitem', $action);
    }

    /**
     * Check if a menu item field is allowed
     *
     * @param $name
     * @return bool
     */
    public function menuitemFieldAllowed($name)
    {
        return $this->getFormPermissions()->isAllowed('menuitemFields', $name);
    }

    /**
     * Check if a simulate field is allowed
     *
     * @param $name
     * @return bool
     */
    public function simulateFieldAllowed($name)
    {
        return $this->getFormPermissions()->isAllowed('simulateFields', $name);
    }

    /**
     * Check if a util action is allowed
     *
     * @param $action
     * @return bool
     */
    public function utilActionAllowed($action)
    {
        return $this->getFormPermissions()->isAllowed('util', $action);
    }


    /**
     * The available items rendered in the target dropdown in the menuitem form
     * @return array
     */
    public function getLinkTargets()
    {
        return array(
            '_blank' => Yii::t('MenubuilderModule.messages', 'New window/tab'),
            '_top' => Yii::t('MenubuilderModule.messages', 'This window/tab'),
            '_self' => Yii::t('MenubuilderModule.messages', 'Inside the same frame'),
            '_parent' => Yii::t('MenubuilderModule.messages', 'Inside the parent frame'),
        );
    }

    /**
     * Get the dropDownList for the icons from the configured iconProvider
     *
     * @param $model
     * @param $attribute
     * @param array $htmlOptions
     * @return null
     */
    public function iconDropDownList($model, $attribute, $htmlOptions = array())
    {
        $iconProvider = $this->getModule()->getIconProvider();
        return !empty($iconProvider) ? $iconProvider->dropDownList($model, $attribute, $htmlOptions) : null;
    }


    /**
     * The labeltemplate in the arrange menuitems section
     *
     * @return string
     */
    public function getLabelTemplate()
    {
        $labelTemplate = '<a href="{url}" target="_blank">&raquo;</a> {label}';

        $adminInfo = '';

        if ($this->menuitemFieldAllowed('userroles'))
            $adminInfo .= '[R: {userroles}]';
        if ($this->menuitemFieldAllowed('scenarios'))
            $adminInfo .= '[S: {scenarios}]';

        if (!empty($adminInfo))
            $adminInfo = ' <span style="font-size: 80%;">' . $adminInfo . '</span>';

        if ($this->menuActionAllowed('update') || $this->menuActionAllowed('delete'))
            $adminInfo .= '<span class="right pull-right">{edit}</span>';

        return $labelTemplate . $adminInfo;
    }

    /**
     * Get the nested config from the arrange items form
     *
     * @param $menuModel
     * @param $itemsProvider
     */
    protected function processNestedConfig($menuModel, $itemsProvider)
    {
        $module = $this->getModule();
        $nestedConfig = isset($_POST[EMBConst::HIDDENNAME_NESTEDCONFIG])
            ? $_POST[EMBConst::HIDDENNAME_NESTEDCONFIG]
            : null;

        if (isset($nestedConfig))
        {
            $itemsProvider->nestedConfig = $nestedConfig;

            //save nestedConfig if it's not preview
            if (isset($_POST[EMBConst::BUTTONNAME_UPDATENESTEDCONFIG]))
            {
                if ($module->getDataAdapter()->saveNestedConfig($menuModel->menuid, $nestedConfig))
                {
                    $menuModel->nestedconfig = $nestedConfig; //necessary in memory?
                    $module->flushCache();
                    $this->setFlashMessage('nestedConfigSaved','success');
                }
                else
                    $this->setFlashMessage('nestedConfigSaveError','error');
            }
        }
    }

    /**
     * Create the menuitem model depending on the formpermissions and the clicked button in the adminform
     *
     * @param $menuId
     * @return mixed
     * @throws CHttpException
     */
    public function processMenuItemModel($menuId)
    {
        $module = $this->getModule();
        $dataAdapter = $module->getDataAdapter();
        $menuItemClass = $dataAdapter->menuItemClass;

        $hasMenuFormData = isset($_POST[$menuItemClass]);
        $doCreate = isset($_POST[EMBConst::BUTTONNAME_CREATEITEM]) && $this->menuitemActionAllowed('create');
        $doUpdate = isset($_POST[EMBConst::BUTTONNAME_UPDATEITEM]) && $this->menuitemActionAllowed('update');
        $doDelete = isset($_POST[EMBConst::BUTTONNAME_DELETEITEM]) && $this->menuitemActionAllowed('delete');
        $menuFormActionClicked = $hasMenuFormData && ($doUpdate || $doCreate || $doDelete);
        $doEditItem = !empty($_POST[EMBConst::HIDDENNAME_EDITITEM]);
        $menuSwitched = isset($_POST[EMBConst::HIDDENNAME_OLDMENUID]) && $_POST[EMBConst::HIDDENNAME_OLDMENUID] != $menuId;

        //edit link clicked: fill the menuitem form
        if ($doEditItem)
        {
            $model = $dataAdapter->loadMenuItem($_POST[EMBConst::HIDDENNAME_EDITITEM], true);

            if (empty($model))
                throw new CHttpException(500, 'Invalid itemid');
            $this->_openMenuItemForm = true;

            return $model;
        }

        //return empty item if menuswitched or not a itemform action clicked
        if($menuSwitched || !$menuFormActionClicked)
            return new $menuItemClass;

        $itemId = isset($_POST[$menuItemClass]['itemid'])
            ? $_POST[$menuItemClass]['itemid'] : null;

        $model = empty($itemId) ? new $menuItemClass : $dataAdapter->loadMenuItem($itemId, true);
        if (empty($model)) //not found
            $model = new $menuItemClass;

        $model->attributes = $this->checkMenuItemAttributesAllowed($_POST[$menuItemClass]);

        if ($menuFormActionClicked)
        {
            //-------------------- create item --------------------
            if ($doCreate)
            {
                $model->setIsNewRecord(true); //if created as copy
                $model->itemid = null;
                $model->menuid = empty($_POST[self::FORMFIELD_CURRENTMENUONLY]) ? null : $menuId;

                if ($model->save())
                {
                    $this->setFlashMessage('menuItemCreated','success');
                    return new $menuItemClass;
                }
                else //otherwise the model->errors are displayed
                    return $model;
            }
            //-------------------- update item --------------------
            elseif(!empty($model->itemid) && $doUpdate)
            {
                $model->menuid = empty($_POST[self::FORMFIELD_CURRENTMENUONLY]) ? null : $menuId;

                //empty if not submitted
                if (empty($_POST[$menuItemClass]['scenarios']))
                    $model->scenarios = null;

                if (empty($_POST[$menuItemClass]['userroles']))
                    $model->userroles = null;

                if ($model->save())
                {
                    $module->flushCache();
                    $this->setFlashMessage('menuItemUpdated','success');
                    return new $menuItemClass;
                } //otherwise the model->errors are displayed
            }
            //-------------------- delete item --------------------
            elseif(!empty($model->itemid) && $doDelete)
            {
                if ($model->delete())
                {
                    $module->flushCache();
                    $this->setFlashMessage('menuItemDeleted','success');
                    return new $menuItemClass;
                } else
                    $this->setFlashMessage('menuItemDeleteError','error');
            }
        }

        return $model;
    }


    /**
     * Create the menu model depending on the formpermissions and the clicked button in the adminform
     *
     * @param $menuId
     * @return mixed
     * @throws CHttpException
     */
    public function processMenuModel(&$menuId)
    {
        $module = $this->getModule();
        $dataAdapter = $module->getDataAdapter();
        $menuClass = $dataAdapter->menuClass;

        $hasMenuFormData = isset($_POST[$menuClass]);
        $doUpdate = isset($_POST[EMBConst::BUTTONNAME_UPDATEMENU]) && $this->menuActionAllowed('update');
        $doCreate = isset($_POST[EMBConst::BUTTONNAME_CREATEMENU]) && $this->menuActionAllowed('create');
        $doDelete = isset($_POST[EMBConst::BUTTONNAME_DELETEMENU]) && $this->menuActionAllowed('delete');
        $menuFormActionClicked = $hasMenuFormData && ($doUpdate || $doCreate || $doDelete);

        $model = $dataAdapter->loadMenu($menuId, true);

        if (!$menuFormActionClicked)
        {
            return empty($model) ? new $menuClass : $model;
        }


        if (empty($model)) //user has changed the menuid or menu was deleted; save as new
        {
            if ($doUpdate || $doCreate)
            {
                $newModel = new $menuClass;
                $newModel->attributes = $this->checkMenuAttributesAllowed($_POST[$menuClass]);

                if ($newModel->save())
                {
                    $module->flushCache();
                    $menuId = $newModel->menuid;
                    $msgKey = $doUpdate ? 'menuUpdated' : 'menuCreated';
                    $this->setFlashMessage($msgKey,'success');
                } //otherwise the model->errors are displayed

                return $newModel;
            }
            elseif($doDelete)
            {
                $module->flushCache();
                $this->setFlashMessage('menuItemDeleteError','error');
                $this->redirect($this->createUrl(''));
            }
        }
        else //update, create or delete menu
        {
            if ($doUpdate || $doCreate)
            {
                $model->attributes = $this->checkMenuAttributesAllowed($_POST[$menuClass]);

                //empty if not submitted
                if (empty($_POST[$menuClass]['scenarios']))
                    $model->scenarios = null;

                if (empty($_POST[$menuClass]['userroles']))
                    $model->userroles = null;

                if (empty($_POST[$menuClass]['adminroles']))
                    $model->adminroles = null;

                //don't lock yourself out: add the own userroles as adminroles
                if(!empty($model->adminroles))
                {
                    $dataFilterClass = $module->dataFilterClass;
                    $currentUserRoles  = array_keys($dataFilterClass::getCurrentUserRoles());
                    if(!empty($currentUserRoles))
                    {
                        $intersect = array_intersect($currentUserRoles,$model->adminroles);
                        if(empty($intersect))
                        {
                            $model->adminroles = array_merge($model->adminroles,$currentUserRoles);
                            $this->setFlashMessage('addedOwnRoles','notice');
                        }
                    }
                }

                if($doCreate)
                    $model->setIsNewRecord(true);

                if ($model->save())
                {
                    $module->flushCache();
                    $menuId = $model->menuid;
                    $msgKey = $doUpdate ? 'menuUpdated' : 'menuCreated';
                    $this->setFlashMessage($msgKey,'success');
                } //otherwise the model->errors are displayed

                return $model;
            }
            elseif($doDelete)
            {
                $module->flushCache();

                if($model->delete())
                    $this->setFlashMessage('menuItemDeleted','success');
                else
                    $this->setFlashMessage('menuItemDeleteError','error');

                $this->redirect($this->createUrl(''));
            }
        }

        throw new CHttpException(400, 'Invalid menu action');
    }


    /**
     * Create the simulate form model
     *
     * @return EMBSimulateForm
     */
    protected function createSimulateModel()
    {
        $model = new EMBSimulateForm;
        if (isset($_POST['EMBSimulateForm']) && $this->menuActionAllowed('simulate'))
        {
            $model->attributes = $_POST['EMBSimulateForm'];
            if (!$model->validate())
            {
                $model->scenarios = null;
                $model->userroles = null;
            }
        }
        return $model;
    }
}