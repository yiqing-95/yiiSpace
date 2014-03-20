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
    static public function getTempIconUrl(){
      return  bu('public/images/yii.png');
    }

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

    /**
     * @param array $arrs
     * @param string $depth_key
     * @return array
     * @see http://semlabs.co.uk/journal/converting-nested-set-model-data-in-to-multi-dimensional-arrays-in-php
     */
    function nestify( $arrs, $depth_key = 'depth' )
    {
        $nested = array();
        $depths = array();

        foreach( $arrs as $key => $arr ) {
            if( $arr[$depth_key] == 0 ) {
                $nested[$key] = $arr;
                $depths[$arr[$depth_key] + 1] = $key;
            }
            else {
                $parent =& $nested;
                for( $i = 1; $i <= ( $arr[$depth_key] ); $i++ ) {
                    $parent =& $parent[$depths[$i]];
                }

                $parent[$key] = $arr;
                $depths[$arr[$depth_key] + 1] = $key;
            }
        }

        return $nested;
    }

    /**
     * 把dbSchema中的表转换成 db迁移类
     * 存放在runtime下
     */
  static   public function dbSchema2migration(){
        $commandPath = Yii::app()->getBasePath() . DIRECTORY_SEPARATOR . 'commands';
        $runner = new CConsoleCommandRunner();
        $runner->addCommands($commandPath);
        // below it is dir path where you put this extension
        $commandPath =  Yii::getPathOfAlias('ext.dbCommand');

        $runner->addCommands($commandPath);

        $args = array('yiic', 'eDatabase', 'dump','--insertData=0');
        ob_start();
        $runner->run($args);
        echo htmlentities(ob_get_clean(), null, Yii::app()->charset);
    }

    /**
     * 锁定表单
     *
     * @param int $life_time 表单锁的有效时间(秒). 如果有效时间内未解锁, 表单锁自动失效.
     * @return boolean 成功锁定时返回true, 表单锁已存在时返回false
     */
   public static  function lockSubmit($lifeTime = null) {
        $lockKey = 'LOCK_SUBMIT_TIME' ;
        if ( isset($_SESSION[$lockKey]) && intval($_SESSION[$lockKey]) > time() ) {
            return false;
        }else {
            $lifeTime = $lifeTime ? $lifeTime : 10;
            $_SESSION[$lockKey] = time() + intval($lifeTime);
            return true;
        }
    }

    /**
     * 检查表单是否已锁定
     *
     * @return boolean 表单已锁定时返回true, 否则返回false
     */
   public static  function isSubmitLocked() {
       $lockKey = 'LOCK_SUBMIT_TIME';
        return isset($_SESSION[$lockKey]) && intval($_SESSION[$lockKey]) > time();
    }

    /**
     * 表单解锁
     *
     * @return void
     */
   public static  function unlockSubmit() {
       $lockKey = 'LOCK_SUBMIT_TIME';
        unset($_SESSION[$lockKey]);
    }
}
