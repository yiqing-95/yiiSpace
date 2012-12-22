<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-12-22
 * Time: 下午9:13
 * To change this template use File | Settings | File Templates.
 */
class ObjectPrivacyKeeper
{

    /**
     * @var string
     * -----------------------------
     * if null means the default behavior
     * can apply to any object !
     * -----------------------------
     */
    public $objectName = null;

    /**
     * @param int $privacyCode
     * @param int $ownerId
     * @param int $viewerId
     * @param string $privacyField
     * @param string $privacyData used for complex check .such as specified userIds groupIds ... password protection etc..
     * @throws CException
     * @throws InvalidArgumentException
     * @return bool
     */
    public function check($privacyCode=1,$ownerId=0,$viewerId=0,$privacyField='view',$privacyData='')
    {
        $groupCheckers = $this->groupCheckers();
        if(!empty($groupCheckers) && isset($groupCheckers[$privacyField])){
            $groupCheckers = $groupCheckers[$privacyField];
            if(isset($groupCheckers[$privacyCode])){
                $groupCheckerConfig = $groupCheckers[$privacyCode];
                if(is_string($groupCheckerConfig)){
                    //$groupCheckerName = $groupCheckerConfig;
                  $groupChecker =  PrivacyGroupChecker::createPrivacyGroupChecker($groupCheckerConfig);

                }elseif(is_array($groupCheckerConfig)){
                  $groupChecker =  PrivacyGroupChecker::createPrivacyGroupChecker($groupCheckerConfig[1],array_slice($groupCheckerConfig,1));
                }else{
                    $className = get_class($this);
                    throw new CException("please check class {$className} , the method groupCheckers may mis_config ");
                }
            }else{
                throw new InvalidArgumentException('the privacy code is not supported  '.$privacyCode);
            }
        }else{
            throw new InvalidArgumentException('the privacy field is not supported  '.$privacyField);
        }
        $groupChecker->objectOwner = $ownerId;
        $groupChecker->viewer = $viewerId;
         // 防止有的实现没有返回值！！
        return $groupChecker->check();

    }

    /**
     * @return array
     * 支持多种配置
     * view cmt rate 等等....
     */
    public function groupCheckers()
    {
        return array(
            'view'=>array(
                1 => 'public',
                2 => 'friend',
                3 => 'self',
            ),
        );
    }
}
