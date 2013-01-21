<?php

/**
 * --------------------------------------------------
 * no need for validate !!
 *
 */
class AdminBulkActionForm extends CFormModel
{

    public static function createForm(){
        return new self();
    }

    /**
     * @var string the collected ids for bulk actions!
     */
    public $ids;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			// name, email, subject and body are required
			array('ids', 'required'),
		);
	}

	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
			//'ids'=>'ids',
		);
	}
}