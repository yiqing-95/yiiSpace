<?php
/**
 * EImageValidator validates image with fix width and height
 *
 * @author ituri
 * @version 1.0
 */
class EImageValidator extends CValidator {

    /**
     * @var boolean the attribute requires a file to be uploaded or not.
     */
    public $allowEmpty = false;
    
    /**
     * @var int width of the image
     */
    public $width;

    /**
     * @var int height of the image
     */
    public $height;

    /**
     * @var string image dimension error message
     */
    public $dimensionError;

    /**
     * Validates the attribute of the object.
     * If there is any error, the error message is added to the object.
     * @param CModel $object the object being validated
     * @param string $attribute the attribute being validated
     */
    protected function validateAttribute($object, $attribute) {

        $file = $object->$attribute;

        if (!$file instanceof CUploadedFile) {
            $file = CUploadedFile::getInstance($object, $attribute);

            if (null === $file)
                return $this->emptyAttribute($object, $attribute);
        }

        $size = file_exists($file->tempName) ? getimagesize($file->tempName) : false;
        
        if (isset($this->width, $this->height) && $size !==false) {
            if ($size[0] != $this->width && $size[1] != $this->height) {
                $message = $this->dimensionError ? $this->dimensionError : Yii::t('yii', 'Image dimension should be {width}x{height}');
                $this->addError($object, $attribute, $message, array('{width}' => $this->width, '{height}' => $this->height));
                return;
            }
        }
    }
    
    /**
     * @param CModel $object the object being validated
     * @param string $attribute the attribute being validated
     * 
     * @return void
     */
    protected function emptyAttribute($object, $attribute) {
        if (!$this->allowEmpty) {
            $message = $this->message !== null ? $this->message : Yii::t('yii', '{attribute} cannot be blank.');
            $this->addError($object, $attribute, $message);
            return;
        }
    }

}

?>