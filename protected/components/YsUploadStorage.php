<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-8-13
 * Time: 下午4:00
 * To change this template use File | Settings | File Templates.
 * ------------------------------------------------------------------------
 * 上传文件统一用这个组件 不然文件存放很零散
 * linux下 可以采用fastDfs 新版的客户端已经支持自定义fileId 功能
 * @see http://code.google.com/p/my-fastdfs-client/（安装 fastDHT 分布式hash表组件）
 * 如果想用那个 可以改造下该类予以支持 目前不考虑分布式存储
 *
 * ------------------------------------------------------------------------
 * 文件还可以采取按日期存放 year/month  ...
 * ------------------------------------------------------------------------
 */
class YsUploadStorage extends CApplicationComponent
{

    /**
     * @var string
     */
    protected $savePathTemplate = 'base_path/module_id/user_id/';
    /**
     * @var bool
     */
    public $autoCreateUploadDir = true;
    /**
     * @var string
     */
    protected $_tempUploadDir ;

    /**
     * @static
     * @return YsUploadStorage
     */
    public static function instance()
    {
        if (!isset(Yii::app()->uploadStorage)) {
           /*
            Yii::app()->setComponents(array(
                'uploadStorage' => array(
                    'class' =>__CLASS__,
                ),
            ), false);*/
        }
        return Yii::app()->uploadStorage;
    }

    /**
     * Default web accessible base path for storing protected files
     */
    public  $uploadPath = 'uploads';

    /**
     * @var int
     */
    public $newFileMode = 0666;
    /**
     * @var int
     */
    public $newDirMode = 0777;
    /**
     * @var string base web accessible path for storing private files
     */
    protected $_basePath;
    /**
     * @var string base URL for accessing the publishing directory.
     */
    protected $_baseUrl;


    /**
     * @return string the root directory storing the published asset files. Defaults to 'WebRoot/assets'.
     */
    public function getBasePath()
    {
        if ($this->_basePath === null) {
            $request = Yii::app()->getRequest();
            $this->setBasePath(dirname($request->getScriptFile()) . DIRECTORY_SEPARATOR . $this->uploadPath);
        }
        return $this->_basePath;
    }

    /**
     * Sets the root directory storing published asset files.
     * @param string $value the root directory storing published asset files
     * @throws CException if the base path is invalid
     */
    public function setBasePath($value)
    {
        if (($basePath = realpath($value)) !== false && is_dir($basePath) && is_writable($basePath))
            $this->_basePath = $basePath;
        else
            throw new CException(Yii::t('yii', 'YsUploadStorage.basePath "{path}" is invalid.
             Please make sure the directory exists and is writable by the Web server process.',
                array('{path}' => $value)));
    }


    /**
     * @param $path
     * @return string
     */
    protected function hash($path)
    {
        return sprintf('%x', crc32($path /*. Yii::getVersion()*/));
    }

    //..........................................................................................
    /**
     * @param $dir
     * @param int $mode
     * @param bool $recursive
     * @return bool
     */
    public function makeDir($dir, $mode = 0777, $recursive = true)
    {
        if (!file_exists($dir)) {
            return mkdir($dir, $mode, $recursive);
        } else {
            return true;
        }
    }


    /**
     * @param CModule|string $module
     * @param bool $autoCreate
     * @throws CException
     * @return string
     */
    public function getUploadDir(CModule $module = null,$autoCreate = true)
    {
        $app = Yii::app();
        if (is_null($module)) {
            $module = $app->getController()->getModule();
            if (empty($module)) {
                $module = $app;
            }
        } elseif (is_string($module)) {
            $module = $app->getModule($module);
        }

        if ($module instanceof CModule) {
            if ($module instanceof CApplication) {
                $moduleId = 'app';
            } else {
                $moduleId = $module->getId();
            }
            //module id 含有点"."  不能作为文件夹名称
            $moduleId = str_replace('.', '_', $moduleId);
            $uploadDir = $this->getBasePath() . DIRECTORY_SEPARATOR . $moduleId;

            if($autoCreate == true){
                $this->makeDir($uploadDir);
            }
            return $uploadDir;
        } else {
            throw new CException('must give a validate module id or instance of CModule');
        }

    }


    /**
      * @deprecated 不在建议使用了
     *
     * @param $path
     *
     * @return bool|string
     * 获取路径的相对路径 如果没有给出那么就是基于yii基路经
     * 如果不存在 则认为不是上传上去的文件
     */
    public function getRelativePath($path)
    {

        $path = strtr($path, array('/' => DIRECTORY_SEPARATOR, '\\' => DIRECTORY_SEPARATOR));
        $relPath = $this->getBasePath();
        if (strpos($path, $relPath) === 0){
            // echo substr($path, strlen($relPath) + 1) , die;
            return substr($path, strlen($relPath) + 1);
        }else
            return false;
    }

    /**
     * 获取相对url
     *
     * @param $path
     * @return bool|string
     */
    public function getRelativeUrl($path)
    {

        $path = strtr($path, array('/' => DIRECTORY_SEPARATOR, '\\' => DIRECTORY_SEPARATOR));
        $relPath = $this->getBasePath();
        if (strpos($path, $relPath) === 0){
            // echo substr($path, strlen($relPath) + 1) , die;
            $relPath = substr($path, strlen($relPath) + 1);
             return   $this->getBaseUrl().str_replace(DIRECTORY_SEPARATOR, '/', ltrim($relPath, '/'));
        }else
            return false;
    }

    /**
     * @param string $relPath
     * @return string
     * get the realPath from relative path
     */
    public function relativePath2realPath($relPath){
        $relPath =  strtr($relPath, array('/' => DIRECTORY_SEPARATOR, '\\' => DIRECTORY_SEPARATOR));
        return $this->getBasePath(). DIRECTORY_SEPARATOR . ltrim($relPath,DIRECTORY_SEPARATOR);
    }

    /**
     * @param $relPath ,  如果传递的路径中以http开始 那么原样返回
     * 获取可访问的url 根据所传递的相对路径
     * 这里的相对路径是相对于数据上传跟目录文件夹：
     * @param bool $absolute
     * @throws InvalidArgumentException
     * @return string
     * 不存在传递路径时返回false
     */
    public function getAccessibleUrl($relPath , $absolute=false){
        if(($pos=strpos($relPath,'http'))===0){
            return $relPath ;
        }

        // 如果是文件那么传递的是绝对路径了！
        if(is_file($relPath)){
            $relPath = $this->getRelativePath($relPath);
        }

        if(empty($relPath)) throw new InvalidArgumentException('must give a not null param !') ;
        $accessibleUrl = $this->getBaseUrl($absolute) .str_replace(DIRECTORY_SEPARATOR, '/', '/' .ltrim($relPath,'/'));
        return $accessibleUrl ;
    }

    /**
     * @param $url
     * @return string
     */
    public function url2realPath($url){
        $relPath =  strtr($url, array('/' => DIRECTORY_SEPARATOR, '\\' => DIRECTORY_SEPARATOR));
        return dirname(Yii::app()->request->getScriptFile()) . DIRECTORY_SEPARATOR . ltrim($relPath,DIRECTORY_SEPARATOR);
    }

    /**
     * @param $path
     * @return bool|mixed
     */
    public function realPath2url($path){
        $path = strtr($path, array('/' => DIRECTORY_SEPARATOR, '\\' => DIRECTORY_SEPARATOR));

        $request = Yii::app()->getRequest();
        $relPath = dirname($request->getScriptFile());

        if (strpos($path, $relPath) === 0){
            return str_replace(DIRECTORY_SEPARATOR, '/',substr($path, strlen($relPath) + 1));
        }else
            return false;
    }

    /**
     * @return mixed
     */
    public function getTempUploadDir()
    {
        if ($this->_tempUploadDir === null) {

            $tempDir = Yii::getPathOfAlias('application.runtime') . DIRECTORY_SEPARATOR . 'uploadTmps';
            $this->setTempUploadDir($tempDir);
        }
        return $this->_tempUploadDir;
    }

    /**
     * @param string $value
     * @throws CException
     */
    public function setTempUploadDir($value )
    {
        if ($this->makeDir($value)) {
            $this->_tempUploadDir = $value;
        } else {
            throw new CException(Yii::t('yii', 'YsUploadStorage.tempUploadDir "{dir}" is invalid.
             Please make sure the directory exists and is writable by the Web server process.',
                array('{dir}' => $value)));
        }
    }


    /**
     * @param int $uid
     * @return string
     * 生一个文件名出来
     * ------------------
     * 要不要生文件夹？ 有待考虑
     * 如果用linux的话 文件系统准备用fastDfs
     * ------------------
     */
    public function genFileName($uid=0){
        $moduleId = $this->getCurrentModuleId();
        $fileName = $this->hash($moduleId)."_{$uid}_".str_replace(array(' ','.'),'',microtime()) ;
        return $fileName;
    }

    /**
     * @return string
     */
    protected function getCurrentModuleId(){
        $module = Yii::app()->getController()->getModule();
        if($module == null){
            /**
             *  $module = Yii::app();
             *   return $module->getId();
             */
            return 'app';
        }else{
            return $module->getId();
        }
    }

    /**
     * 获取上传路径
     *
     * @param int $uid
     * @return string
     */
    public function getSaveToPath($uid=0){
        return $this->getUploadDir(). DIRECTORY_SEPARATOR .$this->genFileName($uid);
    }

    //============================================================\\
    /**
     * 传递上传文件实例 返回文件存储的URI地址
     *
     * @param CUploadedFile $uploadFile
     * @throws CException
     * @return string
     */
    public function upload(CUploadedFile $uploadFile){
        $extName =  $uploadFile->getExtensionName();
        $saveToPath = $this->getSaveToPath(Yii::app()->user->getId()).".{$extName}";
         if($uploadFile->saveAs($saveToPath)){
            $basePath = $this->getBasePath() ;
             $relPath = substr($saveToPath, strlen($basePath) + 1);
             return   $this->uploadPath.'/'.str_replace(DIRECTORY_SEPARATOR, '/', ltrim($relPath, '/'));

         }  else{
            throw new CException('can not upload file to file path :'.$saveToPath);
         }

         }

    /**
     * 根据文件存储的URI 删除掉该文件
     *
     * @param string $fileUri
     * @return bool
     */
    public function deleteFile($fileUri=''){
        $filePath =  Yii::getPathOfAlias('webroot').'/'.ltrim(
                strtr($fileUri, array('/' => DIRECTORY_SEPARATOR, '\\' => DIRECTORY_SEPARATOR)),
                '/'
            );
        return unlink($filePath);
    }

    /**
     * @param string $fileURI
     * @param bool $absolute
     * @return string
     */
    public function getUrl($fileURI= '',$absolute=false){
        $baseUrl = Yii::app()->getBaseUrl($absolute);
        return  $baseUrl.'/'.ltrim($fileURI,'/');
    }

    /**
     * return the thumbnailUrl calculated by the fileURI
     *
     * @param string $fileUri
     * @param $height
     * @param int $width
     * @param string $suffix
     * @return string
     */
    public function getThumbUrl($fileUri='',$height,$width=0,$suffix=''){
        return Ys::thumbUrl($fileUri,$height,$width,$suffix);
    }
    //============================================================//

}