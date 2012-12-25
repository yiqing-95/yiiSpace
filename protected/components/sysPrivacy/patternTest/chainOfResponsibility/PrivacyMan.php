<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-12-25
 * Time: 上午10:11
 * To change this template use File | Settings | File Templates.
 * ================================================================
 * yii 的事件机制其实也可以处理这种需求 触发一个事件 然后事件传播到各个
 * 处理器 如果有一个处理了 那么设置事件为被处理状态停止事件的继续传播！！
 * 所以隐私检查将是一个很好的示例yii事件处理的例子了。 一般大家很少注意
 * 对CEvent::handled 的设置！
 * 查看CComponent 源码：
 *  ...
 * // stop further handling if param.handled is set true
 *  if(($event instanceof CEvent) && $event->handled)
 *  return;
 *  ...
 * =================================================================
 * 责任链是有顺序的  而策略模式中的各个策略是无序的  如果对比if/else 那么
 * if(){}elseif(){}elseif(){}....  这种将是责任链模式的候选
 * 而
 * if(){} if(){} if(){} if(){} 这种可能是策略模式候选。
 * switch/case 也分以上两种隐喻  一种是有依赖的 比如顺序  一种是对等关系
 * 各个case间是平等的。
 * 因为一般语言都是 顺序执行的 所以出现在前面的语句先被执行 所以对于if/elseif/else 结构
 * 把最可能出现的条件放在越靠前的位置 是有利于效率提高的！虽然这种提升微不足道 :D ..
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

    public function __check($privacyCode=1,$ownerId=0,$viewerId=0,$privacyField='view',$privacyData=''){

        //---------------------------------------------------------------------------------
        /**
         * below is use handler's configs to build the chain
         */
        $privacyCheckers = array_reverse($this->privacyCheckers);
        $preChecker = null;
        foreach($privacyCheckers as $privacyCode=>$checkerConfig){
            // if checkerConfig is array ? next version realize it
            $currentChecker = new $checkerConfig;
            $currentChecker->handle();
            if($currentChecker->handled !== true){
                if(!empty($preChecker) && $preChecker instanceof PrivacyHandler){
                    $preChecker->setSuccessor($currentChecker);
                }else{
                    $firstChecker = $currentChecker ;
                }
                $preChecker = $currentChecker;
            }
        }
        //---------------------------------------------------------------------------------
    }

    /**
     * @param int $privacyCode
     * @param int $ownerId
     * @param int $viewerId
     * @param string $privacyField
     * @param string $privacyData
     * this is the standard realization of chan of responsibility !
     */
    public function check($privacyCode=1,$ownerId=0,$viewerId=0,$privacyField='view',$privacyData=''){

        //---------------------------------------------------------------------------------
        /**
         * below is use handler's configs to build the chain
         */
        $privacyCheckers = array_reverse($this->privacyCheckers);

        foreach($privacyCheckers as $privacyCode=>$checkerConfig){
            // if checkerConfig is array ? next version realize it
            $currentChecker = new $checkerConfig;
            $currentChecker->handle();
            if($currentChecker->getIsHandled() == true){
                break;
            }
        }

        //---------------------------------------------------------------------------------
    }
}
