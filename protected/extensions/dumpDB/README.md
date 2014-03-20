# Yii extension that allows to create backups of mysql database (structure and data) 

Usage:

      Yii::import('ext.dumpDB.dumpDB');
           $dumper = new dbBackup();
           $dumper->getDump();
