<?php

/**
 * @author Simone (Demo) Gentili
 * @version 0.3
 * @copyright Copyright (c) 2012, Simone Gentili
 * @license New BSD Licence
 */
class QueryiiCommand extends CConsoleCommand {

    /**
     * @since release 0.1
     * @param array() $args 
     */
    public function run($args) {
        list($command, $tableName, $alterCommand, $columnType, $fieldName, $blabla) = $args;
        if ($command == 'custom')
            $this->customCommand($tableName);
        elseif ($command == 'rename')
            $this->renameCommand($tableName, $alterCommand);
        elseif ($command == 'drop')
            $this->dropCommand($tableName);
        elseif ($command == 'alter')
            $this->alterCommand($tableName, $alterCommand, $columnType, $fieldName, $blabla);
        elseif ($command == 'create')
            $this->createCommand($tableName);
        elseif ($command == 'describe')
            $this->describeCommand($tableName);
        elseif ($command == 'truncate')
            $this->truncateCommand($tableName);
        elseif ($command == 'help')
            switch ($tableName) {
                case 'alter':
                    echo $this->getAddHelp();
                    break;

                default:
                    echo $this->getHelp();
                    break;
            }
        else
            $this->getHelp();
    }

    /**
     * @since release 0.1
     * @return void
     */
    public function getHelp() {
        return <<<EOD
   
EXAMPLES
   
    queryii alter <table-name> add <column-type> <column-name>
    queryii alter <table-name> drop <column-name>
    queryii alter <table-name> change <column-name> <column-type>
    
    queryii create <table-name>
    
    queryii custom user
    queryii custom rbac
    
    queryii drop <table-name>
    
    queryii help
    queryii help alter
    
    queryii rename <table-name>
    
    queryii truncate <table-name>

EOD;
    }

    /**
     * @since release 0.1
     * @return type 
     */
    public function getAddHelp() {

        return <<<EOD
   
USAGE
  queryii alter [table-name] add [column-type] [column-name]

DESCRIPTION
  This command alter a table

PARAMETERS
 * table-name: required.
   this parameter indicates the name of table that you want to alter.
   
 * column-type: required.
   this parameter indicates the schema column type of the field:
   
  pk:           int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY"
  string:       varchar(255)
  text:         text
  integer:      int(11)
  float:        float
  decimal:      decimal
  datetime:     datetime
  timestamp:    timestamp
  time:         time
  date:         date
  binary:       blob
  boolean:      tinyint(1)
  
 * column-name: required.
   this name of the field.

     
EOD;
    }

    /**
     * @since release 0.1
     * @param string $tableName
     * @return void 
     */
    private function describeCommand($tableName) {
        if (($db = Yii::app()->getDb()) === null) {
            echo "Error: an active 'db' connection is required.\n";
            echo "If you already added 'db' component in application configuration,\n";
            echo "please quit and re-enter the yiic shell.\n";
            return;
        }
    }

    /**
     * @since release 0.1
     * @param string $tableName
     * @return void 
     * @access private
     */
    private function createCommand($tableName) {
        Yii::app()
                ->db
                ->createCommand("create table {$tableName} (" .
                        "id " . Yii::app()->getDb()->schema->columnTypes['pk'] . "" .
                        ")")
                ->execute();
        return $this;
    }

    /**
     * @since release 0.1
     * @param string $tableName
     * @return void
     * @access private
     */
    private function truncateCommand($tableName) {
        Yii::app()
                ->db
                ->createCommand("truncate table {$tableName}")
                ->execute();
    }

    /**
     * @since release 0.2
     * @param string $command
     * @access private
     */
    private function customCommand($command) {
        if ($command == 'user') {
            $this->createCommand('User')
                    ->alterCommand('User', 'add', 'string', 'username')
                    ->alterCommand('User', 'add', 'string', 'password')
                    ->alterCommand('User', 'add', 'string', 'email');
        }
        if ($command == 'rbac') {
            /* AuthItem */
            $this->createCommand('AuthItem')
                    ->alterCommand('AuthItem', 'add', 'string', 'name')
                    ->alterCommand('AuthItem', 'add', 'integer', 'type')
                    ->alterCommand('AuthItem', 'add', 'text', 'description')
                    ->alterCommand('AuthItem', 'add', 'text', 'data');

            $this->createCommand('AuthItemChild')
                    ->alterCommand('AuthItemChild', 'add', 'string', 'parent')
                    ->alterCommand('AuthItemChild', 'add', 'string', 'child');

            $this->createCommand('AuthAssignment')
                    ->alterCommand('AuthAssignment', 'add', 'string', 'itemname')
                    ->alterCommand('AuthAssignment', 'add', 'string', 'userid')
                    ->alterCommand('AuthAssignment', 'add', 'text', 'bizrule')
                    ->alterCommand('AuthAssignment', 'add', 'text', 'date');
        }
    }

    /**
     * @since release 0.3
     * @param string $oldTableName
     * @param string $newTableName
     * @access private
     */
    private function renameCommand($oldTableName, $newTableName) {
        Yii::app()
                ->db
                ->createCommand("rename table {$oldTableName} to {$newTableName}")
                ->execute();
    }
    
    /**
     * @since release 0.1
     * @param string $tableName 
     * @access private
     */
    private function dropCommand($tableName) {
        Yii::app()
                ->db
                ->createCommand("drop table {$tableName}")
                ->execute();
    }
    
    /**
     * @since release 0.1
     * @param string $tableName 
     * @param string $alterCommand 
     * @param string $columnType 
     * @param string $fieldName 
     * @access private
     */
    private function alterCommand($tableName, $alterCommand, $columnType, $fieldName) {

        switch ($alterCommand) {
            case 'add':
                $schemaType = Yii::app()->getDb()->schema->columnTypes[$columnType];
                $sqlCommand = "alter table `{$tableName}` " .
                        "$alterCommand `{$fieldName}` {$schemaType} ";

                $tipi = array();
                foreach (Yii::app()->getDb()->schema->columnTypes as $key => $value) {
                    $tipi[] = $key;
                }
                if (in_array($columnType, $tipi)) {
                    Yii::app()
                            ->db
                            ->createCommand($sqlCommand)
                            ->execute();
                }
                break;
            case 'drop':
                $sqlCommand = "alter table `{$tableName}` " .
                        "$alterCommand `{$columnType}`";
                Yii::app()
                        ->db
                        ->createCommand($sqlCommand)
                        ->execute();
                break;
            case 'change':
                $sqlCommand = "alter table `{$tableName}` " .
                        "$alterCommand `{$columnType}` `{$columnType}` {$fieldName} ";
                Yii::app()
                        ->db
                        ->createCommand($sqlCommand)
                        ->execute();
                break;

            default:
                $this->getHelp();
                return;
                break;
        }
        return $this;
    }

}