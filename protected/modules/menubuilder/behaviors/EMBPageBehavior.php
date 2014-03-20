<?php
/**
 * EMBPageBehavior.php
 *
 * Don't use this - still in work
 * If finished:
 *  - integrate input elements for autogenerate menuitems for a page in your cms
 *
 *
 * @author Joe Blocher <yii@myticket.at>
 * @copyright Copyright &copy; myticket it-solutions gmbh
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package menubuilder
 * @category User Interface
 * @version 1.0
 */
class EMBPageBehavior extends CActiveRecordBehavior
{
    public $attribute;
    public $usePostVars=true;

    protected $_menu;
    protected $_menuItem;
    protected $_menuId;
    protected $_menuitemId;
    protected $_labelsHash;
    protected $_insertBefore;
    protected $_dataAdapter;
    protected $_controller;


    public function getDataAdapter()
    {
        if(!isset($this->_dataAdapter))
            $this->_dataAdapter = MenubuilderModule::getInstance()->getDataAdapter;

        return $this->_dataAdapter;
    }


    public function getInsertBefore()
    {
        return $this->_insertBefore;
    }

    public function getMenu()
    {
        return $this->_menu;
    }

    public function getMenuItem()
    {
        return $this->_menuItem;
    }

    public function setController($controller)
    {
        $this->_controller=$controller;
    }

    public function getController()
    {
        if(!isset($this->_controller))
            $this->_controller = Yii::app()->controller;
        return $this->_controller;
    }

    public function getFieldName($name)
    {
        return EMBConst::FIELDNAME_PAGEBEHAVIOR.'['.$name.']';
    }


    protected function getLabelsHash($labels)
    {
        return md5(serialize($labels));
    }

    public function getMenuItemsDropDownList()
    {
        $menuIds = $this->getDataAdapter()->loadAllMenuIds(true); //adminMode=true
        $select = empty($this->_menuitemId) ? '' : $this->_menuId . EMBDropDownList::KEYS_SEPARATOR . $this->_menuitemId;

        return $this->getController()->widget('EMBDropDownList', array(
            'name'=>$this->getFieldName($this->attribute),
            'select'=>$select,
            'menuIds'=>$menuIds
        ),true);
    }


    public function renderHiddenFields()
    {
        $model=$this->getOwner();
        $attribute=$this->attribute;
        $value=$model->$attribute;

        if(!empty($value))
            echo CHtml::hiddenField($this->getFieldName('old_'.$this->attribute),$value);

        if(!empty($this->_labelsHash))
           echo CHtml::hiddenField($this->getFieldName('labelsHash'),$this->_labelsHash);
    }

    public function createMenuItem($attributes=null)
    {
        $class=$this->getDataAdapter()->menuItemClass;
        $menuItem= new $class;
        if(isset($attributes))
            $menuItem->setAttributes($attributes);
        return $menuItem;
    }

    public function processFormAttributes()
    {
        $formVars = $this->usePostVars ? $_POST : $_GET;

        if(isset($formVars[EMBConst::FIELDNAME_PAGEBEHAVIOR]))
        {
           $labelsField = $this->getFieldName('labels');
           $labelsHash = $this->getFieldName('labelsHash');
           $oldValueField = $this->getFieldName('old_'.$this->attribute);
           $itemBeforeField = $this->getFieldName('itemBefore');
           $menuItemsField = $this->getFieldName($this->attribute);

           $oldLabelsHash=isset($formVars[$labelsHash]) ? $formVars[$labelsHash] : null;
           $oldValue = isset($formVars[$oldValueField]) ? $formVars[$oldValueField] : null;
           list($oldMenuId,$oldMenuitemId,$oldInsertBefore)=$this->decodeAttributeValue($oldValue);

            list($newMenuId,$newItemId) = explode(EMBDropDownList::KEYS_SEPARATOR,$formVars[$menuItemsField]);
            $newInsertBefore = !empty($formVars[$itemBeforeField]) ? true : false;
            $newValue=$newMenuId.EMBDropDownList::KEYS_SEPARATOR.$newItemId.EMBDropDownList::KEYS_SEPARATOR.$newInsertBefore;
            $newLabels=isset($formVars[$labelsField]) ? $formVars[$labelsField] : null;
            $newLabelsHash=$this->getLabelsHash($newLabels);

            $menuChanged = $newMenuId != $oldMenuId;
            $labelsChanged = $newLabelsHash != $oldLabelsHash;
            $valueChanged = $newValue != $oldValue;

           if($valueChanged)
           {
               $this->getOwner()->setAttribute($this->attribute,$newValue);

               if($menuChanged) //remove from old menu
               {
                   $this->initMenuData($oldValue); //load with old values
                   if(!empty($this->_menu) && !empty($this->_menuitemId))
                   {
                       $nestedConfig = $this->_menu->nestedconfig;
                       $this->_menu->nestedconfig = EMBNestedConfigUtil::removeId($nestedConfig,$this->_menuitemId);
                       $this->_menu->save();
                   }
               }

               $this->initMenuData(); //load with new values
               if(!empty($this->_menu))
               {
                   $nestedConfig = $this->_menu->nestedconfig;
                   if($oldValue == null) //new model -> do insert
                   {
                       $newItem = $this->createMenuItem(array('labels'=>$newLabels,'url'=>$this->getOwner()->getActionView()));
                       $newItem->save();
                   }
                   else
                   {
                       $newItem = $this->_menuItem;

                       if(!empty($newItem))
                       {
                           if($labelsChanged && !empty($newItem))
                           {
                               $newItem->labels=$newLabels;
                               $newItem->url=$this->getOwner()->getActionView();
                               $newItem->save();
                           }
                           elseif(Yii::app()->createUrl($this->getOwner()->getActionView()) != $newItem->url) //check url changed
                           {
                               $newItem->url=$this->getOwner()->getActionView();
                               $newItem->save();
                           }
                       }
                   }

                   if(!empty($newItem))
                   {
                       if($newInsertBefore)
                           $this->_menu->nestedconfig = EMBNestedConfigUtil::moveBefore($nestedConfig,$newItemId,$newItem->itemid);
                       else
                           $this->_menu->nestedconfig = EMBNestedConfigUtil::moveAfter($nestedConfig,$newItemId,$newItem->itemid);
                       $this->_menu->save();
                   }
               }
           }
           else
           if($labelsChanged)
           {
               $this->initMenuData(); //load with new values
               if(!empty($this->_menuItem))
               {
                   $this->_menuItem->labels=$newLabels;
                   $this->_menuItem->url=$this->getOwner()->getActionView();
                   $this->_menuItem->save();
               }
           }
        }
    }


    protected function decodeAttributeValue($value=null)
    {
        if($value===null)
        {
            $attribute = $this->attribute;
            $value = $this->owner->$attribute;
        }

        if(!empty($value))
           return explode(EMBDropDownList::KEYS_SEPARATOR,$value);
        else
           return array();
    }



    protected function initMenuData($value=null)
    {
        $this->_menuId = null;
        $this->_menu = null;
        $this->_menuitemId = null;
        $this->_menuItem = null;
        $this->_labelsHash = null;

        list($menuId,$menuitemId,$insertBefore)=$this->decodeAttributeValue($value);

        if(!empty($menuId))
        {
            $this->_menuId = $menuId;
            $this->_menu=$this->getDataAdapter()->loadMenu($this->_menuId,true); //adminMode=true

        }

        if(!empty($menuitemId) && !empty($this->_menu))
        {
            $this->_menuitemId=$menuitemId;
            $this->_menuItem=$this->getDataAdapter()->loadMenuItem($menuitemId,true); //adminMode=true($this->_menuId,true); //adminMode=true
            if(!empty($this->_menuItem))
              $this->_labelsHash=$this->getLabelsHash($this->_menuItem->labels);
        }
    }


    public function render($view='blueprint',$controller=null)
    {
        $this->initMenuData();

        if(strpos($view->view,'.') === false)
           $view = 'menubuilder.views.'.MenubuilderModule::getInstance()->theme.'.pagebehavior.'.$view;

        $this->setController($controller);
        $this->getController()->renderPartial($view,array('pageBehavior'=>$this));
    }

    public function beforeSave($event)
    {
        $this->processFormAttributes();
    }

    public function afterDelete($event)
    {
        list($menuId,$menuitemId,$insertBefore)=$this->decodeAttributeValue();
        if(!empty($menuitemId))
        {
            $this->initMenuData();
            if(!empty($this->_menu))
            {
                $nestedConfig = $this->_menu->nestedconfig;
                $this->_menu->nestedconfig = EMBNestedConfigUtil::removeId($nestedConfig,$this->_menuitemId);
                $this->_menu->save();
            }

            if(!empty($this->_menuItem))
                $this->_menuItem->delete();
        }

    }
}