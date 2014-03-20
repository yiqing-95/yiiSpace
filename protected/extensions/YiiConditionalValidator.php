<?php

/**
 * YiiConditionalValidator
 * If-then validation rules on Yii Framework using core validators.
 *
 * Example of rule in a hipothetic invoice checking if id_payment_method is Money
 * or Card, if so, then dueDate is required *and* must be numerical:
 *
 * array('dueDate', 'ext.YiiConditionalValidator',
 *      'if'=>array(
 *          array('id_payment_method', 'in', 'range'=>array(PaymentMethod::MONEY, PaymentMethod::CARD), 'allowEmpty'=>false)
 *      ),
 *      'then'=>array(
 *          array('dueDate', 'required', [other params]),
 *          array('dueDate', 'numerical', [other params]),
 *      ),
 * ),
 *
 * @author Sidney Lins <solucoes@wmaior.com>
 * @copyright Copyright &copy; 2011 Sidney Lins
 * @version 1.0.0
 * @license New BSD Licence
 */
class YiiConditionalValidator extends CValidator
{

    public $if = array();
    public $then = array();

    public function __construct()
    {
        $this->analyseSupliedRules();
    }

    protected function validateAttribute($object, $attribute)
    {
        $noErrorsOnIfRules = $this->runValidators($this->prepareValidatorsData($object, $this->if), true, true);

        if ($noErrorsOnIfRules) {
            $this->runValidators($this->prepareValidatorsData($object, $this->then));
        }
    }

    /**
     * Create a data array with params prepared for use into runValidators() method.
     *
     * @param CModel $object The original object that is being conditionally validated.
     * @param array $rules The set of rules defined in "if" or "then" rules.
     * @return array A dimensional array containing prepared data for each validator
     * to be created and runned.
     */
    protected function prepareValidatorsData($object, array $rules)
    {
        $preparedValidatorsData = array();

        foreach ($rules as $ruleKey => $rule) {
            $validatorData = array();

            /**
             * @todo Implement way of define "if" rules using or not the attribute
             * name. When not using, use original attributeName list.
             */
            $fetchedObjectAndAttribute   = $this->fetchRealObjectAndAttribute($object, array_shift($rule));
            $validatorData['object']     = $fetchedObjectAndAttribute['object'];
            $validatorData['attribute']  = $fetchedObjectAndAttribute['attribute'];
            $validatorData['validation'] = array_shift($rule);

            foreach ($rule as $key => $ruleParams) {
                $validatorData['params'][$key] = $ruleParams;
            }

            $validatorData['params'] = isset($validatorData['params']) ? $validatorData['params'] : array();
            $preparedValidatorsData[$ruleKey] = $validatorData;
        }

        return $preparedValidatorsData;
    }

    /**
     * Fetches the original or related object defined in a validation rule. If this
     * attribute name uses dot notation it will try to get the related object, otherwise
     * the original object is used.
     *
     * Ex:
     *
     * If original object is an instance of Post and the attribute name is 'author.name',
     * this will fetch the object $author (related to Post) and its attribute 'name'.
     *
     * @param CActiveRecord|CFormModel $originalObject Original object
     * @param string $attributeName Attribute name defined in validation (may use dot notation)
     * @return array An array with original or related object and attribute name in format of
     * ['object' => $instanceOfFetchedObject, 'attribute' => $attributeName].
     * @throws CException If a relation not exists or if related object is null.
     */
    protected function fetchRealObjectAndAttribute($originalObject, $attributeName)
    {
        $realObjectAndAttribute = array();
        $realObjectAndAttribute['object']    = $originalObject;
        $realObjectAndAttribute['attribute'] = $attributeName;

        if (strpos($attributeName, '.') !== false) {
            $relations         = explode('.', $attributeName);
            $realObject        = $originalObject;
            $realAttributeName = array_pop($relations);

            foreach ($relations as $relation) {
                if ($realObject instanceof CActiveRecord) {
                    if ($realObject->hasRelated($relation)) {
                        $realObject = $realObject->getRelated($relation);
                    } else {
                        throw new CException(
                                get_class($realObject) . " has not a relation named \"$relation\". Check the YiiConditionalValidator rule that is using the attribute name \"$attributeName\"."
                        );
                    }
                }
            }

            if (is_null($realObject)) {
                throw new CException(
                        "The relation \"$relation\" of " . get_class($realObject) . " is returning a null value. Check the YiiConditionalValidator rule that is using the attribute name \"$attributeName\"."
                );
            }

            $realObjectAndAttribute['object']    = $realObject;
            $realObjectAndAttribute['attribute'] = $realAttributeName;
        }

        return $realObjectAndAttribute;
    }

    /**
     * Analyses and validates the rules suplied.
     *
     * @throws CException On invalid sintaxe or error.
     */
    protected function analyseSupliedRules()
    {
        if (!is_array($this->if)) {
            throw new CException('Invalid argument "validations" for YiiConditionalValidator. Please, suply an array().');
        }

        if (!is_array($this->then)) {
            throw new CException('Invalid argument "dependentValidations" for YiiConditionalValidator. Please, suply an array().');
        }
    }

    /**
     * Creates and executes each validator based on $validatorsData.
     *
     * @param array $validatorsData Must contain 'validation', 'object', 'attribute'
     * and 'params' for each validator.
     * @param boolean $discardErrorsAfterCheck Useful to allow discard validation
     * errors in "if" rules but not in "then" rules.
     * @param boolean   $returnFalseOnError Useful to allow return false on error
     * when validating "if" rules but continue checking on error when validating
     * "then" rules.
     *
     * @return boolean If $returnFalseOnError is true, returns false on error, otherwise
     * returns ever true;
     */
    protected function runValidators(array $validatorsData, $discardErrorsAfterCheck = false, $returnFalseOnError = false)
    {
        foreach ($validatorsData as $preparedData) {
            $validation = $preparedData['validation'];
            $object     = $preparedData['object'];
            $attribute  = $preparedData['attribute'];
            $params     = $preparedData['params'];

            $errorsBackup = $object->getErrors();
            $object->clearErrors();

            $validator = CValidator::createValidator($validation, $object, $attribute, $params);
            $validator->validate($object, $attribute);

            if ($object->hasErrors($attribute)) {
                $newErrors = $object->getErrors();
                $object->clearErrors();

                if (!$discardErrorsAfterCheck) {
                    $object->addErrors($newErrors);
                }

                if ($returnFalseOnError) {
                    $object->addErrors($errorsBackup);
                    $errorsBackup = null;
                    return false;
                }
            } else {
                $object->addErrors($errorsBackup);
                $errorsBackup = null;
            }
        }
        return true;
    }

}

?>
