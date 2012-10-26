<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-8-16
 * Time: 下午5:02
 * To change this template use File | Settings | File Templates.
 */
class StatusManager
{


public static function processTypeStatus($data){
    $controller = Yii::app()->getController();
    /*
    switch($data['type_reference']){
        case 'image':
            echo 'hi'; break;
            default:
            echo __METHOD__;
    }*/
    $type = $data['type_reference'];
    if($type == 'update'){
         return ;
    }elseif($type == 'image'){
        $controller->renderPartial('status.plugins.image.imageView',array('data'=>$data));
    }elseif($type == 'video'){
        $controller->renderPartial('status.plugins.video.videoView',array('data'=>$data));
    }elseif($type == 'link'){
        $controller->renderPartial('status.plugins.link.linkView',array('data'=>$data));
    }else{
        echo __METHOD__;
    }

}
}
