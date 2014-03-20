<?php
/**
 * EMBSimulateForm.php
 *
 * The CFormModel for the simulate section in the admin form
 *
 * @author Joe Blocher <yii@myticket.at>
 * @copyright Copyright &copy; myticket it-solutions gmbh
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package menubuilder
 * @category User Interface
 * @version 1.0
 */

class EMBSimulateForm extends CFormModel
{
    public $scenarios;
    public $userroles;


    public function scenariosAsString()
    {
        return is_array($this->scenarios) ? implode(',',$this->scenarios) : $this->scenarios;
    }


    public function userrolesAsString()
    {
        return is_array($this->userroles) ? implode(',',$this->userroles) : $this->userroles;
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('userroles,scenarios', 'checkArray'),
        );
    }

    public function checkArray($attribute)
    {
        $value = $this->$attribute;
        if(!empty($value) && !is_array($value))
            $this->addError($attribute,ucfirst($attribute).' '.Yii::t('MenubuilderModule.messages', 'must be an array'));
    }


}