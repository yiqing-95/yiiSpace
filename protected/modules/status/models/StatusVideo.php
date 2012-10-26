<?php

class StatusVideo extends Status
{
    public  $video_id;


    /**
     * @param string $scenario
     */
    public function __construct($scenario = 'insert')
    {
        parent::setTypeReference('video');

        parent::__construct($scenario);
    }

    public function setVideoId($vid)
    {
        $this->video_id = $vid;
    }

    /**
     * @param $url
     * we have a useful setter method that parses the YouTube URL, extracts the
     * video ID from it, and sets the class variable accordingly. In this case, if no video ID
     * is found in the URL, it uses a clip from the TV series "Dinosaurs" as a default video
     */
    public function setVideoIdFromURL($url)
    {
        $data = array();
        parse_str(parse_url($url, PHP_URL_QUERY), $data);
        $this->video_id = $data['v'];
    }

    /**
     * Save the video status
     * @param bool $runValidation
     * @param null $attributes
     * @return bool
     */
    public function save($runValidation=true,$attributes=null)
    {
        // save the parent object and thus the status table
        $saveResult = parent::save($runValidation=true,$attributes=null);
        // grab the newly inserted status ID
        $id = $this->id;
        // insert into the video status table, using the same ID
        $extended = array();
        $extended['id'] = $id;
        $extended['video_id'] = $this->video_id;

        $easyQuery = EasyQuery::instance('status_video')->insert($extended);

        return $saveResult;
    }


}