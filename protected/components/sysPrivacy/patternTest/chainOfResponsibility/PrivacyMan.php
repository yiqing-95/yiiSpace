<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-12-25
 * Time: 上午10:11
 * To change this template use File | Settings | File Templates.
 */
class PrivacyMan
{

    /**
     * @var array
     *  privacyCode=>handlerClassConfig
     *  -----------------------------------
     *  complex form (not supported yet , just a thought ):
     *  1=>array('class'=>'PublicPrivacyHandler','attr1'=>'someVal'..)
     */
    public $privacyCheckers = array(
        1 => 'PublicPrivacyHandler',
        2 => 'FriendPrivacyHandler',
        3 => 'SelfPrivacyHandler',
    );

    public function check($privacyCode=1,$ownerId=0,$viewerId=0,$privacyField='view',$privacyData=''){

        //---------------------------------------------------------------------------------
        /**
         * below is use handlers config to build the chain
         */
        $privacyCheckers = array_reverse($this->privacyCheckers);
         $firstChecker = null;
         $preChecker = null;
        foreach($privacyCheckers as $privacyCode=>$checkerConfig){
            // if checkerConfig is array ? next version realize it
            $currentChecker = new $checkerConfig;
            if(!empty($preChecker) && $preChecker instanceof PrivacyHandler){
                $preChecker->setSuccessor($currentChecker);
            }else{
                $firstChecker = $currentChecker ;
            }
            $preChecker = $currentChecker;
        }
        $firstChecker->handle();
        //---------------------------------------------------------------------------------
    }
}
