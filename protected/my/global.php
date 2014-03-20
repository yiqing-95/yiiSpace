<?php

/**
 * This is the shortcut to DIRECTORY_SEPARATOR
 */
defined('DS') or define('DS', DIRECTORY_SEPARATOR);

/**
 * @static
 * @param $url
 * @return string
 * 取baseUrl根目录下images/Css/Js/Assets的路径
 */
function imageUrl($url, $useBaseDir = false, $defaultDir = 'images')
{
    if (module() && !$useBaseDir)
        $imgPath = getAssetsUrl();
    else
        $imgPath = Yii::app()->baseUrl;
    return $imgPath . '/' . $defaultDir . '/' . $url;
}

function cssUrl($url, $useBaseDir = false, $defaultDir = 'css')
{
    if (module() && !$useBaseDir)
        $cssPath = getAssetsUrl();
    else
        $cssPath = Yii::app()->baseUrl;
    return $cssPath . '/' . $defaultDir . '/' . $url;
}

function jsUrl($url, $useBaseDir = false, $defaultDir = 'js')
{
    if (module() && !$useBaseDir)
        $jsPath = getAssetsUrl();
    else
        $jsPath = Yii::app()->baseUrl;
    return $jsPath . '/' . $defaultDir . '/' . $url;
}

function assetsUrl($url, $useBaseDir = false, $defaultDir = 'assets')
{
    if (module() && !$useBaseDir)
        $assetsPath = getAssetsUrl();
    else
        $assetsPath = Yii::app()->baseUrl;
    return $assetsPath . '/' . $defaultDir . '/' . $url;
}


/**
 * This is the shortcut to Yii::app()
 * @return CWebApplication
 */
function app()
{
    return Yii::app();
}

/**
 * @return CDbConnection
 */
function db()
{
    return Yii::app()->db;
}

/**
 * @return KSettings
 */
function settings()
{
    return Yii::app()->settings;
}

/**
 * This is the shortcut to Yii::app()->clientScript
 * @return CClientScript
 */
function cs()
{
    // You could also call the client script instance via Yii::app()->clientScript
    // But this is faster
    return Yii::app()->getClientScript();
}

/**
 * This is the shortcut to Yii::app()->user.
 * @return  YsWebUser|CWebUser|null
 */
function user()
{
    return Yii::app()->getUser();
}

/**
 * @return CHttpSession
 */
function session(){
    return Yii::app()->session ;
}

/**
 * This is the shortcut to Yii::app()->createUrl()
 */
function url($route, $params = array(), $ampersand = '&')
{
    return Yii::app()->createUrl($route, $params, $ampersand);
    // return Yii::app()->createUrl($route, $params, $ampersand) . ($anchor !== null ? '#' . $anchor : '');
}

/**
 * This is the shortcut to Yii::app()->createAbsoluteUrl()
 * @param string $route
 * @param array $params
 * @param string $anchor
 * @param string $ampersand
 * @return string 绝对url地址
 */
function aurl($route, array $params = array(), $schema = '', $anchor = null, $ampersand = '&')
{
    return Yii::app()->createAbsoluteUrl($route, $params, $schema, $ampersand) . ($anchor !== null ? '#' . $anchor
            : '');
}

/**
 * This is the shortcut to CHtml::encode
 */
function h($text)
{
    return htmlspecialchars($text, ENT_QUOTES, Yii::app()->charset);
    //  return CHtml::encode($text);
}

/**
 * This is the shortcut to CHtml::link()
 */
function l($text, $url = '#', $htmlOptions = array())
{
    return CHtml::link($text, $url, $htmlOptions);
}

function t($category, $message, $params = array(), $source = null, $language = null)
{
    $module = Yii::app()->controller->module;
    $prefix = '';
    if (!is_null($module)) {
        $prefix = ucfirst($module->id) . 'Module.';
    }
    //echo $prefix.$cateName;

    return Yii::t($prefix . $category, $message, $params, $source, $language);
}

/**
 * This is the shortcut to Yii::app()->request->baseUrl
 * If the parameter is given, it will be returned and prefixed with the app baseUrl.
 */
function bu($url = null)
{
    static $baseUrl;
    if ($baseUrl === null)
        $baseUrl = Yii::app()->getRequest()->getBaseUrl();
    return $url === null ? $baseUrl : $baseUrl . '/' . ltrim($url, '/');
}

/**
 * This is the shortcut to Yii::app()->request->getBaseUrl(true)
 * If the parameter is given, it will be returned and prefixed with the app absolute baseUrl.
 * @param string $url 相对url地址
 * @return string 返回绝对url地址
 */
function abu($url = null)
{
    static $baseUrl = null;
    if ($baseUrl === null)
        $baseUrl = rtrim(Yii::app()->request->getBaseUrl(true), '/') . '/';
    return $url === null ? $baseUrl : $baseUrl . ltrim($url, '/');
}


/**
 * This is the shortcut to Yii::app()->theme->baseUrl
 * If the parameter is given, it will be returned and prefixed with the app baseUrl.
 */
function tu($url = null)
{
    static $baseUrl;
    if ($baseUrl === null)
        $baseUrl = Yii::app()->theme->getBaseUrl();
    return $url === null ? $baseUrl : $baseUrl . '/' . ltrim($url, '/');
}

/**
 * Returns the named application parameter.
 * This is the shortcut to Yii::app()->params[$name].
 */
function param($name)
{
    return Yii::app()->params[$name];
}

/**
 * wrapper to trace variable on FirePHP
 *
 * @param $what variable to dumping
 */
function fb($what)
{
    echo Yii::trace(CVarDumper::dumpAsString($what), 'vardump');
}


/**
 * this is the shortcut to Yii::app()->theme->baseUrl
 * @param string $url
 * @return string Yii::app()->theme->baseUrl
 */
function tbu($url = null)
{
    static $themeBaseUrl = null;
    if ($themeBaseUrl === null)
        $themeBaseUrl = rtrim(Yii::app()->theme->baseUrl, '/') . '/';
    return $url === null ? $themeBaseUrl : $themeBaseUrl . ltrim($url, '/');
}

/**
 * This is the shortcut to Yii::app()->authManager.
 * @return IAuthManager Yii::app()->authManager
 */
function auth()
{
    return Yii::app()->authManager;
}

/**
 * This is the shortcut to Yii::app()->getStatePersister().
 * @return CStatePersister
 */
function sp()
{
    return Yii::app()->getStatePersister();
}

/**
 * This is the shortcut to Yii::app()->getSecurityManager().
 * @return CSecurityManager
 */
function sm()
{
    return Yii::app()->getSecurityManager();
}

/**
 * This is the shortcut to Yii::app()->getAssetManager().
 * @return CAssetManager
 */
function am()
{
    return Yii::app()->getAssetManager();
}

/**
 * This is the shortcut to Yii::app()->request
 * @return CHttpRequest
 */
function request()
{
    return Yii::app()->request;
}

/**
 * @param $data
 * @param bool $return
 * @return string
 */
function renderJson($data, $return = false)
{
    $output = CJSON::encode($data);
    if ($return)
        return $output;
    else {
        header("HTTP/1.0 200 OK");
        header('Content-type: text/json; charset=' . Yii::app()->charset);
        header('Content-Length: ' . strlen($output));
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
        echo $output;
    }
}

/**
 * ------------------------------
 * yiqing 2011/5/15
 * 获取客户端ip地址
 *--------------------------------
 * @return string
 * 注释部分是以前的简化模式
 * function getClientIp()
 * {
 *      if ($_SERVER['HTTP_CLIENT_IP']) {
 *          $ip = $_SERVER['HTTP_CLIENT_IP'];
 *      } elseif ($_SERVER['HTTP_X_FORWARDED_FOR']) {
 *          $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
 *       } else {
 *          $ip = $_SERVER['REMOTE_ADDR'];
 *      }
 *      return $ip;
 * }
 * 以下是最新最全的获取客户端ＩＰ地址的方式，同时对获得的ＩＰ地址进行校验
 */
function getClientIp()
{
    /*foreach (array(
        'HTTP_CLIENT_IP',
        'HTTP_X_FORWARDED_FOR',
        'HTTP_X_FORWARDED',
        'HTTP_X_CLUSTER_CLIENT_IP',
        'HTTP_FORWARDED_FOR',
        'HTTP_FORWARDED',
        'REMOTE_ADDR') as $key) {
        if (!empty($_SERVER[$key])) {
            foreach (explode(',', $_SERVER[$key]) as $ip) {
                $ip = trim($ip);
                if ((bool)filter_var($ip, FILTER_VALIDATE_IP,
                                     FILTER_FLAG_IPV4 |
                                     FILTER_FLAG_NO_PRIV_RANGE |
                                     FILTER_FLAG_NO_RES_RANGE)
                ) {
                    return $ip;
                }
            }
        }
    }
    return "unknownIp";*/
    if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
        $ip = getenv("HTTP_CLIENT_IP");
    else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
        $ip = getenv("HTTP_X_FORWARDED_FOR");
    else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
        $ip = getenv("REMOTE_ADDR");
    else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
        $ip = $_SERVER['REMOTE_ADDR'];
    else
        $ip = "unknown";
    return ($ip);
}

function dump($target)
{
    return CVarDumper::dump($target, 10, true);
}

/**
 * 创建上传的文件名
 * @param string $filename 可以传递一个原始文件名，如果没有此参数返回默认文件名
 * @return string
 */
function createFileName($filename = '')
{
    return empty ($filename) ? date('d') . '_' . time() . '_' . md5(time())
            : date('d') . '_' . time() . '_' . md5_file($filename);
}

/**
 * @param string $moduleName
 * @return
 * CHtml::asset() 方法的意义！
 */
function getAssetsUrl($moduleName = '')
{
    if ($moduleName)
        return Yii::app()->getModule($moduleName)->getAssetsUrl();
    else
        return module()->getAssetsUrl();
}

/**
 * 查找Assets目录
 * @param string $path
 * @param bool $hashByName
 * @return
 */
function getAssets($obj, $defaultDir = 'assets')
{
    $rc = new ReflectionClass(get_class($obj));
    $path = dirname($rc->getFileName()) . DIRECTORY_SEPARATOR . $defaultDir;
    if ($path === false || !is_dir($path))
        return;
    return Yii::app()->getAssetManager()->publish($path, false, -1, YII_DEBUG);
}

/**
 * @param  $keywords
 * @param  $subject
 * @param string $color
 * @param string $bgColor
 * @param string $fontSize
 * @return mixed
 */
function linkKeywords($keywords, $subject)
{
    //防止空格出现
    $keywords = preg_split("/[\s]+/", $keywords);

    $pattern = is_array($keywords) ? '/' . implode("|", $keywords) . "/" : '/' . $keywords . '/';
    //echo $pattern;
    // return preg_replace_callback($pattern,"highlightMatch",$subject);
    $replaceTo = '"<a href=\'livmx.com/index.php/keywords\'>".$matches[1].\'</a>\';';
    $func_code = 'return ' . $replaceTo;
    // $func_code = sprintf($func_code, $color, $bgColor, $fontSize);
    // echo 'pt:' . $pattern;
    return preg_replace_callback(
        $pattern,
        create_function(
        // 这里使用单引号很关键，
        // 否则就把所有的 $ 换成 \$
            '$matches',
            $func_code
        ),
        $subject);
}

/**
 * @return LEventDispatcher
 */
function ed()
{
    return Yii::app()->ed;
}

/**
 * @return CController
 * 获取当前上下文中的正在运行的那个控制器
 * 一般用此来判断运行环境，比如是在主app中还是
 * 在某个模块中
 */
function controller()
{
    return Yii::app()->getController();
}

/**
 * @return CAction
 */
function action()
{
    return Yii::app()->getController()->getAction();
}

/**
 * @return LBaseModule 没有则返回null
 */
function module()
{
    return Yii::app()->getController()->getModule();
}

/**
 * @return string 返回当前的路由
 */
function route()
{
    return Yii::app()->getController()->getRoute();
}

/**
 * @return string 返回当前模块的导航路径 如果不是在模块中那么返回application
 */
function moduleNav()
{
    $navPath = '';
    $currModule = Yii::app()->getController()->getModule();
    if ($currModule == null) {
        $navPath = 'application';
    } else {
        $navPath = $currModule->getId();
        while ($currModule = $currModule->getParentModule() != null) {
            $navPath = $currModule->getId() . '.' . $navPath;
        }
    }
    return $navPath;
}

/**
 * @return HookManager
 */
function hookManager()
{
    return HookManager::instance();
}

/**
 * @return HookManager
 */
function hookMngr()
{
    return HookManager::instance();
}

function getAvatarDir($uid, $isRelative = false)
{
    $relativePath = 'data/avatars/' . $uid . '/';
    return $isRelative ? $relativePath : Yii::app()->getBasePath() . '/../' . $relativePath;
    #  mkdir(str_replace('//','/',$folderPath), 0755, true);
}

/**
 * @return LTriggerManager
 */
function triggerMngr()
{
    return Yii::app()->triggerManager;
}

/**
 *
 */
if (!function_exists('lcfirst')) {
    if (function_exists('lcfirst') === false) {
        function lcfirst($str)
        {
            $str[0] = strtolower($str[0]);
            return $str;
        }
    }
}

/**
 * @return CDateFormatter
 */
function dateFormatter()
{
    return Yii::app()->getDateFormatter();
}

/**
 * @param  $uid
 * @param bool $isRelative
 * @return string
 * =================================
 * 获取用户上传路径
 * 原始文件存储路径 按月分存
 * =================================
 */
function getUploadDir($uid, $isRelative = false)
{
    if (empty($uid)) {
        $uid = 'temp';
    }
    //$relativePath =   'data/uploads/'.$uid.'/'.date('Y').date('m'.'/');
    // return  $isRelative ? $relativePath :Yii::app()->getBasePath().'/../'. $relativePath;
    $path = Yii::getPathOfAlias('webroot.data.uploads.' . $uid . '.' . date('Y') . date('m'));

    if (!is_dir($path)) {
        mkdir($path, 0777, true);
    }
    return $path;
}

/**
 * @param $uid
 * @param bool $absolute
 * @return string
 *  获取个人上传路径的url地址 一般常跟getUploadDir 一起使用
 */
function getUploadBaseUrl($uid, $absolute = false)
{
    if (empty($uid)) {
        $uid = 'temp';
    }
    return Yii::app()->getRequest()->getBaseUrl($absolute) . '/data/uploads/' . $uid . '/' . date('Y') . date('m');
}

/**
 * @throws CException
 * @param string $realPath
 * @param bool $absolute 是否是绝对url 默认是false
 *    绝对url对于别站访问或者多域名情况很有用
 * @return string
 * 根据传递的实际地址获取可从浏览器访问的url地址
 * 要求参数地址必须 位于webroot可访问的目录下
 */
function getUrlFromRealPath($realPath = '', $absolute = false)
{

    $realPath = strtr($realPath,array('/'=>DIRECTORY_SEPARATOR,'\\'=>DIRECTORY_SEPARATOR));
    $webRootPath = Yii::getPathOfAlias('webroot');
   // die($realPath . __FILE__ .'|'.$webRootPath);
    if (strpos($realPath, $webRootPath) === 0) {
        return Yii::app()->getRequest()->getBaseUrl($absolute) . substr($realPath, strlen($webRootPath));
    } else {
        throw new CException('the path you give must under the webroot ,the path is' . $realPath);
    }
}

/**
 * @param  $uid
 * @param bool $isRelative
 * @return string
 * ==============================================
 * 获取用户的存储路径 是经过图片处理后的位置
 * 区别于 uploadDir  前者是用来存放原始文件的路径
 * ===============================================
 */
function getSaveDir($uid, $isRelative = false)
{
    if (null == ($module = controller()->getModule())) {
        $moduleId = 'user';
    } else {
        $moduleId = $module->id;
    }

    if (empty($uid)) {
        $uid = 'temp';
    }

    $path = Yii::getPathOfAlias('webroot.data.' . $moduleId . '.' . $uid . '.' . date('Y') . date('m'));

    if (!is_dir($path)) {
        mkdir($path, 0777, true);
    }
    return $path;

}

/**
 * @param $uid
 * @param bool $absolute
 * @return string
 *  获取个人上传路径的url地址 一般常跟getSaveDir 一起使用
 */
function getSaveBaseUrl($uid, $absolute = false)
{
    if (null == ($module = controller()->getModule())) {
        $moduleId = 'user';
    } else {
        $moduleId = $module->id;
    }

    if (empty($uid)) {
        $uid = 'temp';
    }

    return Yii::app()->getRequest()->getBaseUrl($absolute) . '/data/' . $moduleId . '/' . $uid . '/' . date('Y') . date('m');
}


/**
 * @param string $uid
 * @return void
 */
function genFileName($uid = '0')
{
    if ($uid == '0' && !user()->getIsGuest()) {
        $uid = user()->getId();
    }
    return substr(md5(moduleNav()), 8, 4) . '_' . $uid . '_' . time() . '_' . substr(mt_rand(), 0, 8);
}

/**
 * @throws ValueException
 * @param $object
 * @return array
 * 获取对象的公共属性
 * 也可以写成自己类 的一个静态方法
 * -------------------------------------------
 * 注意与用反射获取的内容略有不同
 * 反射可以获取改属性是否是父类的
 *
 *  $rc = new ReflectionClass($obj);
 *      $properties = $rc->getProperties();
 *       print_r($properties);
 *       print_r(get_object_vars($testModule));
 *
 * 另外还有一个方法 ， 有空搞一下：
 * get_class_vars
 *
 */
function getPublicProperties($object)
{
    /*
    $getFields = function($obj)
    {
        return get_object_vars($obj);
    };
    return $getFields($object);
    /*
    //下面的实现不管用的 还是等价调用get_object_vars 上面用到5.3 的新特性 或者参考使用create_function方法
    $result = get_object_vars($object);
    if ($result === NULL or $result === FALSE) {
        throw new ValueException('Given $object parameter is not an object.');
    }
    return $result;
    */
}

function getPublicVarsOfObject($obj)
{
    // return get_object_vars($obj);
    $getFields = create_function('$obj', 'return get_object_vars($obj);');
    return $getFields($obj); // Returns only 'name' and 'publicFlag'
}

/**
 * @param $srcObj
 * @param $dstObj
 * @return void
 *  将一个对象的属性拷贝到另一个对象上
 */
function  copyProperties($srcObj, $dstObj)
{
    $srcObjProperties = get_object_vars($srcObj);
    foreach ($srcObjProperties as $prop => $val) {
        if (property_exists(get_class($dstObj), $prop)) {
            $dstObj->$prop = $val;
        }
    }
}

/**
 * Send mail method
 */
function sendMail($email, $subject, $message)
{

    $mail = Yii::app()->mailer;
    $mail->CharSet = "UTF-8";
    $message = $message;
    $mail->Host = 'smtp.163.com';
    $mail->Port = 25;
    $mail->IsSMTP();
    $mail->SMTPAuth = true;
    $mail->Username = "yii_qing@163.com"; //你的用户名，或者完整邮箱地址
    $mail->Password = "yiqing"; //邮箱密码
    $mail->SetFrom('yii_qing@163.com', 'yiqing95'); //发送的邮箱和发送人
    $mail->AddAddress($email);
    //		$mail->AddAddress('61391362@qq.com');
    $mail->Subject = $subject;
    $mail->Body = $message;
    $mail->IsHTML(true); // 是否使用HTML格
    return $mail->Send();

    try {
        $adminEmail = Yii::app()->params['adminEmail'];
        $headers = "MIME-Version: 1.0\r\nFrom: $adminEmail\r\nReply-To: $adminEmail\r\nContent-Type: text/html; charset=utf-8";
        $message = wordwrap($message, 70);
        $message = str_replace("\n.", "\n..", $message);
        return mail($email, '=?UTF-8?B?' . base64_encode($subject) . '?=', $message, $headers);
    } catch (Exception $ex) {

        $mail = Yii::app()->mailer;
        $mail->CharSet = "UTF-8";
        $message = $message;
        $mail->Host = 'smtp.163.com';
        $mail->Port = 25;
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->Username = "yii_qing@163.com"; //你的用户名，或者完整邮箱地址
        $mail->Password = "yiqing^"; //邮箱密码
        $mail->SetFrom('yii_qing@163.com', 'yiqing95'); //发送的邮箱和发送人
        $mail->AddAddress($email);
        //		$mail->AddAddress('61391362@qq.com');
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->IsHTML(true); // 是否使用HTML格
        return $mail->Send();
    }

}


/**
 * @param $url
 * @param $params  要传递的参数
 * @param string $type  请求方法 must equal 'GET' or 'POST'
 * @return void
 * 异步调用方法的实现策略
 *
 * 蛋疼估计对于yii的模式重写有问题
 */
function curl_request_async($url, $params, $type = 'POST')
{
    foreach ($params as $key => &$val) {
        if (is_array($val)) $val = implode(',', $val);
        $post_params[] = $key . '=' . urlencode($val);
    }
    $post_string = implode('&', $post_params);

    $parts = parse_url($url);

    $fp = fsockopen($parts['host'],
                    isset($parts['port']) ? $parts['port'] : 80,
                    $errno, $errstr, 30);

    // Data goes in the path for a GET request
    if ('GET' == $type) $parts['path'] .= '?' . $post_string;

    $out = "$type " . $parts['path'] . " HTTP/1.1\r\n";
    $out .= "Host: " . $parts['host'] . "\r\n";
    $out .= "Content-Type: application/x-www-form-urlencoded\r\n";
    $out .= "Content-Length: " . strlen($post_string) . "\r\n";
    $out .= "Connection: Close\r\n\r\n";
    // Data goes in the request body for a POST request
    if ('POST' == $type && isset($post_string)) $out .= $post_string;

    fwrite($fp, $out);
    fclose($fp);
}


/**
 * @return string
 * 获取被卸载的模块所记录的路径
 * 注意配置文件中访问这个方法会出错 那时候别名还没有被设置
 *
 */
function  getUnInstalledModulesFile()
{
    return Yii::getPathOfAlias('application.config.modules.unInstalled') . DIRECTORY_SEPARATOR . 'unInstalledModules.php';
}

/**
 * @return array|mixed
 * 获取被卸载的模块列表
 */
function getUnInstalledModules($basePath = __FILE__)
{
    //注意这个要跟 global中的 getUnInstalledModulesFile 方法要同步
    $unInstallFilePath = 'unInstalled' . DIRECTORY_SEPARATOR . 'unInstalledModules.php';
    $file = $basePath . DIRECTORY_SEPARATOR . $unInstallFilePath;
    if (file_exists($file)) {
        return require($file);
    } else {
        return array();
    }
}

/**
 * @param string $source
 * @param string $dest
 * @return bool
 * ----------------------------------
 * 文件拷贝 可以实现远程文件的抓取 支持http/https/ftp协议
 * 内部使用crul库 所以最好开启这个扩展
 * ----------------------------------
 */
function xCopy($source, $dest)
{
    $res = @copy($source, $dest);
    if ($res) {
        chmod($dest, 0777);
        return TRUE;
    }
    if (function_exists('curl_init') && preg_match('/^(http|https|ftp)\:\/\//u', $source)) {
        $dst = fopen($dest, 'w');
        if (!$dst) {
            return FALSE;
        }
        $ch = curl_init();
        curl_setopt_array($ch, array(
                                    CURLOPT_FILE => $dst,
                                    CURLOPT_HEADER => FALSE,
                                    CURLOPT_URL => $source,
                                    CURLOPT_CONNECTTIMEOUT => 3,
                                    CURLOPT_TIMEOUT => 5,
                                    CURLOPT_MAXREDIRS => 5,
                                    CURLOPT_REFERER => "yiqing95.gicp.net",
                                    CURLOPT_USERAGENT => isset($_SERVER['HTTP_USER_AGENT'])
                                            ? trim($_SERVER['HTTP_USER_AGENT'])
                                            : 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.1) Gecko/2008070208 Firefox/3.0.1',
                               ));
        @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        $res = curl_exec($ch);
        fclose($dst);
        if (!$res) {
            curl_close($ch);
            return FALSE;
        }
        if (curl_errno($ch)) {
            curl_close($ch);
            return FALSE;
        }
        curl_close($ch);
        chmod($dest, 0777);
        return TRUE;
    }
    return FALSE;
}

/**
 * 获取给定URL的短地址
 *
 * @param string $url
 * @return string
 */
function getShortUrl($url)
{
    return ShortUrlUtil::getShort(CHtml::decode($url));
}

/**
 * 去除文件中的注释
 * @param $buffer
 * @return mixed
 */
function compress($buffer)
{
    $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
    return $buffer;
}

/**
 * 获取给定IP的物理地址
 *
 * @param string $ip
 * @return string
 */
function convert_ip($ip, $accurate = false)
{
    $return = '';
    if (preg_match("/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/", $ip)) {
        $iparray = explode('.', $ip);
        if ($iparray[0] == 10 || $iparray[0] == 127 || ($iparray[0] == 192 && $iparray[1] == 168) || ($iparray[0] == 172 && ($iparray[1] >= 16 && $iparray[1] <= 31))) {
            $return = 'LAN';
        } elseif ($iparray[0] > 255 || $iparray[1] > 255 || $iparray[2] > 255 || $iparray[3] > 255) {
            $return = 'Invalid IP Address';
        } else {
            $tinyipfile = Yii::app()->basePath . '/common/lib/tinyipdata.dat';
            $fullipfile = Yii::app()->basePath . '/common/lib/wry.dat';
            if (!$accurate && @file_exists($tinyipfile)) {
                $return = convert_ip_tiny($ip, $tinyipfile);
            } elseif ($accurate && @file_exists($fullipfile)) {
                $return = convert_ip_full($ip, $fullipfile);
                $return = iconv('GBK', 'UTF-8', $return);
            }
        }
    }
    return $return;
}

/**
 * @see convert_ip()
 */
function convert_ip_tiny($ip, $ipdatafile)
{
    static $fp = NULL, $offset = array(), $index = NULL;

    $ipdot = explode('.', $ip);
    $ip = pack('N', ip2long($ip));

    $ipdot[0] = (int)$ipdot[0];
    $ipdot[1] = (int)$ipdot[1];

    if ($fp === NULL && $fp = @fopen($ipdatafile, 'rb')) {
        $offset = unpack('Nlen', fread($fp, 4));
        $index = fread($fp, $offset['len'] - 4);
    } elseif ($fp == FALSE) {
        return 'Invalid IP data file';
    }

    $length = $offset['len'] - 1028;
    $start = unpack('Vlen', $index[$ipdot[0] * 4] . $index[$ipdot[0] * 4 + 1] . $index[$ipdot[0] * 4 + 2] . $index[$ipdot[0] * 4 + 3]);

    for ($start = $start['len'] * 8 + 1024; $start < $length; $start += 8) {

        if ($index{$start} . $index{$start + 1} . $index{$start + 2} . $index{$start + 3} >= $ip) {
            $index_offset = unpack('Vlen', $index{$start + 4} . $index{$start + 5} . $index{$start + 6} . "\x0");
            $index_length = unpack('Clen', $index{$start + 7});
            break;
        }
    }

    fseek($fp, $offset['len'] + $index_offset['len'] - 1024);
    if ($index_length['len']) {
        return fread($fp, $index_length['len']);
    } else {
        return 'Unknown';
    }

}

/**
 * @see convert_ip()
 */
function convert_ip_full($ip, $ipdatafile)
{
    if (!$fd = @fopen($ipdatafile, 'rb')) {
        return 'Invalid IP data file';
    }

    $ip = explode('.', $ip);
    $ipNum = $ip [0] * 16777216 + $ip [1] * 65536 + $ip [2] * 256 + $ip [3];

    if (!($DataBegin = fread($fd, 4)) || !($DataEnd = fread($fd, 4)))
        return;
    @$ipbegin = implode('', unpack('L', $DataBegin));
    if ($ipbegin < 0)
        $ipbegin += pow(2, 32);
    @$ipend = implode('', unpack('L', $DataEnd));
    if ($ipend < 0)
        $ipend += pow(2, 32);
    $ipAllNum = ($ipend - $ipbegin) / 7 + 1;

    $BeginNum = $ip2num = $ip1num = 0;
    $ipAddr1 = $ipAddr2 = '';
    $EndNum = $ipAllNum;

    while ($ip1num > $ipNum || $ip2num < $ipNum) {
        $Middle = intval(($EndNum + $BeginNum) / 2);

        fseek($fd, $ipbegin + 7 * $Middle);
        $ipData1 = fread($fd, 4);
        if (strlen($ipData1) < 4) {
            fclose($fd);
            return 'System Error';
        }
        $ip1num = implode('', unpack('L', $ipData1));
        if ($ip1num < 0)
            $ip1num += pow(2, 32);

        if ($ip1num > $ipNum) {
            $EndNum = $Middle;
            continue;
        }

        $DataSeek = fread($fd, 3);
        if (strlen($DataSeek) < 3) {
            fclose($fd);
            return 'System Error';
        }
        $DataSeek = implode('', unpack('L', $DataSeek . chr(0)));
        fseek($fd, $DataSeek);
        $ipData2 = fread($fd, 4);
        if (strlen($ipData2) < 4) {
            fclose($fd);
            return 'System Error';
        }
        $ip2num = implode('', unpack('L', $ipData2));
        if ($ip2num < 0)
            $ip2num += pow(2, 32);

        if ($ip2num < $ipNum) {
            if ($Middle == $BeginNum) {
                fclose($fd);
                return 'Unknown';
            }
            $BeginNum = $Middle;
        }
    }

    $ipFlag = fread($fd, 1);
    if ($ipFlag == chr(1)) {
        $ipSeek = fread($fd, 3);
        if (strlen($ipSeek) < 3) {
            fclose($fd);
            return 'System Error';
        }
        $ipSeek = implode('', unpack('L', $ipSeek . chr(0)));
        fseek($fd, $ipSeek);
        $ipFlag = fread($fd, 1);
    }

    if ($ipFlag == chr(2)) {
        $AddrSeek = fread($fd, 3);
        if (strlen($AddrSeek) < 3) {
            fclose($fd);
            return 'System Error';
        }
        $ipFlag = fread($fd, 1);
        if ($ipFlag == chr(2)) {
            $AddrSeek2 = fread($fd, 3);
            if (strlen($AddrSeek2) < 3) {
                fclose($fd);
                return 'System Error';
            }
            $AddrSeek2 = implode('', unpack('L', $AddrSeek2 . chr(0)));
            fseek($fd, $AddrSeek2);
        } else {
            fseek($fd, -1, SEEK_CUR);
        }

        while (($char = fread($fd, 1)) != chr(0))
            $ipAddr2 .= $char;

        $AddrSeek = implode('', unpack('L', $AddrSeek . chr(0)));
        fseek($fd, $AddrSeek);

        while (($char = fread($fd, 1)) != chr(0))
            $ipAddr1 .= $char;
    } else {
        fseek($fd, -1, SEEK_CUR);
        while (($char = fread($fd, 1)) != chr(0))
            $ipAddr1 .= $char;

        $ipFlag = fread($fd, 1);
        if ($ipFlag == chr(2)) {
            $AddrSeek2 = fread($fd, 3);
            if (strlen($AddrSeek2) < 3) {
                fclose($fd);
                return 'System Error';
            }
            $AddrSeek2 = implode('', unpack('L', $AddrSeek2 . chr(0)));
            fseek($fd, $AddrSeek2);
        } else {
            fseek($fd, -1, SEEK_CUR);
        }
        while (($char = fread($fd, 1)) != chr(0))
            $ipAddr2 .= $char;
    }
    fclose($fd);

    if (preg_match('/http/i', $ipAddr2)) {
        $ipAddr2 = '';
    }
    $ipaddr = "$ipAddr1 $ipAddr2";
    $ipaddr = preg_replace('/CZ88\.NET/is', '', $ipaddr);
    $ipaddr = preg_replace('/^\s*/is', '', $ipaddr);
    $ipaddr = preg_replace('/\s*$/is', '', $ipaddr);
    if (preg_match('/http/i', $ipaddr) || $ipaddr == '') {
        $ipaddr = 'Unknown';
    }

    return $ipaddr;

}

/**
+----------------------------------------------------------
 * 字符串截取，支持中文和其它编码
+----------------------------------------------------------
 * @static
 * @access public
+----------------------------------------------------------
 * @param string $str 需要转换的字符串
 * @param string $start 开始位置
 * @param string $length 截取长度
 * @param string $charset 编码格式
 * @param string $suffix 截断显示字符
+----------------------------------------------------------
 * @return string
+----------------------------------------------------------
 */
function msubstr($str, $start = 0, $length, $charset = "utf-8", $suffix = true)
{
    if (function_exists("mb_substr"))
        $slice = mb_substr($str, $start, $length, $charset);
    elseif (function_exists('iconv_substr')) {
        $slice = iconv_substr($str, $start, $length, $charset);
    } else {
        $re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join("", array_slice($match[0], $start, $length));
    }
    if ($suffix && $str != $slice) return $slice . "...";
    return $slice;
}

/**
+----------------------------------------------------------
 * 字符串截取，支持中文和其它编码
+----------------------------------------------------------
 * @static
 * @access public
+----------------------------------------------------------
 * @param string $str 需要转换的字符串
 * @param string $length 截取长度
 * @param string $charset 编码格式
 * @param string $suffix 截断显示字符
+----------------------------------------------------------
 * @return string
+----------------------------------------------------------
 */
function mStr($str, $length, $charset = "utf-8", $suffix = true)
{
    return msubstr($str, 0, $length, $charset, $suffix);
}

/**
+----------------------------------------------------------
 * 产生随机字串，可用来自动生成密码 默认长度6位 字母和数字混合
+----------------------------------------------------------
 * @param string $len 长度
 * @param string $type 字串类型
 * 0 字母 1 数字 其它 混合
 * @param string $addChars 额外字符
+----------------------------------------------------------
 * @return string
+----------------------------------------------------------
 */
function rand_string($len = 6, $type = '', $addChars = '')
{
    $str = '';
    switch ($type) {
        case 0:
            $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz' . $addChars;
            break;
        case 1:
            $chars = str_repeat('0123456789', 3);
            break;
        case 2:
            $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' . $addChars;
            break;
        case 3:
            $chars = 'abcdefghijklmnopqrstuvwxyz' . $addChars;
            break;
        default :
            // 默认去掉了容易混淆的字符oOLl和数字01，要添加请使用addChars参数
            $chars = 'ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789' . $addChars;
            break;
    }
    if ($len > 10) { //位数过长重复字符串一定次数
        $chars = $type == 1 ? str_repeat($chars, $len) : str_repeat($chars, 5);
    }
    $chars = str_shuffle($chars);
    $str = substr($chars, 0, $len);
    return $str;
}

/**
+----------------------------------------------------------
 * 字节格式化 把字节数格式为 B K M G T 描述的大小
+----------------------------------------------------------
 * @return string
+----------------------------------------------------------
 */
function byte_format($size, $dec = 2)
{
    $a = array("B", "KB", "MB", "GB", "TB", "PB");
    $pos = 0;
    while ($size >= 1024) {
        $size /= 1024;
        $pos++;
    }
    return round($size, $dec) . " " . $a[$pos];
}

/**
+----------------------------------------------------------
 * 检查字符串是否是UTF8编码
+----------------------------------------------------------
 * @param string $string 字符串
+----------------------------------------------------------
 * @return Boolean
+----------------------------------------------------------
 */
function is_utf8($string)
{
    return preg_match('%^(?:
		 [\x09\x0A\x0D\x20-\x7E]            # ASCII
	   | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
	   |  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
	   | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
	   |  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
	   |  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
	   | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
	   |  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
   )*$%xs', $string);
}

//加密函数
function encrypt($txt, $key = null)
{
    if (empty ($key))
        $key = C('SECURE_CODE');
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-=+";
    $nh = rand(0, 64);
    $ch = $chars [$nh];
    $mdKey = md5($key . $ch);
    $mdKey = substr($mdKey, $nh % 8, $nh % 8 + 7);
    $txt = base64_encode($txt);
    $tmp = '';
    $i = 0;
    $j = 0;
    $k = 0;
    for ($i = 0; $i < strlen($txt); $i++) {
        $k = $k == strlen($mdKey) ? 0 : $k;
        $j = ($nh + strpos($chars, $txt [$i]) + ord($mdKey [$k++])) % 64;
        $tmp .= $chars [$j];
    }
    return $ch . $tmp;
}

//解密函数
function decrypt($txt, $key = null)
{
    if (empty ($key))
        $key = C('SECURE_CODE');
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-=+";
    $ch = $txt [0];
    $nh = strpos($chars, $ch);
    $mdKey = md5($key . $ch);
    $mdKey = substr($mdKey, $nh % 8, $nh % 8 + 7);
    $txt = substr($txt, 1);
    $tmp = '';
    $i = 0;
    $j = 0;
    $k = 0;
    for ($i = 0; $i < strlen($txt); $i++) {
        $k = $k == strlen($mdKey) ? 0 : $k;
        $j = strpos($chars, $txt [$i]) - $nh - ord($mdKey [$k++]);
        while ($j < 0)
            $j += 64;
        $tmp .= $chars [$j];
    }
    return base64_decode($tmp);
}

/**
 * DES加密函数
 *
 * @param string $input
 * @param string $key
 */
function desencrypt($input, $key)
{
    $size = mcrypt_get_block_size('des', 'ecb');
    $input = pkcs5_pad($input, $size);

    $td = mcrypt_module_open('des', '', 'ecb', '');
    $iv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
    @mcrypt_generic_init($td, $key, $iv);
    $data = mcrypt_generic($td, $input);
    mcrypt_generic_deinit($td);
    mcrypt_module_close($td);
    $data = base64_encode($data);
    return $data;
}

/**
 * DES解密函数
 *
 * @param string $input
 * @param string $key
 */
function desdecrypt($encrypted, $key)
{
    $encrypted = base64_decode($encrypted);
    $td = mcrypt_module_open('des', '', 'ecb', ''); //使用MCRYPT_DES算法,cbc模式
    $iv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
    $ks = mcrypt_enc_get_key_size($td);
    @mcrypt_generic_init($td, $key, $iv); //初始处理

    $decrypted = mdecrypt_generic($td, $encrypted); //解密

    mcrypt_generic_deinit($td); //结束
    mcrypt_module_close($td);

    $y = pkcs5_unpad($decrypted);
    return $y;
}

/**
 * @see desencrypt()
 */
function pkcs5_pad($text, $blocksize)
{
    $pad = $blocksize - (strlen($text) % $blocksize);
    return $text . str_repeat(chr($pad), $pad);
}

/**
 * @see desdecrypt()
 */
function pkcs5_unpad($text)
{
    $pad = ord($text{strlen($text) - 1});

    if ($pad > strlen($text))
        return false;
    if (strspn($text, chr($pad), strlen($text) - $pad) != $pad)
        return false;

    return substr($text, 0, -1 * $pad);
}

/**
 * 检查是否是以手机浏览器进入(IN_MOBILE)
 */
function isMobile()
{
    $mobile = array();
    static $mobilebrowser_list = 'Mobile|iPhone|Android|WAP|NetFront|JAVA|OperasMini|UCWEB|WindowssCE|Symbian|Series|webOS|SonyEricsson|Sony|BlackBerry|Cellphone|dopod|Nokia|samsung|PalmSource|Xphone|Xda|Smartphone|PIEPlus|MEIZU|MIDP|CLDC';
    //note 获取手机浏览器
    if (preg_match("/$mobilebrowser_list/i", $_SERVER['HTTP_USER_AGENT'], $mobile)) {
        return true;
    } else {
        if (preg_match('/(mozilla|chrome|safari|opera|m3gate|winwap|openwave)/i', $_SERVER['HTTP_USER_AGENT'])) {
            return false;
        } else {
            if ($_GET['mobile'] === 'yes') {
                return true;
            } else {
                return false;
            }
        }
    }
}

/**
 * 检查给定的用户名是否合法
 *
 * 合法的用户名由2-10位的中英文/数字/下划线/减号组成
 *
 * @param string $username
 * @return boolean
 */
function isLegalUsername($username)
{
    // GB2312: preg_match("/^[".chr(0xa1)."-".chr(0xff)."A-Za-z0-9_-]+$/", $username)
    return preg_match("/^[\x{4e00}-\x{9fa5}A-Za-z0-9_-]+$/u", $username) &&
           mb_strlen($username, 'UTF-8') >= 2 &&
           mb_strlen($username, 'UTF-8') <= 10;
}

/**
 * 将ＡＲ集转化成指定key的数组集
 * @param $ARs
 * @param $key
 * @param $relation
 * @return array
 */
function getArToArray($ARs, $key, $relation = '')
{
    if (empty($ARs))
        return;

    $key = is_array($key) ? $key : explode(',', $key);
    $key = array_map('trim', $key);
    $res = array();
    foreach ($ARs as $v1)
        foreach ($key as $v2) {
            if (!empty($relation))
                $v1 = $v1->$relation;
            if (isset($v1->$v2))
                $res[$v2][] = $v1->$v2;
        }

    return $res;
}

/**
 * 将一个二维数组中的某个固定键的值取出，形成一个新的一维数组
 * @param $pArray 一个二维数组
 * @param string $pKey 数组的键的名称
 * @param string $pCondition
 * @return array 返回新的一维数组
 */
function getSubByKey($pArray, $pKey = "", $pCondition = "")
{
    $result = array();
    foreach ($pArray as $temp_array) {
        if (is_object($temp_array)) {
            $temp_array = (array)$temp_array;
        }
        if (("" != $pCondition && $temp_array[$pCondition[0]] == $pCondition[1]) || "" == $pCondition) {
            $result[] = ("" == $pKey) ? $temp_array : isset($temp_array[$pKey]) ? $temp_array[$pKey] : "";
        }
    }
    return $result;
}

/**
 * @return LFeedNoticeManager
 */
function feedNoticeMngr()
{
    return Yii::app()->actionFeedNoticeMngr;

}

/**
 * @return EFile
 * 之所以返回EFile 仅仅是为了自动补全
 * 原来的扩展的api doc不太全 新写一个哑类EFile 修改文档
 * 实际并没有使用EFile类
 */
function cfile()
{
    return Yii::app()->file;
}
