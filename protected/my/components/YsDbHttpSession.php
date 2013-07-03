<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-11-3
 * Time: 下午7:25
 * To change this template use File | Settings | File Templates.
 * ----------------------------------------------------------------
 * @see http://www.yiiframework.com/forum/index.php/topic/4330-users-online-feature/page__p__114267__hl__online++u+er#entry114267
 *
 */
class YsDbHttpSession extends  CDbHttpSession
{

    /**
     * Creates the session DB table.
     * @param CDbConnection $db the database connection
     * @param string $tableName the name of the table to be created
     */
    protected function createSessionTable($db,$tableName)
    {
        $driver=$db->getDriverName();
        if($driver==='mysql')
            $blob='LONGBLOB';
        else if($driver==='pgsql')
            $blob='BYTEA';
        else
            $blob='BLOB';
        $db->createCommand()->createTable($tableName,array(
            'id'=>'CHAR(32) PRIMARY KEY',
            'expire'=>'integer',
            'data'=>$blob,
           // 'user_id'=> " int(11) NOT NULL DEFAULT '0' ",
            'user_id'=> " int(11)  DEFAULT '0' ",
        ));
    }

}
