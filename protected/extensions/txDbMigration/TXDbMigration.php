<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-12-9
 * Time: 下午7:26
 * To change this template use File | Settings | File Templates.
 * ----------------------------------------------------------------
 * @author  TXGruppi
 * ---------------------------------------------------------------
 * @see http://www.yiiframework.com/forum/index.php/topic/28947-execute-sql-file-in-migration/
 * ---------------------------------------------------------------
 */

class TXDbMigration extends CDbMigration {

    protected function _infoLine($filePath, $next = null) {
        echo "\r    > execute file $filePath ..." . $next;
    }

    public function executeFile($filePath) {
        $this->_infoLine($filePath);
        $time=microtime(true);
        $file = new TXFile(array(
            'path' => $filePath,
        ));

        if (!$file->exists)
            throw new Exception("'$filePath' is not a file");

        try {
            if ($file->open(TXFile::READ) === false)
                throw new Exception("Can't open '$filePath'");

            $total = floor($file->size / 1024);
            while (!$file->endOfFile()) {
                $line = $file->readLine();
                $line = trim($line);
                if (empty($line))
                    continue;
                $current = floor($file->tell() / 1024);
                $this->_infoLine($filePath, " $current of $total KB");
                $this->getDbConnection()->createCommand($line)->execute();
            }

            $file->close();
        } catch (Exception $e) {
            $file->close();
            var_dump($line);
            throw $e;
        }
        $this->_infoLine($filePath, " done (time: ".sprintf('%.3f', microtime(true)-$time)."s)\n");
    }

}
