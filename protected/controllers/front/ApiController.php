<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-11-25
 * Time: 上午12:32
 * To change this template use File | Settings | File Templates.
 */
class ApiController extends CController
{

    public function actionRpc(){
      //  die(__METHOD__);
        Yii::log("enter ". __METHOD__, CLogger::LEVEL_INFO);
        Yii::import('application.vendors.json_rpc.jsonRPCServer');

        $serviceProxy = YsService::instance();

        jsonRPCServer::handle($serviceProxy)
            or print 'no request';
        Yii::log("end ". __METHOD__, CLogger::LEVEL_INFO);
        die();
    }


}
