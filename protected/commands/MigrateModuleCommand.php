<?php
/**
 * @see https://bitbucket.org/cwmonson/yii-migratemodule
 */
class MigrateModuleCommand extends CConsoleCommand
{


    public function getHelp()
    {
        return <<<EOS

    usage: 
            yiic migratemodule modulename [migrateargs...]
            
    options:        
            yiic migrateargs: same as those of the built in 'migrate' command
            
    example:
            yiic migratemodule mymodule.mysubmodule up
            
            same as calling:
            
            yiic migrate --migrationPath=application.modules.mymodule.mysubmodule.migrations --migrationTable=MIGRATION_mymodule.mysubmodule up

EOS;
    }

    public function run($args)
    {
        $module = array_shift($args);

        if(!$module)
        {
            die("\nNo module specified.\n");
        }

        array_push($args, "--migrationPath=application.modules.$module.migrations");
        array_push($args, "--migrationTable=MIGRATION_$module");

        $migrateCmd = $this->commandRunner->createCommand('migrate');

        return $migrateCmd->run($args);
    }
}