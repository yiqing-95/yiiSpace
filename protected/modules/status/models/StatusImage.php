<?php


class StatusImage extends Status
{
    /**
     * @var string
     */
    public $image;


    /**
     * @var string
     * @overwrite
     */
    public $type = 'image';
    /**
     * @param string $scenario
     */
    public function __construct($scenario='insert'){
        parent::__construct($scenario);
        parent::setTypeReference('image');
    }


    /**
     * Process an image upload and set the image
     * @param String $postfield the $_POST field the image was uploaded through
     * @return boolean
     */
    public function processImage( $postfield )
    {
        $uploadStorage = YsUploadStorage::instance() ;
        $uploadDir = $uploadStorage->getUploadDir();

        $im = new Imagemanager();
        $prefix = time() . '_';
        if( $im->loadFromPost($postfield, "{$uploadDir}/", $prefix ) )
        {
            $im->resizeScaleWidth( 150 );
            $saveToFilePath = "{$uploadDir}/" . $im->getName() ;
            $im->save( $saveToFilePath);
            $this->image =  $uploadStorage->realPath2url($saveToFilePath) ; // $im->getName();
            return true;
        }
        else
        {
            return false;
        }

    }

    /**
     * Save the image status
     */
    public function save($runValidation=true,$attributes=null)
    {
        // save the parent object and thus the status table
       $saveResult = parent::save($runValidation=true,$attributes=null);
        // grab the newly inserted status ID
        $id = $this->id;
        // insert into the images status table, using the same ID
        $extended = array();
        $extended['id'] = $id;
        $extended['image'] = $this->image;
       //$this->registry->getObject('db')->insertRecords( 'statuses_images', $extended );
        $easyQuery = EasyQuery::instance('status_image')->insert($extended);

        return $saveResult;
    }
}