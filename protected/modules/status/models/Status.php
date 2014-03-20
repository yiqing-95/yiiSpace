<?php

Yii::import('status.models._base.BaseStatus');

class Status extends BaseStatus
{

    public $type = 'update';

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function relations()
    {
        return array(
            'owner' => array(self::BELONGS_TO, 'User', 'creator'),
            'image' => array(self::HAS_ONE, 'StatusImage', 'id'),
            'link' => array(self::HAS_ONE, 'StatusLink', 'id'),
            'video' => array(self::HAS_ONE, 'StatusVideo', 'id'),
        );
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
    static public function listRecentStatuses($user = null, $sqlDataProviderConfig = array())
    {
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
        $cmd->select(" up.username ,up.icon_uri,
                    s.*,ua.username as poster_name,
                       i.image,
                        v.video_id,
                         l.url, l.description")
            ->from(" user ua , user up, status s")
            ->leftJoin('status_image i', 's.id = i.id')
            ->leftJoin('status_video v', 's.id = v.id')
            ->leftJoin('status_link l', 's.id = l.id')
            ->where("  ua.id = s.creator " . (empty($user) ? '' : " AND up.id={$user} AND s.profile = up.id"));
        if (empty($user)) {
            $cmd->select("u.username , u.icon_uri,
             s.*, pa.first_name as poster_name,
              i.image,
               v.video_id,
               l.url, l.description,
               u.username,
               pa.photo as avatar");
            $cmd->join('user u', 's.creator = u.id ');
        }
        $sql = $cmd->text;

        $config = array();
        $config['totalItemCount'] = YiiUtil::countBySql($sql);
        $config['keyField'] = 'id';
        $config['sort'] = array(
            'defaultOrder' => 'id DESC',
            'attributes' => array(
                'id'
            )
        );
        $sqlDataProviderConfig = CMap::mergeArray($config, $sqlDataProviderConfig);
        return new CSqlDataProvider($sql, $sqlDataProviderConfig);
    }


    /**
     * @param $user
     * @param array $sqlDataProviderConfig
     * @return CSqlDataProvider|null
     */
    static public function listFriendsStatuses($user, $sqlDataProviderConfig = array())
    {

        $friendsIdsCondition = UserHelper::getFriendIdsCondition($user);

        /*
        if(empty($friendsIds)){
            return null;
        }

         * sql字句条件
        $friendsIdsSql = "
        SELECT  ub.id
                FROM
                relationship r,
                relationship_type t,
                user ua, user ub
                 WHERE t.id = r.type AND  ua.id = r.user_a
                  AND ub.id = r.user_b
                  AND  r.accepted = 1
                  AND r.user_a = {$user}
        ";
        */


        $cmd = Yii::app()->db->createCommand();
        $cmd->select("
        u.username , u.icon_uri
        , t.type_name
        , s.*
        , i.image
        , v.video_id
        , l.url, l.description
        "
        )
            ->from("status_type t, status s")
            ->join('user u', 's.creator = u.id ')
            ->leftJoin('status_image i', 's.id = i.id')
            ->leftJoin('status_video v', 's.id = v.id')
            ->leftJoin('status_link l', 's.id = l.id');

        /*
        if (empty($friendsIds)) {
            $where = '1=2'; // 构造一个假条件
        } else {
            $friendsIds = implode(',', $friendsIds);
            $where = " t.id = s.type
            AND u.id IN ({$friendsIds}) ";
        }
        */
        $where = " t.id = s.type
            AND u.id IN ({$friendsIdsCondition}) ";

        $cmd->where($where);
        $sql = $cmd->text;
       // die($sql);

        $config = array();
        $config['totalItemCount'] = YiiUtil::countBySql($sql);
        $config['keyField'] = 'id';
        $config['sort'] = array(
            'defaultOrder' => 'id DESC',
            'attributes' => array(
                'id'
            )
        );
        $sqlDataProviderConfig = CMap::mergeArray($config, $sqlDataProviderConfig);
        return new CSqlDataProvider($sql, $sqlDataProviderConfig);
    }

    /**
     * @param $exceptUser 除了那个用户 一般是自己
     * @param array $sqlDataProviderConfig
     * @return CSqlDataProvider
     * 列举所有人的状态
     */
    static public function listAllStatus($exceptUser=null, $sqlDataProviderConfig = array())
    {

        $cmd = Yii::app()->db->createCommand();
        $cmd->select("
        u.username , u.icon_uri
        , s.*
        , i.image
        , v.video_id
        , l.url, l.description
        "
        )
            ->from("status s")
            ->join('user u', 's.creator = u.id ')
            ->leftJoin('status_image i', 's.id = i.id')
            ->leftJoin('status_video v', 's.id = v.id')
            ->leftJoin('status_link l', 's.id = l.id');

        if($exceptUser !== null){
            $cmd->where('u.id <> '.$exceptUser);
        }

        /*
            $where = '1=2'; // 构造一个限制查询太多条记录的条件
        比如时间 这个值可以来自配置
        $cmd->where($where);
        */
        $sql = $cmd->text;
       // die($cmd->text);

        $totalItemCount = YiiUtil::countBySql($sql);

        $config = array();
        /**
         * 不要让他查太多了数据量越大 越往后则查询越慢
         *
         *
         */
        $config['totalItemCount'] = $totalItemCount > 10000 ? 10000 : $totalItemCount;
        $config['keyField'] = 'id';
        $config['sort'] = array(
            'defaultOrder' => 'id DESC',
            'attributes' => array(
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
    static public function buildStream($user, $sqlDataProviderConfig = array())
    {
        // prepare an array
        $network = array();
        // use the relationships model to get relationships
        $network = Relationship::getNetwork($user);
        // Add a zero element; so if network is empty the IN part of the query won't fail
        $network[] = 0;
        $network = implode(',', $network);
        // query the statuses table
        $sql = "SELECT  t.type_name, s.*, UNIX_TIMESTAMP(s.created) as timestamp,
               p.first_name as poster_name, r.first_name as profile_name
        FROM status s, status_type t, user_profile p, user_profile r
         WHERE t.id = s.type AND p.user_id = s.creator AND r.user_id = s.profile
         AND ( p.user_id={$user} OR r.user_id={$user} OR ( p.user_id IN ({$network}) AND r.user_id IN ({$network}) ) )";
        // ORDER BY s.ID DESC LIMIT {$offset}, 20";

        $config = array();
        $config['totalItemCount'] = YiiUtil::countBySql($sql);
        $config['keyField'] = 'id';
        $config['sort'] = array(
            'defaultOrder' => 'id DESC',
            'attributes' => array(
                'id'
            )
        );
        $sqlDataProviderConfig = CMap::mergeArray($config, $sqlDataProviderConfig);
        return new CSqlDataProvider($sql, $sqlDataProviderConfig);
    }
}