<?php

Yii::import('status.models._base.BaseStatus');

class Status extends BaseStatus
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function relations()
    {
        return array(
            'owner'=>array(self::BELONGS_TO, 'User', 'creator'),
            'image'=>array(self::HAS_ONE, 'StatusImage', 'id'),
            'link'=>array(self::HAS_ONE, 'StatusLink', 'id'),
            'video'=>array(self::HAS_ONE, 'StatusVideo', 'id'),
        );
    }

    protected  $typeReference = 'update';

    /**
     * Generate the type of status based of the type reference
     * @return void
     */
    public function generateType()
    {
        $sql = "SELECT id FROM status_type WHERE
        type_reference='{$this->typeReference}'";
        $this->type = $this->dbConnection->createCommand($sql)->queryScalar();
       // die(__METHOD__. $this->typeReference);
    }

    /**
     * Set the type reference, so we can get the type ID from the database
     * @param String $typeReference the reference of the type
     * @return void
     */
    public function setTypeReference( $typeReference )
    {
        $this->typeReference = $typeReference;
    }

    /**
     * @static
     * @param $user
     * @param array $sqlDataProviderConfig
     * @return CSqlDataProvider
     * --------------------------------------------------------------------
     * sql语句中出现连接两次user_profile 现象 这个是应对 a用户到b用户的主页去发言去了
     *
     * ---------------------------------------------------------------------
     */
    static  public function listRecentStatuses( $user = null , $sqlDataProviderConfig = array() ){
       /*
        $sql = "SELECT t.type_reference, t.type_name, s.*, pa.first_name as poster_name, i.image, v.video_id, l.url, l.description
         FROM status_type t, user_profile p, user_profile pa, status s
         LEFT JOIN status_image i ON s.id = i.id
         LEFT JOIN status_video v ON s.id = v.id
         LEFT JOIN status_link l ON s.id = l.id
          WHERE t.id = s.type AND p.user_id = s.profile AND pa.user_id = s.creator AND p.user_id={$user}";
         // ORDER BY s.ID DESC  LIMIT 20";
       */

        $cmd = Yii::app()->db->createCommand();
        $cmd->select("t.type_reference, t.type_name, s.*, pa.first_name as poster_name, i.image, v.video_id, l.url, l.description")
            ->from("status_type t, user_profile p, user_profile pa, status s")
            ->leftJoin('status_image i','s.id = i.id')
            ->leftJoin('status_video v','s.id = v.id')
            ->leftJoin('status_link l','s.id = l.id')
            ->where(" t.id = s.type AND p.user_id = s.profile AND pa.user_id = s.creator ".(empty($user)? '': " AND p.user_id={$user}"));
        if(empty($user)){
             $cmd->select("t.type_reference, t.type_name, s.*, pa.first_name as poster_name, i.image, v.video_id, l.url, l.description,u.username,pa.photo as avatar");
            $cmd->join('user u','s.creator = u.id ');
        }
        $sql = $cmd->text;

        $config = array();
        $config['totalItemCount'] = YiiUtil::countBySql($sql);
        $config['keyField'] = 'id';
        $config['sort'] = array(
            'defaultOrder'=>'id DESC',
            'attributes'=>array(
                'id'
            )
        );
        $sqlDataProviderConfig = CMap::mergeArray($config, $sqlDataProviderConfig);
        return new CSqlDataProvider($sql, $sqlDataProviderConfig);
    }


    /**
     * Build a users stream
     * @param int $user the user whose network we want to stream
     * @param array $sqlDataProviderConfig
     *  param int $offset - useful if we add in an AJAX based "view more statuses" feature
     * @return \CSqlDataProvider
     */
  static   public function buildStream( $user, $sqlDataProviderConfig = array()  )
    {
        // prepare an array
        $network = array();
        // use the relationships model to get relationships
        $network = Relationship::getNetwork( $user );
        // Add a zero element; so if network is empty the IN part of the query won't fail
        $network[] = 0;
        $network = implode( ',', $network );
        // query the statuses table
        $sql = "SELECT t.type_reference, t.type_name, s.*, UNIX_TIMESTAMP(s.created) as timestamp,
               p.first_name as poster_name, r.first_name as profile_name
        FROM status s, status_type t, user_profile p, user_profile r
         WHERE t.id = s.type AND p.user_id = s.creator AND r.user_id = s.profile
         AND ( p.user_id={$user} OR r.user_id={$user} OR ( p.user_id IN ({$network}) AND r.user_id IN ({$network}) ) )";
         // ORDER BY s.ID DESC LIMIT {$offset}, 20";

        $config = array();
        $config['totalItemCount'] = YiiUtil::countBySql($sql);
        $config['keyField'] = 'id';
        $config['sort'] = array(
            'defaultOrder'=>'id DESC',
            'attributes'=>array(
                'id'
            )
        );
        $sqlDataProviderConfig = CMap::mergeArray($config, $sqlDataProviderConfig);
        return new CSqlDataProvider($sql, $sqlDataProviderConfig);
    }
}