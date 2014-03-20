<?php

/**
 * This is the model class for table "emb_menu".
 *
 * The followings are the available columns in table 'emb_menu':
 * @property string $menuid
 * @property string $nestedconfig
 * @property integer $visible
 * @property integer $locked
 * @property integer $sortposition
 * @property integer $maxdepth
 * @property integer $descriptionashint
 * @property string $icon
 * @property string $scenarios
 * @property string $userroles
 * @property string $adminroles
 * @property string $created
 * @property string $createduser
 * @property string $modified
 * @property string $modifieduser
 */
class EMBDbMenu extends CActiveRecord
{
	public $titles;
	public $descriptions;

    /**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return EMBDbMenu the static model class
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


    /**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'emb_menu';
	}

    protected function afterConstruct()
    {
        parent::afterConstruct();
        $this->initLanguageAttribute('titles'); //behavior method
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
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('menuid', 'required'),
            array('menuid', 'validateMenuId'),
            array('titles', 'validateTitles'),
            array('descriptions', 'validateDescriptions'),
			array('sortposition, maxdepth', 'numerical', 'integerOnly'=>true),
			array('visible, locked, descriptionashint', 'boolean'),
			array('menuid, icon, createduser, modifieduser', 'length', 'max'=>40),
			array('nestedconfig', 'length', 'max'=>5000),
            array('scenarios', 'validateScenarios'),
            array('userroles', 'validateUserRoles'),
            array('adminroles', 'validateAdminRoles'),
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
	 * @return array customized attribute labels (name=>label)
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


    public function save($runValidation=true,$attributes=null)
    {
        if($attributes===null)
            $attributes=array(
                'menuid',
                'nestedconfig',
                'visible',
                'locked',
                'sortposition',
                'maxdepth',
                'descriptionashint',
                'scenarios',
                'userroles',
                'adminroles',
                'url',
                'icon',
                'created',
                'createduser',
                'modified',
                'modifieduser',
            );

        return parent::save($runValidation,$attributes);
    }

    protected function afterDelete()
    {
        parent::afterDelete();
        //delete the items restricted to this->menuid
        MenubuilderModule::getInstance()->getDataAdapter()->deleteMenuItemsByMenuId($this->menuid);
        //delete the language records
        $sql = sprintf("DELETE FROM emb_language WHERE menuid=%s",$this->menuid);
        Yii::app()->db->createCommand($sql)->execute();
    }


    protected function afterSave()
    {
        parent::afterSave();

        $db=Yii::app()->db;

        if(!empty($this->titles))
        {
            foreach($this->titles as $language=>$title)
            {
                $sql = sprintf("REPLACE INTO emb_language (menuid,itemid,type,language,text) VALUES('%s',%d,%d,'%s','%s')",
                    $this->menuid,0,EMBConst::DB_LANGUAGETYPE_MENUTITLE,$language,$title);
                $db->createCommand($sql)->execute();
            }
        }

        if(!empty($this->descriptions))
        {
            foreach($this->descriptions as $language=>$description)
            {
                $sql = sprintf("REPLACE INTO emb_language (menuid,itemid,type,language,text) VALUES('%s',%d,%d,'%s','%s')",
                    $this->menuid,0,EMBConst::DB_LANGUAGETYPE_MENUDESCRIPTION,$language,$description);
                $db->createCommand($sql)->execute();
            }
        }

        MenubuilderModule::getInstance()->getDataAdapter()->resetLanguages();

    }

    protected function beforeSave()
    {
        if(parent::beforeSave())
        {
            if(empty($this->menuid))
                return false;

            if(empty($this->nestedconfig))
                $this->nestedconfig='';

            if (!isset($this->visible))
                $this->visible = 0;

            if (!isset($this->descriptionashint))
                $this->descriptionashint = 1;

            if (!isset($this->titles))
               $this->initLanguageAttribute('titles');

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


    public function afterFind()
    {
        parent::afterFind();

        $dataAdapter = MenubuilderModule::getInstance()->getDataAdapter();

        $titles=$dataAdapter->getLanguageAttributes('menu',$this->menuid,EMBConst::DB_LANGUAGETYPE_MENUTITLE);
        if(!empty($titles))
            $this->titles = $titles;

        $descriptions=$dataAdapter->getLanguageAttributes('menu',$this->menuid,EMBConst::DB_LANGUAGETYPE_MENUDESCRIPTION);
        if(!empty($descriptions))
            $this->descriptions = $descriptions;

    }


}