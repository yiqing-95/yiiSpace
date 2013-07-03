<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-12-9
 * Time: 下午10:04
 * To change this template use File | Settings | File Templates.
 */
class DbUtil
{
    /**
     * @param $output
     * @return string
     */
    public static function removeComments(&$output)
    {
        $lines = explode("\n", $output);
        $output = "";
        // try to keep mem. use down
        $lineCount = count($lines);

        $in_comment = false;
        for ($i = 0; $i < $lineCount; $i++) {
            if (preg_match("/^\/\*/", preg_quote($lines[$i]))) {
                $in_comment = true;
            }
            if (!$in_comment) {
                $output .= $lines[$i] . "\n";
            }
            if (preg_match("/\*\/$/", preg_quote($lines[$i]))) {
                $in_comment = false;
            }
        }
        unset($lines);
        return $output;
    }

    /**
     * @static
     * @param $sql
     * @return string
     * --------------------------
     *  remove_remarks will strip the sql comment lines out of an uploaded sql file
     */
    public static function removeRemarks($sql)
    {
        $lines = explode("\n", $sql);

        // try to keep mem. use down
        $sql = "";

        $lineCount = count($lines);
        $output = "";

        for ($i = 0; $i < $lineCount; $i++) {
            if (($i != ($lineCount - 1)) || (strlen($lines[$i]) > 0)) {
                if (isset($lines[$i][0]) && $lines[$i][0] != "#") {
                    $output .= $lines[$i] . "\n";
                } else {
                    $output .= "\n";
                }
                // Trading a bit of speed for lower mem. use here.
                $lines[$i] = "";
            }
        }

        return $output;

    }

    /**
     * @static
     * @param $sql
     * @param $delimiter
     * @return array
     *-----------------------------------
     * split_sql_file will split an uploaded sql file into single sql statements.
     * Note: expects trim() to have already been run on $sql.
     */
    public static function splitSqlFile($sql, $delimiter)
    {
        // Split up our string into "possible" SQL statements.
        $tokens = explode($delimiter, $sql);

        // try to save mem.
        $sql = "";
        $output = array();

        // we don't actually care about the matches preg gives us.
        $matches = array();

        // this is faster than calling count($oktens) every time thru the loop.
        $token_count = count($tokens);
        for ($i = 0; $i < $token_count; $i++) {
            // Don't wanna add an empty string as the last thing in the array.
            if (($i != ($token_count - 1)) || (strlen($tokens[$i] > 0))) {
                // This is the total number of single quotes in the token.
                $total_quotes = preg_match_all("/'/", $tokens[$i], $matches);
                // Counts single quotes that are preceded by an odd number of backslashes,
                // which means they're escaped quotes.
                $escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $tokens[$i], $matches);

                $unescaped_quotes = $total_quotes - $escaped_quotes;

                // If the number of unescaped quotes is even, then the delimiter did NOT occur inside a string literal.
                if (($unescaped_quotes % 2) == 0) {
                    // It's a complete sql statement.
                    $output[] = $tokens[$i];
                    // save memory.
                    $tokens[$i] = "";
                } else {
                    // incomplete sql statement. keep adding tokens until we have a complete one.
                    // $temp will hold what we have so far.
                    $temp = $tokens[$i] . $delimiter;
                    // save memory..
                    $tokens[$i] = "";

                    // Do we have a complete statement yet?
                    $complete_stmt = false;

                    for ($j = $i + 1; (!$complete_stmt && ($j < $token_count)); $j++) {
                        // This is the total number of single quotes in the token.
                        $total_quotes = preg_match_all("/'/", $tokens[$j], $matches);
                        // Counts single quotes that are preceded by an odd number of backslashes,
                        // which means they're escaped quotes.
                        $escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $tokens[$j], $matches);

                        $unescaped_quotes = $total_quotes - $escaped_quotes;

                        if (($unescaped_quotes % 2) == 1) {
                            // odd number of unescaped quotes. In combination with the previous incomplete
                            // statement(s), we now have a complete statement. (2 odds always make an even)
                            $output[] = $temp . $tokens[$j];

                            // save memory.
                            $tokens[$j] = "";
                            $temp = "";

                            // exit the loop.
                            $complete_stmt = true;
                            // make sure the outer loop continues at the right point.
                            $i = $j;
                        } else {
                            // even number of unescaped quotes. We still don't have a complete statement.
                            // (1 odd and 1 even always make an odd)
                            $temp .= $tokens[$j] . $delimiter;
                            // save memory.
                            $tokens[$j] = "";
                        }

                    } // for..
                } // else
            }
        }
        return $output;
    }

    /**
     * @static
     * @param $file
     * ----------------------------
     * 小心sql中没有 分号结尾的文件
     */
    public static function executeSqlFile($file)
    {
        // ini_set('memory_limit', '5120M');
        // set_time_limit(0);
        $dbms_schema = $file;

        $sql_query = @fread(@fopen($dbms_schema, 'r'), @filesize($dbms_schema)) or die('problem ');
        $sql_query = self::removeRemarks($sql_query);
        $sql_query = self::splitSqlFile($sql_query, ';');
        //print_r($sql_query);
        $i = 1;
        foreach ($sql_query as $sql) {
            echo $i++;
            echo "
";
            Yii::app()->db->createCommand($sql)->execute() or die('error in query');
        }

    }

    public static function execSqlFile($sqlFile)
    {

        // Fetch the schema.
        $schema = file_get_contents($sqlFile);

        // Convert the schema into an array of sql queries.
        $schema = preg_split("/;\s*/", trim($schema, ';'));

        $db = Yii::app()->db;
        // Start transaction
        $txn = $db->beginTransaction();

        try {
            // Execute each query in the schema.
            foreach ($schema as $sql) {
                $command = $db->createCommand($sql);
                $command->execute();
            }

            // All commands executed successfully, commit.
            $txn->commit();
            return true;
        } catch (CDbException $e) {
            // Something went wrong, rollback.
            $txn->rollback();
            return false;
        }

        /*
        $sqls=file_get_contents($sqlFile);
        foreach(explode(';',$sqls) as $sql)
        {
            if(trim($sql)!=='')
                Yii::app()->db->createCommand($sql)->execute();
        }
        */

    }

}


/***************************************************************************
 *                             sql_parse.php
 *                              -------------------
 *     begin                : Thu May 31, 2001
 *     copyright            : (C) 2001 The phpBB Group
 *     email                : support@phpbb.com
 *
 *     $Id: sql_parse.php,v 1.8 2002/03/18 23:53:12 psotfx Exp $
 *
 ****************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   These functions are mainly for use in the db_utilities under the admin
 *   however in order to make these functions available elsewhere, specifically
 *   in the installation phase of phpBB I have seperated out a couple of
 *   functions into this file.  JLH
 *
\***************************************************************************/

//
// remove_comments will strip the sql comment lines out of an uploaded sql file
// specifically for mssql and postgres type files in the install....
//
/*
ini_set('memory_limit', '5120M');
set_time_limit(0);
$dbms_schema = 'yourfile.sql';

$sql_query = @fread(@fopen($dbms_schema, 'r'), @filesize($dbms_schema)) or die('problem ');
$sql_query = remove_remarks($sql_query);
$sql_query = split_sql_file($sql_query, ';');

$host = 'localhost';
$user = 'user';
$pass = 'pass';
$db = 'database_name';

mysql_connect($host, $user, $pass) or die('error connection');
mysql_select_db($db) or die('error database selection');

$i = 1;
foreach ($sql_query as $sql) {
    echo $i++;
    echo "
";
    mysql_query($sql) or die('error in query');
}

*/