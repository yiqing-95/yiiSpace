<?php

Yii::import('friend.models._base.BaseRelationship');

class Relationship extends BaseRelationship
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return CActiveRecord|Relationship
     */
    public function createRelation()
    {
        $model = Relationship::model()->find('(user_a=:user_a AND user_b=:user_b) OR (user_a=:user_b AND user_b=:user_a)',
            array(':user_a' => $this->user_a, ':user_b' => $this->user_b)
        );
        if (!empty($model)) {
            return $model;
        } else {
            $relationType = RelationshipType::model()->findByPk($this->type);
            if ($relationType->mutual == 0) {
                $this->accepted = 1;
            } else {
                // mutual relation need  user_b accept it
                $this->accepted = 0;
            }
            $this->save();
            return $this;
        }
    }

    /**
     * Get relationships between users
     * @static
     * @param int $user_a
     * @param int $user_b
     * @param int $approved
     * @param array $sqlDataProviderConfig
     * @return CSqlDataProvider
     */
    public static function getRelationships($user_a = 0, $user_b = 0, $approved = 0, $sqlDataProviderConfig = array())
    {
        $sql = "SELECT r.id as id , t.name as type_name, t.plural_name as type_plural_name,
                ua.username as user_a_name, ub.username as  user_b_name
                FROM
                relationship r,
                relationship_type t,
                user ua, user ub
                 WHERE t.id = r.type AND  ua.id = r.user_a
                  AND ub.id = r.user_b
                  AND  r.accepted = {$approved} ";
        if ($user_a != 0) {
            $sql .= " AND r.user_a={$user_a} ";
        }
        if ($user_b != 0) {
            $sql .= " AND r.user_b={$user_b} ";
        }
        // $cmd = Yii::app()->db->createCommand($sql);
        // return $cmd->queryAll();

        $config = array();
        $config['totalItemCount'] = YiiUtil::countBySql($sql);
        $config['keyField'] = 'id';

        $sqlDataProviderConfig = CMap::mergeArray($config, $sqlDataProviderConfig);

        return new CSqlDataProvider($sql, $sqlDataProviderConfig);
    }


    /**
     * Get relationships by user
     * @static
     * @param $user the user whose relationships we wish to list
     * @param boolean $obr should we randomly order the results?
     * @param array $sqlDataProviderConfig
     * @return CSqlDataProvider
     */
    static public function getByUser($user, $obr = false, $sqlDataProviderConfig = array())
    {
        $sql = "SELECT t.plural_name, p.first_name as users_name, u.id FROM
                    user u, user_profile p,
                    relationship r, relationship_type t
                    WHERE t.id = r.type AND r.accepted=1 AND (r.user_a={$user}
                          OR r.user_b={$user}) AND IF( r.user_a={$user},u.id=
                          r.user_b,u.id = r.user_a) AND p.user_id = u.id";
        // if we are ordering by random
        if ($obr == true) {
            $sql .= " ORDER BY RAND() ";
        }

        $config = array();
        $config['totalItemCount'] = YiiUtil::countBySql($sql);
        $config['keyField'] = 'id';

        $sqlDataProviderConfig = CMap::mergeArray($config, $sqlDataProviderConfig);

        return new CSqlDataProvider($sql, $sqlDataProviderConfig);
    }

    /**
     * Approve relationship
     * @return void
     */
    public function approveRelationship()
    {
        $this->accepted = true;
    }

    /**
     * Get relationship IDs (network) by user
     * @param int $user the user whose relationships we wish to list
     * @return array the IDs of profiles in the network
     */
    static  public  function getNetwork( $user )
    {
        $sql = "SELECT u.id
                FROM user u, user_profile p, relationship r, relationship_type t
                WHERE t.id = r.type AND r.accepted=1 AND
                (r.user_a={$user} OR r.user_b={$user}) AND IF(
                r.user_a={$user},u.ID=r.user_b,u.id = r.user_a) AND p.user_id=u.id";
         $network = Yii::app()->db->createCommand($sql)->queryColumn();
        return $network;
    }

    /**
     * Get IDs of users a user has a relationship with
     * @param int $user the user in question
     * @param bool $cache - cache the results, or return the query?
     * @return array
     */
    public function getIDsByUser( $user, $cache=false )
    {
        $sql = "SELECT u.id
        FROM user u, user_profile p, relationship r, relationship_type t
        WHERE t.id = r.type AND r.accepted=1 AND
        (r.user_a={$user} OR r.user_b={$user})
        AND IF( r.user_a={$user},u.id = r.user_b,u.id = r.user_a)
        AND p.user_id = u.id";

        $networkIds = Yii::app()->db->createCommand($sql)->queryColumn();
        return $networkIds;

    }
}