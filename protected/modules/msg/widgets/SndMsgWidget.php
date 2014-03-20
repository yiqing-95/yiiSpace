<?php
/**
 * Created by PhpStorm.
 * User: yiqing
 * Date: 14-2-7
 * Time: 下午12:42
 */

// namespace widgets;


class SndMsgWidget extends YsWidget {

    /**
     * 由外部注入
     * @var int
     */
    public $toUserId  ;
    /**
     * @var array
     */
    public $actionUrl = array('/msg/msg/create');
    /**
     * @var array
     */
    public $linkHtmlOptions = array() ;


    public function run(){

        if(!isset($this->toUserId)){
            $this->toUserId = UserHelper::getSpaceOwnerId() ;
        }
        $this->actionUrl['u'] = $this->toUserId ;
        // 自己的空间 不需要显示 下面流程不必要执行了直接返回
        if(user()->getId() == $this->toUserId ){

            return  ;
        }

        if(isset($this->linkHtmlOptions['class'])){
            $this->linkHtmlOptions['class'] .= ' msg-ajax-create';
        }else{
            $this->linkHtmlOptions['class'] = 'msg-ajax-create';
        }

        echo CHtml::link('发送私信',$this->actionUrl,$this->linkHtmlOptions);

        $this->widget('my.widgets.artDialog.ArtFormDialog', array(
                'id'=>'msg_create_dialog',
                'link' => 'a.msg-ajax-create',
                'options' => array(
                    // 'onSuccess' => 'js:',
                    'closeOnSuccess'=>true ,
                ),
                'dialogOptions' => array(
                    'title' => '发送消息',
                    'width' => 650,
                    'height' => 370,

                )
            )
        );
    }

} 