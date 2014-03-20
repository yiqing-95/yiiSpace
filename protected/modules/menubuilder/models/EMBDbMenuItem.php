<?php

/**
 * This is the model class for table "emb_menuitem".
 *
 * The followings are the available columns in table 'emb_menuitem':
 * @property integer $itemid
 * @property string $menuid
 * @property string $url
 * @property integer $descriptionashint
 * @property integer $active
 * @property integer $visible
 * @property string $itemOptions
 * @property string $submenuOptions
 * @property string $linkOptions
 * @property string $template
 * @property string $target
 * @property string $icon
 * @property string $scenarios
 * @property string $userroles
 * @property string $createduser
 * @property string $modifieduser
 * @property string $created
 * @property string $modified
 */
class EMBDbMenuItem extends CActiveRecord
{
    public $labels;
    public $descriptions;

    /**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return EMBDbMenuItem the static model class
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
     * Initializes this model.
     * You may override this method to provide code that is needed to initialize the model (e.g. setting
     * initial property values.)
     */
    public function init()
    {
        $this->active=0;
        $this->visible = 1;
        $this->descriptionashint=1;
    }

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'emb_menuitem';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('descriptionashint, active, visible', 'boolean'),
			array('menuid, target, icon, createduser, modifieduser', 'length', 'max'=>40),
			array('url, itemOptions, submenuOptions, linkOptions, ajaxOptions, template', 'length', 'max'=>300),
            array('scenarios', 'validateScenarios'),
            array('userroles', 'validateUserRoles'),
            array('labels', 'validateLabels'),
            array('descriptions', 'validateDescriptions'),
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
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
        return array(
            'labels' => Yii::t('MenubuilderModule.messages', 'Labels'),
            'descriptions'=>Yii::t('MenubuilderModule.messages','Descriptions'),
            'descriptionashint'=>Yii::t('MenubuilderModule.messages','Description as hint'),
            'visible' => Yii::t('MenubuilderModule.messages', 'Visible'),
            'active' => Yii::t('MenubuilderModule.messages', 'Active'),
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

    public function save($runValidation=true,$attributes=null)
    {
        if($attributes===null)
            $attributes=array(
                'itemid',
                'menuid',
                'url',
                'descriptionashint',
                'active',
                'visible',
                'itemOptions',
                'submenuOptions',
                'linkOptions',
                'ajaxOptions',
                'template',
                'target',
                'icon',
                'scenarios',
                'userroles',
                'created',
                'createduser',
                'modified',
                'modifieduser',
            );

        return parent::save($runValidation,$attributes);
    }

    /**
     * Remove the itemid from all menus->nestedconfig
     *
     * @return bool
     */
    protected function afterDelete()
    {
        parent::afterDelete();
        MenubuilderModule::getInstance()->getDataAdapter()->removeItemFromMenus($this->itemid);
        $sql = sprintf("DELETE FROM emb_language WHERE itemid=%d",$this->itemid);
        Yii::app()->db->createCommand($sql)->execute();
        return true;
    }

    protected function afterSave()
    {
        parent::afterSave();

        $db=Yii::app()->db;

        if(!empty($this->labels))
        {
            foreach($this->labels as $language=>$label)
            {
                $sql = sprintf("REPLACE INTO emb_language (menuid,itemid,type,language,text) VALUES('%s',%d,%d,'%s','%s')",
                    '',$this->itemid,EMBConst::DB_LANGUAGETYPE_MENUITEMLABEL,$language,$label);
                $db->createCommand($sql)->execute();
            }
        }

        if(!empty($this->descriptions))
        {
            foreach($this->descriptions as $language=>$description)
            {
                $sql = sprintf("REPLACE INTO emb_language (menuid,itemid,type,language,text) VALUES('%s',%d,%d,'%s','%s')",
                    '',$this->itemid,EMBConst::DB_LANGUAGETYPE_MENUITEMDESCRIPTION,$language,$description);
                $db->createCommand($sql)->execute();
            }
        }

        MenubuilderModule::getInstance()->getDataAdapter()->resetLanguages();

    }


    protected function beforeSave()
    {
        if (parent::beforeSave())
        {
            if (empty($this->url))
                $this->url = '#';

            if (!isset($this->active))
                $this->active = 0;

            if (!isset($this->visible))
                $this->visible = 0;

            if (!isset($this->descriptionashint))
                $this->descriptionashint = 1;

            if (!isset($this->labels))
               $this->initLanguageAttribute('labels');

            if (!isset($this->descriptions))
                $this->descriptions = $this->initLanguageAttribute('descriptions');

            return true;
        } else
            return false;
    }

    public function afterFind()
    {
        parent::afterFind();

        $dataAdapter = MenubuilderModule::getInstance()->getDataAdapter();

        $labels=$dataAdapter->getLanguageAttributes('menuitem',$this->itemid,EMBConst::DB_LANGUAGETYPE_MENUITEMLABEL);
        if(!empty($labels))
            $this->labels = $labels;
        else
            $this->initLanguageAttribute('labels');

        $descriptions=$dataAdapter->getLanguageAttributes('menuitem',$this->itemid,EMBConst::DB_LANGUAGETYPE_MENUITEMDESCRIPTION);
        if(!empty($descriptions))
            $this->descriptions = $descriptions;
        else
            $this->initLanguageAttribute('descriptions');
    }

}