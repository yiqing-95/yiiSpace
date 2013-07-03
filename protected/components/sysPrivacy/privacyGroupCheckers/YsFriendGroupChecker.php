<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-12-20
 * Time: 下午2:59
 * To change this template use File | Settings | File Templates.
 */
class YsFriendGroupChecker extends  PrivacyGroupChecker
{

    /**
     * This method should be overridden by child classes.
     */
    public  function check()
    {
        // 可以缓存到session中  一个人访问的朋友在同一个session中不会太多 计算只在第一次发生
        return UserHelper::isFriend($this->objectOwner,$this->viewer);
    }
}
