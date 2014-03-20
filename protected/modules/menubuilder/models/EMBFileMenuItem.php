<?php
/**
 * Created by JetBrains PhpStorm.
 * User: joe
 * Date: 13.05.13
 * Time: 00:22
 * To change this template use File | Settings | File Templates.
 */

class EMBFileMenuItem extends ArrayModel
{
    protected $_filename;

    /**
     * Returns the static model of the specified AM class.
     * @return the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function behaviors()
    {
        return array(
            'attributesBehavior'=>'EMBAttributesBehavior',
        );
    }

    protected function afterConstruct()
    {
        parent::afterConstruct();
        $this->initLanguageAttribute('labels'); //behavior method
        $this->initLanguageAttribute('descriptions'); //behavior method
    }

    public function setFilename($filename)
    {
        $this->_filename = $filename;
    }

    /**
     * @return string the associated array data file location (must be writable, to make use of the saving functionalities)
     */
    public function fileName()
    {
        $name = !empty($this->_filename) ? $this->_filename : 'mbmenuitems';
        return MenuBuilderModule::getInstance()->getDataFilePath() . '.'.$name;
    }


    /**
     * Initializes this model.
     * This method is invoked when an ArrayModel instance is newly created.
     * You may override this method to provide code that is needed to initialize the model (e.g. setting
     * initial property values.)
     */
    public function init()
    {
        parent::init();
        $this->visible = true;
        $this->descriptionashint=true;
        $this->active=0;
    }

    /**
     * @return array the associated array structure ('key' => 'attribute'). The list of
     * available attributes will be based on this list.
     */
    public function arrayStructure()
    {
        return array(
            'menuid' => 'menuid',
            'itemid' => 'itemid',
            'labels' => 'labels',
            'descriptions' => 'descriptions',
            'descriptionashint' => 'descriptionashint',
            'url' => 'url',
            'visible' => 'visible',
            'active' => 'active',
            'icon' => 'icon',
            'target' => 'target',
            'template' => 'template',
            'ajaxOptions' => 'ajaxOptions',
            'linkOptions' => 'linkOptions',
            'itemOptions' => 'itemOptions',
            'submenuOptions' => 'submenuOptions',
            'scenarios' => 'scenarios',
            'userroles' => 'userroles',
            'created' => 'created',
            'createduser' => 'createduser',
            'modified' => 'modified',
            'modifieduser' => 'modifieduser',
        );
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('labels', 'validateLabels'),
            array('descriptions', 'validateDescriptions'),
            array('itemid,menuid,target', 'length', 'max' => 40),
            array('url,template,ajaxOptions,linkOptions,itemOptions,submenuOptions', 'length', 'max' => 300),
            array('visible,active,descriptionashint', 'boolean'),
            array('scenarios', 'validateScenarios'),
            array('userroles', 'validateUserRoles'),
            array('icon', 'length', 'max' => 40),
        );
    }





    public function validateLabels($attribute)
    {
        $this->asa('attributesBehavior')->validateLanguageAttribute($attribute,'Label',true);
    }


    public function validateDescriptions($attribute)
    {
        $this->asa('attributesBehavior')->validateLanguageAttribute($attribute,'Description',false);
    }



    /**
     * Scenarios validation
     *
     * @param $attribute
     */
    public function validateScenarios($attribute)
    {
        $allowedItems = array_keys(MenubuilderModule::getInstance()->getSupportedScenarios());
        $this->asa('attributesBehavior')->validateArrayAttribute($attribute,$allowedItems);
    }


    /**
     * Userroles validation
     *
     * @param $attribute
     */
    public function validateUserRoles($attribute)
    {
        $allowedItems = array_keys(MenubuilderModule::getInstance()->getSupportedUserRoles());
        $this->asa('attributesBehavior')->validateArrayAttribute($attribute,$allowedItems);
    }



    /**
     * @return array validation rules for model attributes.
     */
    public function attributeLabels()
    {
        return array(
            'labels' => Yii::t('MenubuilderModule.messages', 'Labels'),
            'descriptions'=>Yii::t('MenubuilderModule.messages','Descriptions'),
            'descriptionashint'=>Yii::t('MenubuilderModule.messages','Description as hint'),
            'visible' => Yii::t('MenubuilderModule.messages', 'Visible'),
            'active' => Yii::t('MenubuilderModule.messages', 'Active'),
            'ajaxOptions' => Yii::t('MenubuilderModule.messages', 'Ajax options'),
            'linkOptions' => Yii::t('MenubuilderModule.messages', 'Link options'),
            'itemOptions' => Yii::t('MenubuilderModule.messages', 'Item options'),
            'submenuOptions' => Yii::t('MenubuilderModule.messages', 'Submenu options'),
            'template' => Yii::t('MenubuilderModule.messages', 'Template'),
            'scenarios' => Yii::t('MenubuilderModule.messages', 'Scenarios'),
            'userroles' => Yii::t('MenubuilderModule.messages', 'Userroles'),
            'url' => Yii::t('MenubuilderModule.messages', 'Url'),
            'icon' => Yii::t('MenubuilderModule.messages', 'Icon'),
            'target' => Yii::t('MenubuilderModule.messages', 'Target'),
        );
    }


    /**
     * Remove the itemid from all menus->nestedconfig
     *
     * @return bool
     */
    protected function afterDelete()
    {
        parent::afterDelete();
        MenuBuilderModule::getInstance()->getDataAdapter()->removeItemFromMenus($this->itemid);
        return true;
    }


    protected function beforeSave()
    {
        if (parent::beforeSave())
        {
            if (empty($this->itemid))
                $this->itemid = md5(uniqid());

            if (empty($this->url))
                $this->url = '#';

            if (!isset($this->active))
                $this->active = false;

            if (!isset($this->visible))
                $this->visible = true;

            if (!isset($this->descriptionashint))
                $this->descriptionashint = true;

            if (!isset($this->labels))
                $this->labels = $this->asa('attributesBehavior')->initLanguageAttribute('labels');

            if (!isset($this->descriptions))
                $this->descriptions = $this->asa('attributesBehavior')->initLanguageAttribute('descriptions');

            return true;
        } else
            return false;
    }

}