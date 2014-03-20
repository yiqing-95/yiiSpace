<?php

class DesignHelperController extends YsController
{
    public function actionIndex()
    {
        $actions = YiiUtil::getActionsOfController($this);

        // print_r($actions);

        ArrayUtil::dumpArray($actions);

        $this->render('index');
    }

    public function actionColumns($tableName)
    {
        $table = Yii::app()->db->getSchema()->getTable($tableName);
        $columns = $table->columns;
        // CVarDumper::dump($columns);
        $columnNames = array_keys($columns);
        //print_r($columnNames);
        foreach ($columnNames as $colName):
            echo ' public $' . $colName . "; \n";

        endforeach;
    }

    /**
     * @param $tableName
     * @Desc('为指定的表生产select语句')
     */
    public function actionSqlSelect($tableName){
        $table = Yii::app()->db->getSchema()->getTable($tableName);
        $columns = $table->columns;
        // CVarDumper::dump($columns);
        $columnNames = array_keys($columns);

        $fields = '';
        foreach ($columnNames as $colName):
            $fields .=  " {$colName}, \n";
        endforeach;
        $select = <<<STR
        SELECT
        {$fields}
        FROM {$tableName}
STR;
       $this->renderCode($select);
    }

    public function actionColumnDefaults($tableName)
    {
        $table = Yii::app()->db->getSchema()->getTable($tableName);
        $columns = $table->columns;
        // CVarDumper::dump($columns);
        // $columnNames = array_keys($columns);
        //print_r($columnNames);
        foreach ($columns as $colName => $colSchema):
            $dftVal = empty($colSchema->defaultValue) ? "''" : $colSchema->defaultValue;
            echo  "'{$colName}' => {$dftVal} " . ",\n";

        endforeach;
    }

    public function actionAttributeLabels($tableName)
    {
        $table = Yii::app()->db->getSchema()->getTable($tableName);
        $columns = $table->columns;
        // CVarDumper::dump($columns);
        $columnNames = array_keys($columns);
        echo " return array( \n ";
        //print_r($columnNames);
        foreach ($columnNames as $colName):
            echo "  '{$colName}' => Yii::t('m_{$tableName}', '{$colName}'), \n";

        endforeach;
        echo ") ;";
    }

    //----------------------<生成消息映射>-------------------------------------------------------------------------------------
    public function actionI18n($tableName, $module = null)
    {
        $module = empty($module) ? 'application' : $module;
        //--------------<确保设计目录的存在-------------------------------------------------------------------
        $designPath = Yii::getPathOfAlias($module . '.messages.zh_cn');
        if (!file_exists($designPath) /*$designPath===false*/)
            mkdir($designPath, 0777, true);
        if (!is_dir($designPath))
            die("Fatal Error: Your {$module} messages/zh_cn is not an directory!");
        //--------------<确保设计目录的存在/>-------------------------------------------------------------------
        //。。。。。。。。。。下面把内容生成道模板目录中.............................

        $defaultPath = Yii::getPathOfAlias($module . '.messages.Tpl.zh_cn'); // Yii::getPathOfAlias('application.messages.zh_cn');
        $path = $defaultPath;
        if (!file_exists($path)) {
            if (!mkdir($path, 0777, true)) {
                dir("Fatal Error: can't mike dir messages/_Tpl/zh_cn !");
            } else {
                echo 'success create dir' . $path;
            }
        }
        /*
        if($path===false)
			if(!mkdir($path,0777,true)){
                dir("Fatal Error: can't mike dir messages/_Tpl/zh_cn !");
            }else{
                echo 'success create dir'.$path;
            }*/
        if (!is_dir($path))
            die("Fatal Error: Your application {$path} is not an directory!");

        $table = Yii::app()->db->getSchema()->getTable($tableName);
        $columns = $table->columns;
        // CVarDumper::dump($columns);
        $columnNames = array_keys($columns);

        $cateName = $tableName;
        $colLabels = $this->generateLabels($table);
        $fileContent =
                " <?php \n
          return array( \n ";
        //print_r($columnNames);
        foreach ($columnNames as $colName):
            /*
            $rSpace = str_repeat(' ', ArrayUtil::maxStringLenInArray($columnNames) - strlen($colName));
            $fileContent .= "  '{$colName}' $rSpace =>  '{$colLabels[$colName]}' , \n";
            */
            $rSpace = str_repeat(' ', ArrayUtil::maxStringLenInArray($colLabels) - strlen($colLabels[$colName]));
            $fileContent .= "  '{$colName}' $rSpace =>  '{$colLabels[$colName]}' , \n";//"  '{$colLabels[$colName]}' $rSpace =>  '{$colLabels[$colName]}' , \n";

        endforeach;
        $fileContent .= ") ;";

        $filePath = $path . DIRECTORY_SEPARATOR . $cateName . '.php';
        file_put_contents($filePath, $fileContent);
        echo  file_get_contents($filePath);
    }

    # 模板二
    public function actionI18n_tpl2($tableName, $module = null)
    {
        $module = empty($module) ? 'application' : $module;
        //--------------<确保设计目录的存在-------------------------------------------------------------------
        $designPath = Yii::getPathOfAlias($module . '.messages.zh_cn');
        if (!file_exists($designPath) /*$designPath===false*/)
            mkdir($designPath, 0777, true);
        if (!is_dir($designPath))
            die("Fatal Error: Your {$module} messages/zh_cn is not an directory!");
        //--------------<确保设计目录的存在/>-------------------------------------------------------------------
        //。。。。。。。。。。下面把内容生成道模板目录中.............................

        $defaultPath = Yii::getPathOfAlias($module . '.messages.Tpl2.zh_cn'); // Yii::getPathOfAlias('application.messages.zh_cn');
        $path = $defaultPath;
        if (!file_exists($path)) {
            if (!mkdir($path, 0777, true)) {
                dir("Fatal Error: can't mike dir {$defaultPath} !");
            } else {
                echo 'success create dir' . $path;
            }
        }
        /*
        if($path===false)
			if(!mkdir($path,0777,true)){
                dir("Fatal Error: can't mike dir messages/_Tpl/zh_cn !");
            }else{
                echo 'success create dir'.$path;
            }*/
        if (!is_dir($path))
            die("Fatal Error: Your application {$path} is not an directory!");

        $table = Yii::app()->db->getSchema()->getTable($tableName);
        $columns = $table->columns;
        // CVarDumper::dump($columns);
        $columnNames = array_keys($columns);

        $cateName = $tableName;
        $colLabels = $this->generateLabels($table);
        $fileContent =
                " <?php \n
          return array( \n ";
        //print_r($columnNames);
        foreach ($columnNames as $colName):
            /*
            $rSpace = str_repeat(' ', ArrayUtil::maxStringLenInArray($columnNames) - strlen($colName));
            $fileContent .= "  '{$colName}' $rSpace =>  '{$colLabels[$colName]}' , \n";
            */
            $rSpace = str_repeat(' ', ArrayUtil::maxStringLenInArray($colLabels) - strlen($colLabels[$colName]));
            $fileContent .= "  '{$colLabels[$colName]}' $rSpace =>  '{$colLabels[$colName]}' , \n";

        endforeach;
        $fileContent .= ") ;";

        $filePath = $path . DIRECTORY_SEPARATOR . $cateName . '.php';
        file_put_contents($filePath, $fileContent);
        echo  file_get_contents($filePath);
    }

    public function actionI18n_tpl4comment($tableName, $module = null)
    {
        $module = empty($module) ? 'application' : $module;
        //--------------<确保设计目录的存在-------------------------------------------------------------------
        $designPath = Yii::getPathOfAlias($module . '.messages.zh_cn');
        if (!file_exists($designPath) /*$designPath===false*/)
            mkdir($designPath, 0777, true);
        if (!is_dir($designPath))
            die("Fatal Error: Your {$module} messages/zh_cn is not an directory!");
        //--------------<确保设计目录的存在/>-------------------------------------------------------------------
        //。。。。。。。。。。下面把内容生成道模板目录中.............................

        $defaultPath = Yii::getPathOfAlias($module . '.messages.Tpl2.zh_cn_4comment'); // Yii::getPathOfAlias('application.messages.zh_cn');
        $path = $defaultPath;
        if (!file_exists($path)) {
            if (!mkdir($path, 0777, true)) {
                dir("Fatal Error: can't mike dir {$defaultPath} !");
            } else {
                echo 'success create dir' . $path;
            }
        }
        /*
        if($path===false)
			if(!mkdir($path,0777,true)){
                dir("Fatal Error: can't mike dir messages/_Tpl/zh_cn !");
            }else{
                echo 'success create dir'.$path;
            }*/
        if (!is_dir($path))
            die("Fatal Error: Your application {$path} is not an directory!");

        $table = Yii::app()->db->getSchema()->getTable($tableName);
        $columns = $table->columns;
        // CVarDumper::dump($columns);
        $columnNames = array_keys($columns);

        $cateName = $tableName;
        $colLabels = $this->generateFieldComments($table);
        $fileContent =
            " <?php \n
      return array( \n ";
        //print_r($columnNames);
        foreach ($columnNames as $colName):
            /*
            $rSpace = str_repeat(' ', ArrayUtil::maxStringLenInArray($columnNames) - strlen($colName));
            $fileContent .= "  '{$colName}' $rSpace =>  '{$colLabels[$colName]}' , \n";
            */
            $rSpace = str_repeat(' ', ArrayUtil::maxStringLenInArray($colLabels) - strlen($colLabels[$colName]));
            $fileContent .= "  '{$colLabels[$colName]}' $rSpace =>  '{$colLabels[$colName]}' , \n";

        endforeach;
        $fileContent .= ") ;";

        $filePath = $path . DIRECTORY_SEPARATOR . $cateName . '.php';
        file_put_contents($filePath, $fileContent);
        echo  file_get_contents($filePath);
    }

    private function generateLabels(CDbTableSchema  $table)
    {
        $labels = array();
        foreach ($table->columns as $column)
        {
            $label = ucwords(trim(strtolower(str_replace(array('-', '_'), ' ', preg_replace('/(?<![A-Z])[A-Z]/', ' \0', $column->name)))));
            $label = preg_replace('/\s+/', ' ', $label);
            if (strcasecmp(substr($label, -3), ' id') === 0)
                $label = substr($label, 0, -3);
            if ($label === 'Id')
                $label = 'ID';
            $labels[$column->name] = $label;
        }
        return $labels;
    }

    private function generateFieldComments(CDbTableSchema  $table)
    {
        $maps = array();
        foreach ($table->columns as $column)
        {
            $columnName =  $column->name;
            if (strcasecmp(substr($columnName, -3), ' id') === 0)
                $columnName = substr($columnName, 0, -3);
            if ($columnName === 'Id')
                $columnName = 'ID';

            $maps[$column->name] = $column->comment;
        }
        return $maps;
    }

    ##-------------------------------------------------------------------------
    //模板间相互转化 针对已经翻译好的文件可以做这个
    public function actionI18nTplTrans()
    {
        // WebUtil::printCharsetMeta();
        $module = 'application';
        $msgDir = Yii::getPathOfAlias($module . '.messages.zh_cn');

        //================<创建目标转换路径>====================================================================
        $destinationDir = Yii::getPathOfAlias($module . '.messages.zh_cn_2');
        if (!file_exists($destinationDir)) {
            if (!mkdir($destinationDir, 0777, true)) {
                dir("Fatal Error: can't mike dir {$destinationDir} !");
            } else {
                echo 'success create dir' . $destinationDir;
            }
        }
        //================<创建目标转换路径/>====================================================================

        $tableNames = Yii::app()->db->getSchema()->tableNames;
        $files = scandir($msgDir);
        foreach ($files as $msgFile) {
            if (StringUtil::endsWith($msgFile, '.php')) {
                $fileName = substr($msgFile, 0, stripos($msgFile, '.php')); // rtrim($msgFile,'.php');
                if (in_array($fileName, $tableNames)) {
                    $dbField_labels = $this->generateLabels(db()->getSchema()->getTable($fileName));
                    $label_dbFields = array_flip($dbField_labels);
                    // echo $msgFile;
                    // echo $fileName;
                    $msgArray = require $msgDir . DIRECTORY_SEPARATOR . $msgFile;
                    $msgTransed = array();
                    foreach ($msgArray as $k => $v) {
                        if (array_key_exists($k, $label_dbFields)) {
                            $msgTransed[$label_dbFields[$k]] = $v;
                        }
                    }
                    print_r($msgTransed);
                    //转换每一个文件到特定目录下去：
                    if (ArrayUtil::saveArray2file($msgTransed, $destinationDir, $fileName)) {
                        echo 'success trans.' . $fileName . "  to {$destinationDir}  {$fileName} " . "<hr/>";
                    } else {
                        echo 'faild!';
                    }
                }

            }
        }
    }

##-------------------------------------------------------------------------
    public function actionI18nForDb()
    {
        $tableNames = Yii::app()->db->getSchema()->tableNames;
        foreach ($tableNames as $tableName) {
            echo  '<p style="background:#ffdd44">';
            echo "tableName :   {$tableName}" . '<hr/>';
            $tpl = request()->getParam('tpl');
            if (is_null($tpl)) {
                $this->actionI18n($tableName);
            } else {
                $this->actionI18n_tpl2($tableName);
            }
            echo '</p>';
        }
    }

    public function actionI18nForTableNames($module = 'application')
    {
        $tableNames = Yii::app()->db->getSchema()->tableNames;
        $messages = array_combine($tableNames, $tableNames);
        ArrayUtil::saveArray2file($messages, Yii::getPathOfAlias($module . '.messages.Tpl.zh_cn'), 'table_name');

        $this->widget('application.widgets.ext.jchili.JChiliHighlighter', array(
                                                                               'fileName' => __FILE__,
                                                                          ));
        $this->widget('ext.jchili.JChiliHighlighter', array(
                                                           'lang' => "php",
                                                           'code' => file_get_contents(Yii::getPathOfAlias($module . '.messages.Tpl.zh_cn') . DS . 'table_name.php'),
                                                           'showLineNumbers' => true
                                                      ));
        $this->render('index');
    }

    /**
     * @param $code
     * 渲染代码
     */
    public function renderCode($code){
      $output =  $this->widget('ext.jchili.JChiliHighlighter', array(
            'lang' => "php",
            'code' => $code,
            'showLineNumbers' => true
        ),
            true
        );
        $this->layout = 'empty';
        $this->renderText($output);
    }

    protected function getMessagesDir($module = 'application')
    {
        // WebUtil::printCharsetMeta();
        $module = 'application';
        $msgDir = Yii::getPathOfAlias($module . '.messages.zh_cn');

        //================<创建目标转换路径>====================================================================
        $destinationDir = Yii::getPathOfAlias($module . '.messages.zh_cn_2');
        if (!file_exists($destinationDir)) {
            if (!mkdir($destinationDir, 0777, true)) {
                dir("Fatal Error: can't mike dir {$destinationDir} !");
            } else {
                echo 'success create dir' . $destinationDir;
            }
        }
        //目标模板文件生成路径
        $defaultTplPath = Yii::getPathOfAlias($module . '.messages.Tpl.zh_cn');
        $cfile = CFile::getInstance($defaultTplPath);
        $cfile->createDir('0777');
        //...................
    }

    //----------------------<生成消息映射/>-------------------------------------------------------------------------------------
    /**
     * @param $tableName
     */
    public function  actionTabularForm($tableName)
    {
        $this->layout = false;

        $table = Yii::app()->db->getSchema()->getTable($tableName);

        WebUtil::printCharsetMeta();

        $this->widget('widgets.ext.jchili.JChiliHighlighter', array(
                                                           'lang' => "html",
                                                           'code' => $this->render('tabularForm', array('columns' => $table->getColumnNames()),true),
                                                           'showLineNumbers' => false
                                                      ));



       //$this->render('tabularForm', array('columns' => $table->getColumnNames()));
    }
    /**
     * @param $tableName
     * @Desc('用listView 显示表格')
     */
    public function  actionTableListView($tableName)
    {
        $this->layout = false;
        $table = Yii::app()->db->getSchema()->getTable($tableName);

        WebUtil::printCharsetMeta();

        $this->widget('widgets.ext.jchili.JChiliHighlighter', array(
            'lang' => "html",
            'code' => $this->render('tableListView', array('columns' => $table->getColumnNames()),true),
            'showLineNumbers' => false
        ));



        //$this->render('tabularForm', array('columns' => $table->getColumnNames()));
    }


    // Uncomment the following methods and override them if needed
    /*
     public function filters()
     {
         // return the filter configuration for this controller, e.g.:
         return array(
             'inlineFilterName',
             array(
                 'class'=>'path.to.FilterClass',
                 'propertyName'=>'propertyValue',
             ),
         );
     }

     public function actions()
     {
         // return external action classes, e.g.:
         return array(
             'action1'=>'path.to.ActionClass',
             'action2'=>array(
                 'class'=>'path.to.AnotherActionClass',
                 'propertyName'=>'propertyValue',
             ),
         );
     }
     */
}