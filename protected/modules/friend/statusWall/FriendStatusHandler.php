<?php

/**
 *
 * User: yiqing
 * Date: 13-5-17
 * Time: 上午9:51
 * To change this template use File | Settings | File Templates.
 * -------------------------------------------------------
 * 需要设计接口 然后实现 可以使用widget技巧 渲染复杂数据
 * -------------------------------------------------------
 */
class FriendStatusHandler
{


    /**
     * @var string
     */
    public $actorLink;
    /**
     * @var User|array
     */
    public $actor;

    /**
     * @var array
     */
    public $data;


    public function init()
    {
        $this->data = CJSON::decode($this->data['update']);;
    }

    /**
     * @return mixed
     */
    public function renderTitle()
    {

        $data = $this->data;
        //$blogTitleLink = CHtml::link($data['title'],Yii::app()->createUrl('blog/post/view',array('id'=>$data['id'],'title'=>$data['title'])));
        echo " {$this->actorLink} 添加了好友  ";
    }

    /**
     * $statusData = array(
     * 'id' => $this->friendObj->primaryKey,
     * 'name' => $this->friendObj->username,
     * 'iconUrl' => $this->friendObj->icon_uri,
     * );
     */
    /**
     * @return mixed
     */
    public function renderBody()
    {

        $data = $this->data;

        //$userId = $data['id'];
        $userSpaceUrl = UserHelper::getUserSpaceUrl($data['id']);
        $userName = $data['name'];
        $iconUrl = UserHelper::getIconUrl($data['iconUrl']);

        $bodyTpl = <<<BODY

            <a  href="{$userSpaceUrl}" target="_blank">
                <img src="{$iconUrl}"
                     width="120px" height="120px"
                     alt=""/>
            </a>
            {$userName}

BODY;
        echo $bodyTpl;

    }
}