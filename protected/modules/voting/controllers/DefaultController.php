<?php

class DefaultController extends Controller
{
    public function actionVote()
    {
        // print_r($_REQUEST);
        $request = Yii::app()->request;
        if ($request->getIsAjaxRequest() && $request->getIsPostRequest()) {

            $objectName = $request->getParam('objectName');
            $objectId = $request->getParam('objectId');
            $mode = $request->getParam('mode');

            $modeValues = array(
                'thumbUp' => 1,
                'thumbDown' => -1,
                'plus' => 1,
            );

            $config = YsThumbVotingSystem::getObjectConfig($objectName);
            if (!empty($config)) {
                $trackTable = $config['table_track'];
                $db = Yii::app()->db;
                $uid = Yii::app()->user->getIsGuest() ? 0 : Yii::app()->user->getId();
                $ip = WebUtil::getIp();

                $cmd = $db->createCommand(
                    " SELECT 1  FROM {$trackTable}
                      WHERE object_id = :object_id AND uid=:uid AND ip=:ip
                        "
                );
                $isDuplicate = $cmd->queryScalar(array(
                    ':object_id' => $objectId,
                    ':uid' => Yii::app()->user->getIsGuest() ? 0 : Yii::app()->user->getId(),
                    'ip' => WebUtil::getIp(),
                ));

                if ($isDuplicate) {
                    echo CJSON::encode(array(
                        'status' => 'failure',
                        'msg' => '已经投过了'
                    ));
                    exit;
                } else {
                    $cmd2 = $db->createCommand("INSERT INTO {$trackTable}
                                            (
                                             `object_id`,
                                             `value`,
                                             `uid`,
                                             `ip`,
                                             `create_time`)
                                VALUES ( :object_id,:value,:uid,:ip,:create_time)");
                    $result = $cmd2->execute(array(
                        ':object_id' => $objectId,
                        ':value' => $modeValues[$mode],
                        ':uid' => $uid,
                        'ip' => $ip,
                        ':create_time' => time(),
                    ));

                    if ($result > 0) {
                        if ($mode == 'thumbDown') {
                            $cmd3 = $db->createCommand("UPDATE {$config['trigger_table']} SET
                                  {$config['trigger_field_down_vote']} = {$config['trigger_field_down_vote']}+1
                                WHERE {$config['trigger_field_id']} = :objectId
                                LIMIT 1 ");
                        } else {
                            $cmd3 = $db->createCommand("UPDATE {$config['trigger_table']} SET
                                  {$config['trigger_field_up_vote']} = {$config['trigger_field_up_vote']}+1
                                  WHERE {$config['trigger_field_id']} = :objectId
                                  LIMIT 1 ");
                        }
                        if($cmd3->execute(array(':objectId'=>$objectId))){
                            echo CJSON::encode(array(
                                'status' => 'success',
                                'msg' => '感谢支持！'
                            ));
                            exit;
                        }else{
                            throw new CException("unKnow db error!");
                        }

                    }else{
                        throw new CException("unKnow db error!");
                    }
                }
            } else {
                  echo CJSON::encode(array(
                     'status'=>'failure',
                      'msg'=>'没有改对象类型的投票配置 ！'
                  ));
                exit;
            }

        }
    }
}