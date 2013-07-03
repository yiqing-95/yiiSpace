<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-8-24
 * Time: 下午12:21
 * To change this template use File | Settings | File Templates.
 * ----------------------------------------------------------------
 * 虽然migration 是yii提供用来自动升级回退程序的 但在安装器实现中
 * 也可以继承CDbMigration 用起up down功能来完成数据库相关的安装卸载工作
 * ----------------------------------------------------------------
 */
interface IInstaller
{

   public function install();

    public function uninstall();

}
