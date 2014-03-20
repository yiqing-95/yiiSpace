<?php


class StatusLink extends Status
{
    public  $url;
    public  $description;

    /**
     * @var string
     * @overwrite
     */
    public $type = 'link';

    /**
     * @param string $scenario
     */
    public function __construct($scenario = 'insert')
    {

        parent::__construct($scenario);
    }

    /**
     * Set the URL
     * @param String $url
     * @return void
     */
    public function setURL( $url )
    {
        $this->url = $url;
    }

    /**
     * Set the description of the link
     * @param String $description
     * @return void
     */
    public function setDescription( $description )
    {
        $this->description = $description;
    }



    public function save($runValidation=true,$attributes=null)
    {
        // save the parent object and thus the status table
        $saveResult = parent::save($runValidation=true,$attributes=null);
        // grab the newly inserted status ID
        $id = $this->id;
        // insert into the video status table, using the same ID
        $extended = array();
        $extended['id'] = $id;
        $extended['url'] = $this->url;
        $extended['description'] = $this->description;

        $easyQuery = EasyQuery::instance('status_link')->insert($extended);

        return $saveResult;
    }
}