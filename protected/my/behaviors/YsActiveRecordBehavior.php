<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 11-6-13
 * Time: 下午8:42
 * To change this template use File | Settings | File Templates.
 */

class YsActiveRecordBehavior extends CActiveRecordBehavior
{

    public static function getStreamModels(){
        //来自配置 参考ActionFeedManager 类先
        return array(
           // 'Relationship',
        );
    }

    public function beforeSave($event)
    {

    }


    public function afterSave($event)
    {
        if ( $this->Owner->getIsNewRecord()) {

             $arClass = get_class($this->owner);
            if(in_array($arClass,self::getStreamModels())){
                $actionFeedHandler = ActionFeedManager::getActionFeedHandler($arClass);
                if(!empty($actionFeedHandler)){
                    $actionFeedHandler->setActionType(ActionFeedManager::ACTION_TYPE_AR_INSERT);
                    $actionFeedHandler->setSubject($this->getOwner());
                    $data  = $actionFeedHandler->getData();

                    $easyQuery = EasyQuery::instance('action_feed');
                    $easyQuery->insert(array(
                        'uid'=>user()->getId(),
                        'action_type'=>ActionFeedManager::ACTION_TYPE_AR_INSERT,
                        'action_time'=>time(),
                        'data'=>$data,
                        'object_type'=>$arClass,
                        'object_id'=>$actionFeedHandler->getObjectId(),
                        'target_type'=>$actionFeedHandler->getTargetType(),
                        'target_id'=>$actionFeedHandler->getTargetId(),
                        'target_owner'=>$actionFeedHandler->getTargetOwner(),
                    ));
                }
               // die(__METHOD__);
            }
        } else {

        }
    }


    public function beforeDelete($event)
    {

    }


    public function afterDelete($event)
    {

    }


    public function beforeFind($event)
    {
    }


    public function afterFind($event)
    {
    }


    //-------------<以下来自CModelBehavior-----------------------------------------------------------------------
    public function afterConstruct($event)
    {
    }


    public function beforeValidate($event)
    {

    }


    public function afterValidate($event)
    {
    }


}

