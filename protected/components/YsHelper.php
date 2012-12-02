<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-11-17
 * Time: 下午4:03
 * To change this template use File | Settings | File Templates.
 */
class YsHelper
{

    /**
     * @static
     * @return string
     */
    static public function getUserCenterLayout(){
        return '//layouts/user/user_center';
    }

    /**
     * @static
     * @param bool $simple if return the simple layout
     * @return string
     * 返回 用户公共页面的布局路径
     * ----------------------------------------------
     * 对于复杂布局 可以提供单一布局 然后再其中根据条件
     * 继承不同的其他布局
     * 也可以直接提供其他返回布局的方法 尽量不要直接引用布局名称
     * 这样以后可以做参数化布局返回 比如根据用户设置返回不同的布局名称
     * ----------------------------------------------
     */
    static public function getUserSpaceLayout($simple=false){
        return ($simple == true)? '//layouts/user/user_space_simple' : '//layouts/user/user_space';
    }
}
