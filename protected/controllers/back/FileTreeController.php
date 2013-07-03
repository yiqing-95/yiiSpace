<?php

class FileTreeController extends Controller
{
    const LIST_DIR = 'dir';
    const LIST_FILE = 'file';
    const LIST_ALL = 'all';

    public function actionIndex()
    {
        $this->render('index');
    }

    /**
     * @param $dirPath the dir path to visit ,such as :  $path = Yii::getPathOfAlias('application');
     * @param string $filter 'all' , 'dir' ,'file'
     * @return array  the children files :
     *           array($fileChildren);  array($dirChildren);
     *           for 'all' :  array(
     *                 'dirs'=> $dirList,
     *                 'files'=> $fileList,
     *            );
     */
    protected function listFiles($dirPath, $filter = self::LIST_ALL)
    {
        $files = $dirs = array();

        $names = scandir($dirPath);
        foreach ($names as $name) {
            //ignore (. or .. or .git or .svn etc...)
            if ($name[0] === '.') continue;

            if (($filter !== self::LIST_FILE) && is_dir($dirPath . '/' . $name)) {
                $dirs[$dirPath . '/' . $name] = $name;
            }

            if (($filter !== self::LIST_DIR) && is_file($dirPath . '/' . $name)) {
                $files[$dirPath . '/' . $name] = $name;
            }
        }
        switch ($filter) {
            case self::LIST_DIR :
                return $dirs;
                break;
            case self::LIST_DIR :
                return $files;
                break;
            case self::LIST_ALL :
                return array(
                    'dirs' => $dirs,
                    'files' => $files,
                );
                break;
            default :
                return array(
                    'dirs' => $dirs,
                    'files' => $files,
                );
        }
    }

    public function actionLoadChildren()
    {
        $request = Yii::app()->getRequest();
        if ($request->getIsAjaxRequest()) {

            $initDir = Yii::getPathOfAlias( /*'application'*/
                'webroot');
            $parentDir = $request->getParam('key');

            if ($parentDir == null) {
                //this is the first load tree action
                $path = $initDir;
            } else {
                //this is not first load children request
                $path = $parentDir;
            }

            $files = $this->listFiles($path);
            $dirs = $files['dirs'];
            $files = $files['files'];

            foreach ($dirs as $dirPath => $dirName) {
                $dynaTreeData[] = array(
                    'title' => $dirName,
                    'key' => $dirPath,
                    'isLazy' => true,
                    'isFolder' => true,
                );
            }

            foreach ($files as $filePath => $fileName) {
                $dynaTreeData[] = array(
                    'title' => $fileName,
                    'key' => $filePath,
                    'isLazy' => false,
                    'isFolder' => false,
                );
            }
            echo CJSON::encode($dynaTreeData);
            die();
        }
    }

    public function actionGetAliasOfPath()
    {
        $request = Yii::app()->getRequest();
        $path = $request->getParam('path');
        /*
     echo $path ,'|';
      echo Yii::getPathOfAlias('webroot');
       die; */
        $relativeAlias = 'webroot';

        echo  YiiUtil::getAliasFromPath($path, $relativeAlias);
        die;
    }

    public function actionGetRoutes()
    {
        $request = Yii::app()->getRequest();
        $path = $request->getParam('path');
        /*
     echo $path ,'|';
      echo Yii::getPathOfAlias('webroot');
       die; */
        $relativeAlias = 'webroot';
        $alias = YiiUtil::getAliasFromPath($path, $relativeAlias);
        /*
        preg_match_all('/modules\.([^\.]+)/i',$alias,$matches);
        print_r($matches);
        */
        if (preg_match('/\.([^\.]+)Controller$/i', $alias, $matches)) {
            $route = '';
            $aliasParts = explode('.', $alias);
            foreach ($aliasParts as $idx => $part) {
                if ($part == 'modules') {
                    $route .= $aliasParts[$idx + 1] . '.';
                    $module = isset($module) ? $module->getModule($route) : Yii::app()->getModule($route);
                }
                //处理控制器中嵌套的文件夹
                if ($part == 'controllers') {
                    $j = $idx;
                    while (strpos($aliasParts[$j + 1], 'Controller') === false) {
                        $route .= $aliasParts[$j + 1] . '.';
                        $j++;
                    }

                }
            }
            $controllerClass = $matches[1] . 'Controller';
            $route .= lcfirst($matches[1]);

            include_once($path);
            $actions = YiiUtil::getActionsOfController($controllerClass, $route, isset($module) ? $module : null);

            $routes = '';
            foreach ($actions as $action) {
                $routes .= $route . '.' . $action . "<br/>";
            }

            echo strtr($routes, array('.' => '/'));
            // print_r($matches);
        } else {
            echo 'sorry';
        }
        die;
    }

    public function actionFileMan()
    {
        $this->layout = false;

        /*
        global $orderType;
        $currDir = $_REQUEST['currDir'];
        $viewStyle = empty($_REQUEST['viewStyle']) ? 'icon' : $_REQUEST['viewStyle'];
        $orderStyle = empty($_REQUEST['orderStyle']) ? '' : $_REQUEST['orderStyle'];
         */
        //----------------------------------------------------------------------------
        $model = new  DynamicFormModel; //LoginForm;
        $model->setAttributeNames(array('viewStyle', 'orderStyle'));
        $model->setAttributeLabels(
            array(
                'viewStyle' => '显示方式',
                'orderStyle' => '排序方式'
            )
        );
        $model->unsetAttributes();
        if (!empty($_POST)) {
            $_GET = CMap::mergeArray($_GET, $_POST);
        }
        if (isset($_GET[get_class($model)])) {
            $model->attributes = $_GET[get_class($model)];
        }


        //------------------------------------------------------------------------------


        $currDir = isset($_GET['currDir']) ? urldecode($_GET['currDir']) : Yii::getPathOfAlias('common');

        /*
        $fileStack = user()->getState('fileStack',array());
        if(! in_array($currDir,$fileStack)){
            array_push($fileStack,$currDir);
            user()->setState('fileStack',$fileStack);
        }
         */
        if (isset($_GET['act']) && $_GET['act'] == 'toParent') {
            $currDir = dirname($currDir);
            $_GET['currDir'] = urlencode($currDir);
            //$currDir = array_pop($fileStack);
            //$_GET['currDir'] = $currDir;
            unset($_GET['act']);
        }
        // print_r($_REQUEST);
        $fileList = FileMan::getFileList($currDir);

        global $orderType;
        $orderType = isset($model->orderStyle) ? $model->orderStyle : 'name';
        usort($fileList, array('FileMan', 'fileCompare'));

        $dataProvider = new CArrayDataProvider($fileList, array(
            'id' => 'path',
            'keyField' => 'path',
            'sort' => array(
                'attributes' => array(
                    'name', 'size', 'ctime',
                ),
            ),
            'pagination' => array(
                'pageSize' => 50,
            ),
        ));
        $assetsUrl = WebUtil::assetsUrl(Yii::getPathOfAlias('common.assets.fileMan'));
        //$_GET['assetsUrl'] = $assetsUrl;
        Registry::instance()->set('assetsUrl', $assetsUrl);

        $this->render('fileMan', array('dataProvider' => $dataProvider,
            'model' => $model
        ));
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

/**
 * utf8_decode($this->request->post['name'])
 */
class FileMan
{
    /**
     * 列举某个文件夹下的文件夹或者文件
     * @param string $currDir
     * @param bool $containSubDir 是否包含子文件夹 或者不包括 表示只含有文件而不列举
     *                         子文件夹
     * @param string $fileExts
     * @internal param mixed $fileExt 要过滤的文件后缀 'jpg,jpeg,gif....'
     *         比如只要图片：array('gif', 'jpg', 'jpeg', 'png', 'bmp');
     * @return array <type>
     */
    public static function getFileList($currDir, $containSubDir = true, $fileExts = '')
    {
        $filterExtArr = is_array($fileExts) || empty($fileExts) ? $fileExts : explode(',',
            strtolower($fileExts));
        $picExtArr = array('gif', 'jpg', 'jpeg', 'png', 'bmp');

        $file_list = array();

        $files = scandir($currDir);
        if ($files) {
            foreach ($files as $filename) {
                if ($filename == '.' || $filename == '..') {
                    continue;
                }
                //$file = str_replace('//', '/', $currDir . '/' . $filename);
                $file = $currDir . DIRECTORY_SEPARATOR . $filename;
                //文件夹处理
                if (is_dir($file) && $containSubDir == true) {
                    $currFile = array('is_dir' => true, 'has_file' => (count(scandir($file)) > 2),
                        'size' => 0, //dirsize($file), 算文件夹大小太费时了所以不算了
                        'is_image' => false, 'filetype' => '', 'class' => 'Dir', //用来做css的类
                        'parent' => $currDir);
                } else {
                    //文件处理
                    $file_ext = strtolower(array_pop(explode('.', trim($file))));
                    if (!empty($filterExtArr)) {
                        //需要过滤文件：
                        if (!in_array($file_ext, $filterExtArr))
                            continue;
                    }
                    $currFile = array('is_dir' => false, 'has_file' => false, 'size' => filesize($file),
                        'easysize' => self::getEasySize(filesize($file)), 'dir_path' => '', 'is_image' =>
                        in_array($file_ext, $picExtArr), 'filetype' => $file_ext, 'class' => in_array($file_ext,
                            $picExtArr) ? 'File Photo' : 'File', //css类
                        //如果是图片其类名将是 File Photo
                        'parent' => $currDir);
                }
                //通用处理：
                $currFile['name'] = $filename;
                $currFile['ctime'] = date('Y-m-d H:i:s', filectime($file));
                $currFile['mtime'] = date('Y-m-d H:i:s', filemtime($file));
                $currFile['ico_type'] = self::type4ico($file);
                $currFile['path'] = $file;
                //$currFile['realpath'] = realpath($file);
                $file_list[] = $currFile;
            }
        }
        // echo count($file_list);
        return $file_list;
    }

    /**
     * //排序方法 排序比较器的实现 支持类型大小 应该还支持时间排序
     * 用法：usort($file_list, 'fileCompare');
     * ctime 表示更改时间 而不是字面猜测的创建时间！！！
     * 排序依据 应该从外部作为globe变量使用
     * @param $currFile
     * @param $nextFile
     * @return int
     */
    public static function fileCompare($currFile, $nextFile)
    {

        global $orderType;
        // echo __METHOD__, '|', $orderType;
        if ($currFile['is_dir'] && !$nextFile['is_dir']) {
            return -1;
        } else
            if (!$currFile['is_dir'] && $nextFile['is_dir']) {
                return 1;
            } else {
                //纯文件比较
                if ($orderType == 'size') {
                    if ($currFile['size'] > $nextFile['size']) {
                        return 1;
                    } else
                        if ($currFile['size'] < $nextFile['size']) {
                            return -1;
                        } else {
                            return 0;
                        }
                } else
                    if ($orderType == 'type') {
                        return strcmp($currFile['filetype'], $nextFile['filetype']);
                    } else
                        if ($orderType == 'ctime') {
                            return strcmp($currFile['ctime'], $nextFile['ctime']);
                        } else {
                            return strcmp($currFile['name'], $nextFile['name']);
                        }
            }
    }

//这些格式 用来找对应的图标用
    /**
     * @param $filename
     * @return string
     */
    public static function type4ico($filename)
    {
        if (is_dir($filename))
            return "dir";
        switch (strtolower(substr($filename, strrpos($filename, ".") + 1))) {
            /* Picturefiles	 */
            case "tif":
            case "cpt":
                return "cpt";
            /* Picturefiles with clips	 */
            case "bmp":
                return "bmp";
            case "gif":
                return "gif";
            case "jpg":
            case "jpeg":
                return "jpg";
            case "png":
                return "png";

            /* Soundfiles	 */
            case "mp2":
            case "mp3":
            case "snd":
            case "wav":
            case "wma":
                return "snd";

            /* Moviefiles	 */
            case "avi":
            case "asf":
            case "divx":
            case "dvx":
            case "flc":
            case "mov":
            case "mp4":
            case "mpg":
            case "mpv":
            case "vob":
            case "wmv":
                return "flick";
            case "rmm":
            case "rm":
                return "real";

            /* Web Files */
            case "asp":
                return "asp";
            case "html":
            case "htm":
            case "htx":
                return "html";
            case "php":
                return "php";
            case "xmp":
                return "xml";

            /* Office */
            case "doc":
            case "dot":
                return "doc";
            case "pws":
            case "pps":
            case "ppt":
                return "pws";
            case "xls":
                return "xls";
            case "pdf":
                return "pdf";
            case "mdb":
                return "mdb";
            case 'odt':
                return 'odt';
            case 'ods':
                return 'ods';
            case 'odp':
                return 'odp';
            case 'odg':
                return 'odg';
            /* C, C++, Java, Executables, SWF */
            case "cpp":
            case "c":
                return "cpp";
            case "h":
                return "h";
            case "jar":
            case "java":
                return "java";
            case "exe":
            case "bat":
            case "com":
                return "exe";
            case "rc":
                return "rc";
            case "sln":
                return "sln";
            case "vcproj":
                return "vcproj";
            case "swf":
                return "swf";
            case "ncb":
                return "ncb";

            /* Compression */
            case "rar":
                return "rar";
            case "zip":
            case "gz":
            case "tar":
            case "cab":
                return "zip";

            case "txt":
                return "txt";

            default:
                return "file";
        }
    }

    /**
     * 计算指定目录的占用大小
     * @param $dir
     * @return int
     */
    public static function dirSize($dir)
    {
        $dh = @opendir($dir);
        $size = 0;
        if ($dh != false) {
            while (($file = readdir($dh)) !== false) {
                if ($file != '.' and $file != '..') {
                    $path = $dir . '/' . $file;
                    if (is_dir($path)) {
                        $size += self::dirSize("$path/");
                    } elseif (is_file($path)) {
                        $size += filesize($path);
                    }
                }
            }
            closedir($dh);
        }
        return $size;
    }

    /**
     * 根据字节数算出文件的大小单位
     * @param  number $size
     * @return string
     */
    public static function getEasySize($size)
    {
        // 常规的文件大小测量单位
        $kb = 1024; // KB
        $mb = 1024 * $kb; // MB
        $gb = 1024 * $mb; // GB
        $tb = 1024 * $gb; // TB

        if ($size < $kb) {
            return $size . " B";
        } else
            if ($size < $mb) {
                return round($size / $kb, 2) . " KB";
            } else
                if ($size < $gb) {
                    return round($size / $mb, 2) . " MB";
                } else
                    if ($size < $tb) {
                        return round($size / $gb, 2) . " GB";
                    } else {
                        return round($size / $tb, 2) . " TB";
                    }
    }

    /**
     * @param $directory
     * @return bool
     */
    protected function recursiveDelete($directory)
    {
        if (is_dir($directory)) {
            $handle = opendir($directory);
        }

        if (!$handle) {
            return FALSE;
        }

        while (false !== ($file = readdir($handle))) {
            if ($file != '.' && $file != '..') {
                if (!is_dir($directory . '/' . $file)) {
                    unlink($directory . '/' . $file);
                } else {
                    $this->recursiveDelete($directory . '/' . $file);
                }
            }
        }

        closedir($handle);

        rmdir($directory);

        return TRUE;
    }

    function recursiveCopy($source, $destination)
    {
        $directory = opendir($source);

        @mkdir($destination);

        while (false !== ($file = readdir($directory))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($source . '/' . $file)) {
                    $this->recursiveCopy($source . '/' . $file, $destination . '/' . $file);
                } else {
                    copy($source . '/' . $file, $destination . '/' . $file);
                }
            }
        }

        closedir($directory);
    }


    /*
    * 用splfileinfo获取文件信息
    * $fileName = '/path/to/file/filename.php';
    $fileInfo = new SPLFileInfo($fileName);
    $fileProps = array();
    $fileProps['path'] = $fileInfo->getPath();
    $fileProps['filename'] = $fileInfo->getFilename();
    $fileProps['pathname'] = $fileInfo->getPathname();
    $fileProps['perms'] = $fileInfo->getPerms();
    $fileProps['inode'] = $fileInfo->getInode();
    $fileProps['size'] = $fileInfo->getSize();
    $fileProps['owner'] = $fileInfo->getOwner();
    $fileProps['group'] = $fileInfo->getGroup();
    $fileProps['atime'] = $fileInfo->getATime();
    $fileProps['mtime'] = $fileInfo->getMTime();
    $fileProps['ctime'] = $fileInfo->getCTime();
    $fileProps['type'] = $fileInfo->getType();
    $fileProps['isWritable'] = $fileInfo->isWritable();
    $fileProps['isReadable'] = $fileInfo->isReadable();
    $fileProps['isExecutable'] = $fileInfo->isExecutable();
    $fileProps['isFile'] = $fileInfo->isFile();
    $fileProps['isDir'] = $fileInfo->isDir();
    $fileProps['isLink'] = $fileInfo->isLink();
    * var_export($fileProps);
    array (
    'path' => '/path/to/file',
    'filename' => 'filename.php',
    'pathname' => '/path/to/file/filename.php',
    'perms' => 33261,
    'inode' => 886570,
    'size' => 1131,
    'owner' => 1002,
    'group' => 1002,
    'atime' => 1167067832,
    'mtime' => 1167067771,
    'ctime' => 1167067771,
    'type' => 'file',
    'isWritable' => true,
    'isReadable' => true,
    'isExecutable' => true,
    'isFile' => true,
    'isDir' => false,
    'isLink' => false,
    )
    *
    *
    */

}