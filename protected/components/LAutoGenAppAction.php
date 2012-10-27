<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 11-12-2
 * Time: 下午1:47
 * To change this template use File | Settings | File Templates.
 * ---------------------------------------------------------------------------------
 $cmd = dirname(__FILE__) . "/../../../protected/yiic migrate --interactive=0";
// Run the command twice - second time around it should
// output that our system is up-to-date.
$output = stream_get_contents(popen($cmd, 'r'));
$output = stream_get_contents(popen($cmd, 'r'));
if (preg_match("/Your system is up-to-date/i", $output)) {
$message .= '<font color="green">Success:</font> Migration successfully applied.';
} else {
// TODO: handle this.
$message .= '<font color="red">Error:</font>An error occurred - here be dragons..';
}
 * ----------------------------------------------------------------------------------
 */
class LAutoGenAppAction extends CAction
{


    public function run(){

        header('Connection: close');
        ignore_user_abort(); //关掉浏览器，PHP脚本也可以继续执行.
        set_time_limit(0); // 通过set_time_limit(0)可以让程序无限制的执行下去
      /*
        $commandPath = Yii::app()->getBasePath().DIRECTORY_SEPARATOR.'commands';
        // add path to Yii's commands
        $commandPathYii = Yii::getPathOfAlias('system.cli.commands');

        $runner=new CConsoleCommandRunner();
        $runner->commands=$this->getModule()->yiicCommandMap;
        $runner->addCommands($commandPath);
        // register these commands too
        $runner->addCommands($commandPathYii);


        ob_start();
        $runner->run($tokens);

        echo htmlentities(ob_get_clean(), null, Yii::app()->charset);
      */
        /*
        $commandPath = Yii::app()->getBasePath() . DIRECTORY_SEPARATOR . 'commands';
        */
        $runner = new CConsoleCommandRunner();
        //$runner->addCommands($commandPath);
        //$commandPath = Yii::getFrameworkPath() . DIRECTORY_SEPARATOR . 'cli' . DIRECTORY_SEPARATOR . 'commands';
        //$runner->addCommands($commandPath);
        // for using  gii in cli
        $commandPath = Yii::getFrameworkPath() . DIRECTORY_SEPARATOR . 'cli' . DIRECTORY_SEPARATOR . 'commands' . DIRECTORY_SEPARATOR . 'shell';
        $runner->addCommands($commandPath);

        ob_start();
        //get models for all tables:
        //$tableNames = Yii::app()->db->getSchema()->tableNames;
        $dbSchema = Yii::app()->db->getSchema();
        $tableSchemas = $dbSchema->getTables();
        foreach($tableSchemas as $tableSchema){
            $this->tableSchema = $tableSchema;
            $tableName = $this->tableSchema->name ;
            $modelClassName = $this->generateClassName($tableName);

            $args = array('yiic', 'model', $modelClassName,$tableName);
            $runner->run($args);
            //generate crud controllers for all models:
            if(is_string($this->tableSchema->primaryKey)){
                unset($args);
                $args = array('yiic', 'crud', $modelClassName);
                $runner->run($args);
            }
        }


        echo htmlentities(ob_get_clean(), null, Yii::app()->charset);
    }


    public $tablePrefix;
    public $tableName;
    public $modelClass;

    /**
     * @var CDbTableSchema
     */
    protected $tableSchema;

    protected function generateClassName($tableName)
    {
        if($this->tableName===$tableName || ($pos=strrpos($this->tableName,'.'))!==false && substr($this->tableName,$pos+1)===$tableName)
            return $this->modelClass;

        $tableName=$this->removePrefix($tableName,false);
        $className='';
        foreach(explode('_',$tableName) as $name)
        {
            if($name!=='')
                $className.=ucfirst($name);
        }
        return $className;
    }

    protected function removePrefix($tableName,$addBrackets=true)
    {
        if($addBrackets && Yii::app()->db->tablePrefix=='')
            return $tableName;
        $prefix=$this->tablePrefix!='' ? $this->tablePrefix : Yii::app()->db->tablePrefix;
        if($prefix!='')
        {
            if($addBrackets && Yii::app()->db->tablePrefix!='')
            {
                $prefix=Yii::app()->db->tablePrefix;
                $lb='{{';
                $rb='}}';
            }
            else
                $lb=$rb='';
            if(($pos=strrpos($tableName,'.'))!==false)
            {
                $schema=substr($tableName,0,$pos);
                $name=substr($tableName,$pos+1);
                if(strpos($name,$prefix)===0)
                    return $schema.'.'.$lb.substr($name,strlen($prefix)).$rb;
            }
            else if(strpos($tableName,$prefix)===0)
                return $lb.substr($tableName,strlen($prefix)).$rb;
        }
        return $tableName;
    }
}
