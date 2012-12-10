<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-12-9
 * Time: 下午6:30
 * To change this template use File | Settings | File Templates.
 */
Yii::import('ext.txDbMigration.*');
class CommentMigration extends TXDbMigration
{

    /**
     * @var string
     */
    public $commentThreadTableName = 'cmt_thread';

    public  function safeUp(){

      $this->createThreadTable();
    }

    public function createThreadTable(){
      $sqlFile =  dirname(__FILE__).DIRECTORY_SEPARATOR.'cmt_thread.sql';

       $this->executeFile($sqlFile);
       // $this->execute($sqlFile);
        //Yii::import('my.utils.DbUtil');
        //DbUtil::executeSqlFile($sqlFile);

    }

    public  function safeDown(){

    }

    protected function execFile($sqlFileToExecute){
        // read the sql file
        $f = fopen($sqlFileToExecute,"r+");
        $sqlFile = fread($f, filesize($sqlFileToExecute));
        $sqlArray = explode(';',$sqlFile);
        foreach ($sqlArray as $stmt) {
            if (strlen($stmt)>3 && substr(ltrim($stmt),0,2)!='/*') {
               Yii::app()->db->createCommand($stmt)->execute();
                /*
                $result = mysql_query($stmt);
                if (!$result) {
                    $sqlErrorCode = mysql_errno();
                    $sqlErrorText = mysql_error();
                    $sqlStmt = $stmt;
                    break;
                }
                */
            }
        }
    }
}
