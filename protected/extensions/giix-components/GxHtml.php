<?php

/**
 * GxHtml class file.
 *
 * @author Rodrigo Coelho <rodrigo@giix.org>
 * @link http://giix.org/
 * @copyright Copyright &copy; 2010-2011 Rodrigo Coelho
 * @license http://giix.org/license/ New BSD License
 */

/**
 * GxHtml extends CHtml and provides additional features.
 *
 * @author Rodrigo Coelho <rodrigo@giix.org>
 * @since 1.0
 */
class GxHtml extends CHtml {

	/**
	 * Renders a checkbox list for a model attribute.
	 * #MethodTracker
	 * This method is based on {@link CHtml::activeCheckBoxList}, from version 1.1.7 (r3135). Changes:
	 * <ul>
	 * <li>Added support to HAS_MANY and MANY_MANY relations.</li>
	 * </ul>
	 * Note: Since Yii 1.1.7, $htmlOptions has an option named 'uncheckValue'.
	 * If you set it to different values than the default value (''), you will
	 * need to change the generated code accordingly or use
	 * GxController::getRelatedData with the appropriate 'uncheckValue'.
	 * If you set it to null, you will have to handle it manually.
	 * @see {@link CHtml::activeCheckBoxList} for more information.
	 * @param CModel $model the data model
	 * @param string $attribute the attribute
	 * @param array $data value-label pairs used to generate the check box list.
	 * @param array $htmlOptions addtional HTML options.
	 * @return string the generated check box list
	 */
	public static function activeCheckBoxList($model, $attribute, $data, $htmlOptions = array()) {
		self::resolveNameID($model, $attribute, $htmlOptions);
		$selection = self::selectData(self::resolveValue($model, $attribute)); // #Change: Added support to HAS_MANY and MANY_MANY relations.
		if ($model->hasErrors($attribute))
			self::addErrorCss($htmlOptions);
		$name = $htmlOptions['name'];
		unset($htmlOptions['name']);

		if (array_key_exists('uncheckValue', $htmlOptions)) {
			$uncheck = $htmlOptions['uncheckValue'];
			unset($htmlOptions['uncheckValue']);
		}
		else
			$uncheck = '';

		$hiddenOptions = isset($htmlOptions['id']) ? array('id' => self::ID_PREFIX . $htmlOptions['id']) : array('id' => false);
		$hidden = $uncheck !== null ? self::hiddenField($name, $uncheck, $hiddenOptions) : '';

		return $hidden . self::checkBoxList($name, $selection, $data, $htmlOptions);
	}

	/**
	 * Generates the data suitable for list-based HTML elements.
	 * #MethodTracker (complex changes)
	 * This method is based on {@link CHtml::listData}, from version 1.1.7 (r3135). Changes:
	 * <ul>
	 * <li>This method supports {@link GxActiveRecord::representingColumn()} and {@link GxActiveRecord::toString()}.</li>
	 * <li>This method supports tables with composite primary keys.</li>
	 * </ul>
	 * @see {@link CHtml::listData} for more information.
	 * @param array $models a list of model objects. Starting from version 1.0.3, this parameter
	 * can also be an array of associative arrays (e.g. results of {@link CDbCommand::queryAll}).
	 * @param string $valueField the attribute name for list option values.
	 * Optional. If not specified, the primary key (or keys) will be used.
	 * @param string $textField the attribute name for list option texts
	 * Optional. If not specified, the {@link GxActiveRecord::__toString()} method will be used.
	 * @param string $groupField the attribute name for list option group names. If empty, no group will be generated.
	 * @return array the list data that can be used in {@link dropDownList}, {@link listBox}, etc.
	 */
	public static function listDataEx($models, $valueField = null, $textField = null, $groupField = '') {
		$listData = array();
		if ($groupField === '') {
			foreach ($models as $model) {
				// If $valueField is null, use the primary key.
				if ($valueField === null)
					$value = GxActiveRecord::extractPkValue($model, true);
				else
					$value = self::valueEx($model, $valueField);
				$text = self::valueEx($model, $textField);
				$listData[$value] = $text;
			}
		} else {
			foreach ($models as $model) {
				// If $valueField is null, use the primary key.
				if ($valueField === null)
					$value = GxActiveRecord::extractPkValue($model, true);
				else
					$value = self::valueEx($model, $valueField);
				$group = self::valueEx($model, $groupField);
				$text = self::valueEx($model, $textField);
				$listData[$group][$value] = $text;
			}
		}
		return $listData;
	}

	/**
	 * Generates the select data suitable for list-based HTML elements.
	 * The select data has the attribute or related data as returned
	 * by {@link CHtml::resolveValue}.
	 * If the select data comes from a MANY_MANY or a HAS_MANY related
	 * attribute (is a model or an array of models), it is transformed
	 * to a string or an array of strings with the selected primary keys.
	 * @param mixed $value the value of the attribute as returned by
	 * {@link CHtml::resolveValue}.
	 * @return mixed the select data.
	 */
	public static function selectData($value) {
		// If $value is a model or an array of models, turn it into
		// a string or an array of strings with the pk values.
		if ((is_object($value) && is_subclass_of($value, 'GxActiveRecord')) ||
				(is_array($value) && !empty($value) && is_object($value[0]) && is_subclass_of($value[0], 'GxActiveRecord')))
			return GxActiveRecord::extractPkValue($value, true);
		else
			return $value;
	}

	/**
	 * Evaluates the value of the specified attribute for the given model.
	 * #MethodTracker
	 * This method improves {@link CHtml::value}, from version 1.1.7 (r3135). Changes:
	 * <ul>
	 * <li>This method supports {@link GxActiveRecord::representingColumn()} and {@link GxActiveRecord::toString()}.</li>
	 * </ul>
	 * @see {@link CHtml::value} for more information.
	 * @param mixed $model the model. This can be either an object or an array.
	 * @param string $attribute the attribute name (use dot to concatenate multiple attributes).
	 * Optional. If not specified, the {@link GxActiveRecord::__toString()} method will be used.
	 * In this case, the fist parameter ($model) can not be an array, it must be an instance of GxActiveRecord.
	 * @param mixed $defaultValue the default value to return when the attribute does not exist
	 * @return mixed the attribute value
	 */
	public static function valueEx($model, $attribute = null, $defaultValue = null) {
		if ($attribute === null) {
			if (is_object($model) && is_subclass_of($model, 'GxActiveRecord'))
				return $model->__toString();
			else
				return $defaultValue;
		} else {
			return parent::value($model, $attribute, $defaultValue);
		}
	}

	/**
	 * Encodes special characters into HTML entities.
	 * #MethodTracker
	 * This method improves {@link CHtml::encode}, from version 1.1.7 (r3135). Changes:
	 * <ul>
	 * <li>This method supports encoding strings in arrays and selective encoding of keys and/or values.</li>
	 * </ul>
	 * @see {@link CHtml::encode} for more information.
	 * @param string|array $data data to be encoded
	 * @param boolean $encodeKeys whether to encode array keys
	 * @param boolean $encodeValues whether to encode array values
	 * @param boolean $recursive whether to encode data in nested arrays
	 * @return string|array the encoded data
	 */
	public static function encodeEx($data, $encodeKeys = false, $encodeValues = false, $recursive = true) {
		if (is_array($data)) {
			$encodedArray = array();
			foreach ($data as $key => $value) {
				$encodedKey = ($encodeKeys && is_string($key)) ? parent::encode($key) : $key;
				if (is_array($value))
					if ($recursive)
						$encodedValue = self::encodeEx($value, $encodeKeys, $encodeValues, $recursive);
					else
						$encodedValue = $value;
				else
					$encodedValue = ($encodeValues && is_string($value)) ? parent::encode($value) : $value;
				$encodedArray[$encodedKey] = $encodedValue;
			}
			return $encodedArray;
		} else if (is_string($data))
			return parent::encode($data);
		else
			throw new InvalidArgumentException(Yii::t('giix', 'The argument "data" must be of type string or array.'));
	}

}