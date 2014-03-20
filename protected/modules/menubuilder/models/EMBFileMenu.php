<?php
/**
 * Created by JetBrains PhpStorm.
 * User: joe
 * Date: 13.05.13
 * Time: 00:22
 * To change this template use File | Settings | File Templates.
 */

class EMBFileMenu extends ArrayModel
{


    protected $_filename;

    /**
     * Returns the static model of the specified AM class.
     * @return the static model class
     */
    public static function model($className=__CLASS__)
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
        $this->initLanguageAttribute('titles'); //behavior method
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
        $name = !empty($this->_filename) ? $this->_filename : 'mbmenus';
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
        $this->visible=true;
        $this->descriptionashint=true;
        $this->locked=false;
        $this->maxdepth=3;
        $this->sortposition=0;
    }

    /**
     * @return array the associated array structure ('key' => 'attribute'). The list of
     * available attributes will be based on this list.
     */
    public function arrayStructure()
    {
        return array(
            'menuid' => 'menuid',
            'titles' => 'titles',
            'descriptions' => 'descriptions',
            'descriptionashint' => 'descriptionashint',
            'visible' => 'visible',
            'locked' => 'locked',
            'sortposition' => 'sortposition',
            'maxdepth' => 'maxdepth',
            'icon' => 'icon',
            'nestedconfig' => 'nestedconfig',
            'userroles' => 'userroles',
            'scenarios' => 'scenarios',
            'adminroles' => 'adminroles',
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
            array('menuid', 'required'),
            array('menuid', 'validateMenuId'),
            array('menuid', 'length', 'max' => 40),
            array('titles', 'validateTitles'),
            array('descriptions', 'validateDescriptions'),
            array('maxdepth', 'numerical', 'integerOnly' => true,'min'=>1,'max'=>5),
            array('sortposition', 'numerical', 'integerOnly' => true,'allowEmpty'=>true),
            array('visible,locked,descriptionashint', 'boolean'),
            array('icon', 'length', 'max' => 40),
            array('scenarios', 'validateScenarios'),
            array('userroles', 'validateUserRoles'),
            array('adminroles', 'validateAdminRoles'),
            array('nestedconfig', 'length', 'max' => 5000),
        );
    }


    /**
     * MenuId validation
     *
     * @param $attribute
     */
    public function validateMenuId($attribute)
    {
        $this->asa('attributesBehavior')->validateMenuId($attribute);
    }

    /**
     * Labels validation
     *
     * @param $attribute
     */
    public function validateTitles($attribute)
    {
        $this->asa('attributesBehavior')->validateLanguageAttribute($attribute,'Title',true);
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
     * Userroles validation
     *
     * @param $attribute
     */
    public function validateAdminRoles($attribute)
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
            'menuid'=>Yii::t('MenubuilderModule.messages','ID'),
            'titles'=>Yii::t('MenubuilderModule.messages','Titles'),
            'descriptions'=>Yii::t('MenubuilderModule.messages','Descriptions'),
            'descriptionashint'=>Yii::t('MenubuilderModule.messages','Description as hint'),
            'visible'=>Yii::t('MenubuilderModule.messages','Visible'),
            'locked'=>Yii::t('MenubuilderModule.messages','Locked'),
            'sortposition'=>Yii::t('MenubuilderModule.messages','Sortposition'),
            'maxdepth'=>Yii::t('MenubuilderModule.messages','Maxdepth'),
            'scenarios'=>Yii::t('MenubuilderModule.messages','Scenarios'),
            'userroles'=>Yii::t('MenubuilderModule.messages','Userroles'),
            'adminroles'=>Yii::t('MenubuilderModule.messages','Adminroles'),
            'url'=>Yii::t('MenubuilderModule.messages','Url'),
            'icon'=>Yii::t('MenubuilderModule.messages','Icon'),
        );
    }


    protected function afterDelete()
    {
        parent::afterDelete();
        MenubuilderModule::getInstance()->getDataAdapter()->deleteMenuItemsByMenuId($this->menuid);
    }

    protected function beforeSave()
    {
        if(parent::beforeSave())
        {
            if(empty($this->menuid))
                return false;

            if (!isset($this->visible))
                $this->visible = true;

            if (!isset($this->descriptionashint))
                $this->descriptionashint = true;

            if (!isset($this->titles))
                $this->titles = $this->asa('attributesBehavior')->initLanguageAttribute('titles');

            if(empty($this->sortposition))
                $this->sortposition = 0;

            if (!isset($this->adminroles))
                $this->adminroles = '';
            if(is_array($this->adminroles))
                $this->adminroles = implode(',',$this->adminroles);

            return true;
        }
        else
            return false;
    }

}