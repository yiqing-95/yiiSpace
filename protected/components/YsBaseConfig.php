<?php
/**
 *
 * User: yiqing
 * Date: 13-3-2
 * Time: 下午11:42
 * To change this template use File | Settings | File Templates.
 * -------------------------------------------------------
 * -------------------------------------------------------
 * the base class  for params which can be configurable from GUI
 */
abstract class YsBaseConfig
{
    /**
     * @return string
     * the category name which meaning is same as {Yii::t()} first param
     * and you can refer to "CmsSettings" extension  for more detail
     * we can generate the subclass through the SysConfigController class
     */
   abstract  public function category();


}
