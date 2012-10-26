<?php

Yii::import('msg.models._base.BaseMsg');

class Msg extends BaseMsg
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * Get a users inbox
     * @param int $user the user
     * @param array $sqlDataProviderConfig
     * @return \CSqlDataProvider
     * @see http://www.yiiframework.com/forum/index.php/topic/32121-data-provider-and-grid-sorting-problem/page__p__154672__hl__c+qldataprovider+ort#entry154672
     */
   static  public function getInbox( $user , $sqlDataProviderConfig = array() )
    {
        $sql = "SELECT IF(m.read=0,'unread','read') as read_style,
            m.subject, m.id, m.sender, m.recipient, DATE_FORMAT(m.sent, '%D
            %M %Y') as sent_friendly, psender.first_name as sender_name
             FROM
                   msg m, user_profile psender
             WHERE psender.user_id=m.sender AND
            m.recipient=" . $user ;// . " ORDER BY m.ID DESC";

        $config = array();
        $config['totalItemCount'] = YiiUtil::countBySql($sql);
        $config['keyField'] = 'id';
        $config['sort'] = array(
            'defaultOrder'=>'m.id DESC',
            'attributes'=>array(
              'm.id'
            )
        );

        $sqlDataProviderConfig = CMap::mergeArray($config, $sqlDataProviderConfig);

        return new CSqlDataProvider($sql, $sqlDataProviderConfig);
    }
}