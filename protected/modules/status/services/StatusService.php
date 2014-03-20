<?php
/**
 *  
 * User: yiqing
 * Date: 13-5-17
 * Time: 上午12:27
 * To change this template use File | Settings | File Templates.
 * -------------------------------------------------------
 * -------------------------------------------------------
 */
Yii::app()->getModule('status');
class StatusService {


    /**
     * @param array $status
     *  actor doWhat on profile time {content description use json here }
     * array(
     *  creator
     *  type
     *  profile
     *  update
     *  created
     *   approved
     * )
     * @return bool
     */
    public function postStatus($status=array()){

        $creator = $status['creator'];
        $type = $status['type'];
        $profile = $status['profile'];
        $update = isset($status['data'])? $status['data'] :$status['update'];
        $created = isset($status['created'])?$status['created']:null;
        $approved = 1 ;

        $statusModel = new Status() ;
        $statusModel->creator = $creator ;
        $statusModel->type = $type ;
        $statusModel->profile = $profile;
        $statusModel->update = $update ;
        $statusModel->created = $created ;
        $statusModel->approved = $approved ;

        if($statusModel->save()){
            return true ;
        }else{
            return false ;
        }

    }
}