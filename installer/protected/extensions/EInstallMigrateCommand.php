<?php
Yii::import('system.cli.commands.MigrateCommand');

class EInstallMigrateCommand extends MigrateCommand
{
	public function beforeAction($action,$params)
	{
		$path= dirname(__FILE__) . '/../../../protected/migrations/';
		if($path===false || !is_dir($path))
			die('Error: The migration directory does not exist: '.$path."\n");
		$this->migrationPath=$path;
		
                return true;
	}
}