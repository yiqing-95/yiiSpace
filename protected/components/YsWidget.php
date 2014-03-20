<?php
/**
 * Created by PhpStorm.
 * User: yiqing
 * Date: 13-12-18
 * Time: 上午1:02
 * To change this template use File | Settings | File Templates.
 */

class YsWidget extends  CWidget{
    /**
     * Yii::app()->getModule('yupe')->coreCacheTime
     * yupe 项目思路! 每个widget都可能被缓存哦!
     */
    public $cacheTime;
    /**
     *
     */
    public $view;

    public function init()
    {
        parent::init();
    }

    /**
     * 有些内容参考yupe！！ 逻辑可以优化 有时间搞
     * 这个要看 文件检查操作的代价（file_exists）
     * @param bool $checkTheme
     * @return null|string
     * --------------------------------
     *  // 在渲染widget 之前设置皮肤！
     *     Yii::app()->theme = 'classic';
     *     $content = $this->widget('application.widgets.TestWidget',array(),true);
     *    $this->renderText($content);
     * --------------------------------
     *
     */
    public function getViewPath($checkTheme = false)
    {
        $themeView = null;
        if (Yii::app()->theme !== null) {
            $class = get_class($this);
            $obj = new ReflectionClass($class);
            // 如果本类所在文件夹下有views/themeName 那么直接用这个
            if(is_dir($tmpThemeView = dirname($obj->getFileName()) .DIRECTORY_SEPARATOR . 'views' .DIRECTORY_SEPARATOR .Yii::app()->getTheme()->name )){
                $themeView = $tmpThemeView ;
            }else{
                // 模块相似位置 在themes相应模块下的相似位置
                //  blog/widgets/hello/.../SomeWidget  映射到有皮肤的位置：
                //  themes/themeName/views/moduleName/widgets/hello/.../SomeWidget
                $string = explode(Yii::app()->modulePath . DIRECTORY_SEPARATOR, $obj->getFileName(), 2);
                if (isset($string[1])) {
                    $string = explode(DIRECTORY_SEPARATOR, $string[1], 2);
                    $themeView = Yii::app()->themeManager->basePath . '/' .
                        Yii::app()->theme->name . '/' . 'views' . '/' .
                        $string[0] . '/' . 'widgets' . '/' . $class;
                }
            }

        }
        return $themeView && file_exists($themeView) ? $themeView : parent::getViewPath($checkTheme);
    }
} 