<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-7-24
 * Time: 下午4:04
 * To change this template use File | Settings | File Templates.
 * 充当global的面向对象用法提供方便的全局对象和全局方法的访问
 * ---------------------------------
 * 由于yii除了核心组件外 其余组件都是需要配置的用Yii::app()->componentId 的形式访问
 * 没有智能提示 附加的行为方法或者属性也没有智能提示
 * 所以这里作为他们的一个容器 这样通过phpDoc 可以提供智能提示
 *
 * ----------------------------------
 */
class My
{
    /**
     * @var bool
     */
    protected static $attachBehaviors = false;

    /**
     * @static
     * @param null $behaviorId 可以指定附加特定的行为 默认会全部的
     */
    public static function  attachBehavior($behaviorId = null)
    {
        if (self::$attachBehaviors) return;

        //...........................................................................
        //动态附加行为：
        $behaviors = array(
            'home' => array(
                'class' => 'ext.yiiext.behaviors.getUrl.EGetUrlBehavior',
                // whether returned url be absolute. Defaults to false.
                'useAbsolute' => true,
            ),
            'publicFilesUrl' => array(
                'class' => 'ext.yiiext.behaviors.getUrl.EGetUrlBehavior',
                'useAbsolute' => true,
                // the base url for files (without slashes)
                'baseUrl' => 'public',
            ),
        );
        if ($behaviorId == null) {
            Yii::app()->attachBehaviors($behaviors);
            self::$attachBehaviors = true;
        } else {
            if (Yii::app()->asa($behaviorId) !== null)
                Yii::app()->attachBehavior($behaviorId, $behaviors[$behaviorId]);
        }
    }

    /**
     * @static
     * @param string $url
     * @param bool $absolute
     * @return mixed
     */
    public static function getHomeUrl($url = '', $absolute = false)
    {
        self::attachBehavior();
        return Yii::app()->asa('home')->getUrl($url, $absolute);
    }

    /**
     * @static
     * @param string $url
     * @param bool $absolute
     * @return mixed
     */
    public static function getPublicFilesUrl($url = '', $absolute = false)
    {
        self::attachBehavior('publicFilesUrl');
        return Yii::app()->asa('publicFilesUrl')->getUrl($url, $absolute);
    }

    /**
     * @static
     * @param bool $onlyInModulesDir
     * @return array
     */
    public static function getModules($onlyInModulesDir = true)
    {
        $basePath = Yii::app()->getModulePath(); //dirname(__FILE__) . DIRECTORY_SEPARATOR . 'modules';
        $dirIt = new DirectoryIterator($basePath);
        $modules = array();
        foreach ($dirIt as $file) {
            if ($file->isDir() && !$file->isDot()) { //Ignore directories, e.g., ./ and ../
                    $modules[] = $file->getFileName() ;
            }
        }
        return $modules ;
    }

    /**
     * @param CSqlDataProvider $dp
     */
  static   public function listView4sqlDataProvider(CSqlDataProvider $dp){

        $viewStr = CHtml::openTag('div',array());
        $idItem = <<<KEY_ITEM
    <b> id:</b>
	<?php echo CHtml::link(CHtml::encode(\$data['id']),array('view','id'=>\$data['id'])); ?>
	<br />
KEY_ITEM;
        $viewStr .= "\n".$idItem;

        $rowSet = $dp->getData();
        $firstRow = array();
        if(!empty($rowSet)){
            $firstRow = current($rowSet);
        }
        foreach(array_keys($firstRow) as $column){
            $columnItem = <<<COL_ITEM
    <b>{$column}:</b>
	<?php echo CHtml::encode(\$data['{$column}']); ?>
	<br />
COL_ITEM;
            $viewStr .= ("\n".$columnItem);
        }
        $viewStr .= CHtml::closeTag('div');

        //echo "<pre>{$viewStr}</pre>" ;
         $controller = Yii::app()->getController();

      $controller->widget('ext.jchili.JChiliHighlighter', array(
          'lang' => "html",
          'code' => "{$viewStr}",
          'showLineNumbers' => false
      ));
    }

}
