<?php

Yii::import('msg.models._base.BaseMsg');

class Msg extends BaseMsg
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @var string
     */
    public $toUserName = '';

    public function rules()
    {
        return array(
            array('uid, data', 'required'),
            // 演示条件验证器的用法！ 其实单用内联验证+ajax验证足以满足用户存在性验证了！
            array('toUserName', 'ext.YiiConditionalValidator',
                'if' => array(
                    array('type', 'in', 'range' => array(self::TYPE_SYS_PERSONAL,self::TYPE_SYS_BROADCAST), 'allowEmpty' => false)
                ),
                'then' => array(
                    array('toUserName', 'existsUser'),
                ),
            ),
            array('uid, snd_type, snd_status, priority, to_id, create_time, sent_time, delete_time', 'numerical', 'integerOnly' => true),
            array('type', 'length', 'max' => 50),
            array('msg_pid', 'length', 'max' => 20),
            array('type, snd_type, snd_status, priority, to_id, msg_pid, sent_time, delete_time', 'default', 'setOnEmpty' => true, 'value' => null),
            array('id, uid, data, type, snd_type, snd_status, priority, to_id, msg_pid, create_time, sent_time, delete_time', 'safe', 'on' => 'search'),
        );
    }


    /**
     * 内联验证器
     *
     * @param $obj
     * @param $attr
     */
    public function existsUser($obj,$attr){
        if($this->type == self::TYPE_SYS_PERSONAL){
            $criteria = new CDbCriteria( );
            $criteria->addColumnCondition(array(
               'username'=>$this->toUserName ,
            ));
            $user = User::model()->find($criteria);
            if($user == null){
                 $this->addError('to_id','用户不存在');
           }else{
                // 修改下掉用户的id
                $this->to_id = $user->primaryKey ;
            }
        }else{
            // 如果不是私人消息那么 接收者id为0 或-1
            $this->to_id = 0 ;
        }
    }

    /**
     * Get a users inbox
     * @param int $user the user
     * @param array $sqlDataProviderConfig
     * @return \CSqlDataProvider
     * @see http://www.yiiframework.com/forum/index.php/topic/32121-data-provider-and-grid-sorting-problem/page__p__154672__hl__c+qldataprovider+ort#entry154672
     */
    static public function getInbox0($user, $sqlDataProviderConfig = array())
    {
        $sql = "SELECT IF(m.read=0,'unread','read') as read_style,
            m.subject, m.id, m.sender, m.recipient, DATE_FORMAT(m.sent, '%D
            %M %Y') as sent_friendly, psender.first_name as sender_name
             FROM
                   msg m, user_profile psender
             WHERE psender.user_id=m.sender AND
            m.recipient=" . $user;
        // . " ORDER BY m.ID DESC";

        $config = array();
        $config['totalItemCount'] = YiiUtil::countBySql($sql);
        $config['keyField'] = 'id';
        $config['sort'] = array(
            'defaultOrder' => 'm.id DESC',
            'attributes' => array(
                'm.id'
            )
        );

        $sqlDataProviderConfig = CMap::mergeArray($config, $sqlDataProviderConfig);

        return new CSqlDataProvider($sql, $sqlDataProviderConfig);
    }


    const TYPE_SYS_BROADCAST = 'sys_broadcast';
    const TYPE_SYS_PERSONAL = 'sys_personal';
    const TYPE_MBR_PERSONAL = 'member_personal';

    /**
     * 系统消息类型选择项
     *
     * @return array
     */
    public static function getMsgTypeOptions()
    {
        return array(
            self::TYPE_SYS_BROADCAST => '系统广播',
            self::TYPE_SYS_PERSONAL => '个人消息',
            // 这个后期再做支持
            //  'sys_multi'=>'多人消息',
        );
    }
}