<?php
/**
 * Created by JetBrains PhpStorm.
 * User: joe
 * Date: 13.05.13
 * Time: 00:22
 * To change this template use File | Settings | File Templates.
 */

class EMBMongoDbMenuItem extends EMongoDocument
{
    //public $_id;
    public $menuid;
    public $itemid;
    public $labels=array();
    public $descriptions=array();
    public $descriptionashint;
    public $url;
    public $visible;
    public $active;
    public $icon;
    public $target;
    public $template;
    public $ajaxOptions;
    public $linkOptions;
    public $itemOptions;
    public $submenuOptions;
    public $scenarios;
    public $userroles;
    public $created;
    public $createduser;
    public $modified;
    public $modifieduser;


    /**
     * Returns the static model of the specified AM class.
     * @return the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    function collectionName()
    {
        return 'emb_menuitem';
    }

    function getCollectionName() //YiiMongoDbSuite
    {
        return $this->collectionName();
    }

    public function behaviors()
    {
        return array(
            'attributesBehavior' => 'EMBAttributesBehavior',
        );
    }

    protected function afterConstruct()
    {
        parent::afterConstruct();
        $this->initLanguageAttribute('labels'); //behavior method
        $this->initLanguageAttribute('descriptions'); //behavior method
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
        $this->descriptionashint = true;
        $this->active = false;
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
        $this->asa('attributesBehavior')->validateLanguageAttribute($attribute, 'Label', true);
    }


    public function validateDescriptions($attribute)
    {
        $this->asa('attributesBehavior')->validateLanguageAttribute($attribute, 'Description', false);
    }


    /**
     * Scenarios validation
     *
     * @param $attribute
     */
    public function validateScenarios($attribute)
    {
        $allowedItems = array_keys(MenubuilderModule::getInstance()->getSupportedScenarios());
        $this->asa('attributesBehavior')->validateArrayAttribute($attribute, $allowedItems);
    }


    /**
     * Userroles validation
     *
     * @param $attribute
     */
    public function validateUserRoles($attribute)
    {
        $allowedItems = array_keys(MenubuilderModule::getInstance()->getSupportedUserRoles());
        $this->asa('attributesBehavior')->validateArrayAttribute($attribute, $allowedItems);
    }


    /**
     * @return array validation rules for model attributes.
     */
    public function attributeLabels()
    {
        return array(
            'labels' => Yii::t('MenubuilderModule.messages', 'Labels'),
            'descriptions' => Yii::t('MenubuilderModule.messages', 'Descriptions'),
            'descriptionashint' => Yii::t('MenubuilderModule.messages', 'Description as hint'),
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