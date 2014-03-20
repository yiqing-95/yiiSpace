<?php
/**
 * EMBAttributesBehavior.php
 *
 * The basic attributes behavior attached to the menu and menuitem model.
 *
 * Handles the translation of a menu title or menuitem label, description (hint)
 * and the validation of the attributes
 *
 *
 * @author Joe Blocher <yii@myticket.at>
 * @copyright Copyright &copy; myticket it-solutions gmbh
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package menubuilder
 * @category User Interface
 * @version 1.0.1
 */
class EMBAttributesBehavior extends CActiveRecordBehavior
{
    /**
     * Get the translated label, title or description of a menu/menuitem
     *
     * @param $attribute
     * @param $language
     * @return string
     */
    protected function getI8NAttribute($attribute,$language)
    {
        $owner = $this->getOwner();

        if(!isset($language))
            $language=Yii::app()->language;

        $i8nAttribute = $owner->$attribute;

        if(isset($i8nAttribute[$language]))
            $i8n = $i8nAttribute[$language];

        if(empty($i8n))
            $i8n = isset($i8nAttribute[Yii::app()->language]) // the default language
                ? $i8nAttribute[Yii::app()->language]
                : Yii::t('MenubuilderModule.messages', 'Missing language attribute').': '.$attribute . '[' . $language . ']';

        return $i8n;
    }

    /**
     * Initialize the labels, titles or descriptions attribute of a menu/menuitem as array
     * with the support languages as key
     *
     * @param $attribute
     */
    public function initLanguageAttribute($attribute)
    {
        $values = array();
        foreach (MenubuilderModule::getInstance()->getLanguages() as $language)
            $values[$language] = '';
        $this->getOwner()->$attribute = $values;
    }

    /**
     * Validate a language based array attribute: labels, titles or descriptions
     *
     * @param $attribute
     * @param $attributeName
     * @param $required
     * @param int $maxLength
     */
    public function validateLanguageAttribute($attribute,$attributeName,$required,$maxLength=80)
    {
        $owner = $this->getOwner();
        $attributes = $owner->$attribute;

        if ($required && (empty($attributes) || !is_array($attributes) || empty($attributes[Yii::app()->language])))
        {
            $owner->addError($attribute, Yii::t('MenubuilderModule.messages',$attributeName.' cannot be blank.'));
            return;
        }

        foreach (MenubuilderModule::getInstance()->getLanguages() as $language)
        {
            if (isset($attributes[$language]) && strlen($attributes[$language]) > $maxLength)
            {
                $owner->addError($attribute, Yii::t('MenubuilderModule.messages', $attributeName.' is too long (maximum is '.$maxLength.' characters).'));
                return;
            }
        }
    }

    /*
     * Validate a array attribute
     * Convert from string to an array
     */
    public function validateArrayAttribute($attribute,$allowedItems)
    {
        $owner = $this->getOwner();
        $value = $owner->$attribute;
        if(empty($value))
            return;

        if(!is_array($value))
            $value = explode(',',$value);

        if(empty($allowedItems) && empty($value))
            return;

        $intersect = array_intersect($allowedItems,$value);
        if(empty($intersect))
        {
            $owner->addError($attribute,Yii::t('MenubuilderModule.messages', 'Invalid'.' '.$attribute));
            return;
        }
    }

    /**
     * Validate the menuid of a menu model.
     * Check if it is unique
     *
     * @param $attribute
     */
    public function validateMenuId($attribute)
    {
        $owner = $this->getOwner();
        $value = $owner->$attribute;
        if($owner->getIsNewRecord())
        {
            if(MenubuilderModule::getInstance()->getDataAdapter()->menuidExists($value))
                $owner->addError($attribute,Yii::t('MenubuilderModule.messages', 'Menuid already exists').': '.$value);

        }
    }

    /**
     * Format a timestamp for the db
     *
     * @param $timeStamp
     * @return string
     */
    public function formatPersistDateTime($timeStamp)
    {
        if($this->getOwner() instanceof EMBDbMenu || $this->getOwner() instanceof EMBDbMenuItem)
            return date('Y-m-d H:i:s', $timeStamp);
        else
            return Yii::app()->getDateFormatter()->formatDateTime($timeStamp);
    }

    /**
     * Format for displaying a timestamp (created, modified)
     *
     * @param $dateTime
     * @param string $dateWidth
     * @param string $timeWidth
     * @return string
     */
    public function formatOutputDateTime($dateTime,$dateWidth='medium',$timeWidth='medium')
    {
        if(!is_numeric($dateTime))
            $dateTime = strtotime($dateTime);

        return  Yii::app()->getDateFormatter()->formatDateTime($dateTime);
    }

    /**
     * Assign the default values
     *
     * @param CModelEvent $event
     */
    public function beforeSave($event)
    {
        $owner = $this->getOwner();

        if (!isset($owner->menuid))
            $owner->menuid = '';

        if (!isset($owner->descriptions))
            $owner->descriptions = $this->initLanguageAttribute('descriptions');

        if (!isset($owner->userroles))
            $owner->userroles = '';
        if(is_array($owner->userroles))
            $owner->userroles = implode(',',$owner->userroles);

        if (!isset($owner->scenarios))
            $owner->scenarios = '';
        else
        if(is_array($owner->scenarios))
            $owner->scenarios = implode(',',$owner->scenarios);

        if (!isset($owner->icon))
            $owner->icon = '';

        if($owner->getIsNewRecord())
        {
            $owner->created = $this->formatPersistDateTime(time());
            $owner->createduser = Yii::app()->user->getIsGuest() ? 'guest' : Yii::app()->user->name;
            $owner->modified = null;
            $owner->modifieduser = null;
        }
        else
        {
            $owner->modified = $this->formatPersistDateTime(time());
            $owner->modifieduser = Yii::app()->user->getIsGuest() ? 'guest' : Yii::app()->user->name;
        }
    }

    /**
     * Get the info about created,createduser,modified,modifieduser
     *
     * @param string $dateWidth
     * @param string $timeWidth
     * @return string
     */
    public function getCreatedInfo($dateWidth='medium',$timeWidth='medium')
    {
        $owner = $this->getOwner();
        $formatter = Yii::app()->getDateFormatter();

        if(empty($owner->created))
        {
            $created = '-';
            $createduser = '-';
        }
        else
        {
            $created = $this->formatOutputDateTime($owner->created,$dateWidth,$timeWidth);
            $createduser = $owner->createduser;
        }

        if(empty($owner->modified))
        {
           $modified = '-';
           $modifieduser = '-';
        }
        else
        {
            $modified = $this->formatOutputDateTime($owner->modified,$dateWidth,$timeWidth);
            $modifieduser = $owner->modifieduser;
        }

        $createdText = Yii::t('MenubuilderModule.messages', 'Created');
        $modifiedText = Yii::t('MenubuilderModule.messages', 'Modified');
        //$userText = Yii::t('MenubuilderModule.messages', 'User');

        $format = "<span>$createdText:</span> %s / %s $modifiedText: %s / %s";

        return sprintf($format,$created,$createduser,$modified,$modifieduser);
    }
}