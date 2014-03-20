<?php
/**
 * Created by JetBrains PhpStorm.
 * User: joe
 * Date: 11.06.13
 * Time: 08:55
 * To change this template use File | Settings | File Templates.
 */

class EMBMongoDbMenu extends EMongoDocument
{
    public $_id;
    public $menuid;
    public $titles=array();
    public $descriptions=array();
    public $descriptionashint;
    public $visible;
    public $locked;
    public $sortposition;
    public $maxdepth;
    public $icon;
    public $nestedconfig;
    public $userroles;
    public $scenarios;
    public $adminroles;
    public $created;
    public $createduser;
    public $modified;
    public $modifieduser;


    /**
     * Returns the static model of the specified AR class.
     * @return User the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }


    function collectionName(){
        return 'emb_menu';
    }

    function getCollectionName() //YiiMongoDbSuite
    {
        return $this->collectionName();
    }

    protected function afterConstruct()
    {
        parent::afterConstruct();
        $this->initLanguageAttribute('titles'); //behavior method
        $this->initLanguageAttribute('descriptions'); //behavior method
    }




    public function behaviors()
    {
        return array(
            'attributesBehavior'=>'EMBAttributesBehavior',
        );
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
