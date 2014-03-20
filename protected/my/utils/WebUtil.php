<?php
/**
 * Created by JetBrains PhpStorm.
 * User: cztx
 * Date: 11-5-12
 * Time: 下午1:01
 * To change this template use File | Settings | File Templates.
 */

class WebUtil
{
    /**
     * @param $containerId
     * @param $url
     * @param string $callback
     */
    public static function ajaxLoad($containerId,$url,$callback='function(res){}'){
        $script = <<<EOD
        $(function(){
            $('#{$containerId}').load('{$url}',{$callback});
        });
EOD;
        echo CHtml::script($script);
    }

    /**
     * @param string $msg
     * @throws CHttpException
     */
    public static function throw404httpException($msg='The requested page does not exist！'){
        throw new CHttpException(
            404,
           $msg
        );
    }
    /**
     * @static
     * @param $text
     * @param string $url
     * @param $ajaxOptions
     * @param array $htmlOptions
     * @return string
     * @see http://api.jquery.com/jQuery.ajax/
     * -----------------------------------------------
    echo WebUtil::ajaxDeleteLink('delete',array('delete','id'=>$data->id),
    array('success'=>'js:function(data){
    $.fn.yiiListView.update("album_thumb_list");
    }'));
     * ------------------------------------------------
     */
    public static function ajaxDeleteLink($text, $url = '#', $ajaxOptions, $htmlOptions = array())
    {

        $ajaxOptions['beforeSend'] = 'js:function(jqXHR, settings){
                if(!confirm("确定要删除这条数据吗?")) return false;
        }';

        $ajaxOptions['type'] = 'POST';
        return CHtml::ajaxLink($text, $url, $ajaxOptions, $htmlOptions);
    }

    /**
     * @static
     * @return string
     */
    static public function getUrl()
    {
        $url = @($_SERVER["HTTPS"] != 'on') ? 'http://' . $_SERVER["SERVER_NAME"] : 'https://' . $_SERVER["SERVER_NAME"];
        $url .= ($_SERVER["SERVER_PORT"] !== 80) ? ":" . $_SERVER["SERVER_PORT"] : "";
        $url .= $_SERVER["REQUEST_URI"];
        return $url;
    }

    public static function div2span($content)
    {

    }

    public static function  alert($msg)
    {
        // $msg = utf8_encode($msg);
        $msg = "<script type='text/javascript'>
                 alert('$msg');
        </script>";
        echo $msg; //CJavaScript::encode($msg);
    }

    public static function  execScript($funcCode)
    {
        CJavaScript::quote($funcCode);
        // $msg = utf8_encode($msg);
        $script = "<script type='text/javascript'>
               {$funcCode};
        </script>";
        echo $script; //CJavaScript::encode($msg);
    }

    public static function setLocation($url)
    {
        $js = <<<CODE
window.location.href = "{$url}";
CODE;
        self::execScript($js);
    }

    public static function  execParentFunction($funcCode)
    {
        $funcCode = 'parent.' . $funcCode;
        CJavaScript::quote($funcCode);
        // $msg = utf8_encode($msg);
        $script = "<script type='text/javascript'>
               {$funcCode};
        </script>";
        echo $script; //CJavaScript::encode($msg);
    }

    /**
     * @static
     * @param string $charset
     * @return void
     * 输出 页面编码元信息 调试用
     */
    public static function printCharsetMeta($charset = 'UTF-8')
    {
        echo '
        <meta http-equiv="Content-Type" content="text/html; charset=' . $charset . '" />
        ';
    }

    #待续
    protected static function iframeSubmitCallBack($callBackFunc, $data = null)
    {
        $script = '
        <script type="text/javascript">
          if(typeof(parent.callbackFunc) == "function"){
             parent.callbackFunc(data);
          }

        </script>
        ';
        $script = str_replace('callbackFunc', $callBackFunc, $script);
        $script = str_replace('data', $data, $script);
        return $script;
    }

    /**
     * @static
     * @return void
     * 放在必须要登陆的页面上面 用来跳转登陆
     */
    public static function checkLogin()
    {
        $loginUrl = app()->createUrl('user/login');
        if (user()->getIsGuest()) {
            cs()->registerScript('forward2login', "
             var newHref = '{$loginUrl}';
             window.location.href = newHref;
	          window.navigate(newHref);
              self.location = newHref;
              top.location = newHref;
           ",
                CClientScript::POS_BEGIN);
        } else {
            //登陆了已经
            /*
              cs()->registerScript('forward2login',"
            function yesYouLogin(){;}
            ",
              CClientScript::POS_BEGIN);

             */
        }
    }

    public static function printClassFileName($obj)
    {
        if (is_object($obj)) {
            $rc = new ReflectionClass($obj);
            echo $rc->getFileName();
        } else {
            throw new Exception('need a object , the type of param is :' . gettype($obj));
        }
    }

    /**
     * @static
     * @param $url
     * @return int
     */
    public static function getUrlStatusCode($url)
    {
        $ch = curl_init(str_replace(" ", "%20", $url));
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_POST, false);
        $data = curl_exec($ch);
        curl_close($ch);
        if ($data === false) {
            return 500;
        } else {
            preg_match("/HTTP\/1\.[1|0]\s(\d{3})/", $data, $matches);
            return (int)$matches[1];
        }
    }

    /**
     * @static
     * @return string
     */
    public static function getClientIp()
    {
        if (!empty($_SERVER["HTTP_CLIENT_IP"]))
            $cip = $_SERVER["HTTP_CLIENT_IP"];
        else if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
            $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        else if (!empty($_SERVER["REMOTE_ADDR"]))
            $cip = $_SERVER["REMOTE_ADDR"];
        else
            $cip = "unknownIp";
        return $cip;
    }

    /**
     * @static
     * @return string
     */
    public static function getIp()
    {

        if (getenv("HTTP_CLIENT_IP"))
            $ip = getenv("HTTP_CLIENT_IP");
        else if (getenv("HTTP_X_FORWARDED_FOR"))
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        else if (getenv("REMOTE_ADDR"))
            $ip = getenv("REMOTE_ADDR");
        else
            $ip = "UNKNOWN";
        return $ip;

    }

    /**
     * @static
     * @param $ip
     * @return mixed
     * 模拟mysql的同名方法实现 php中的inet_pton() 好像有时候返回奇怪字符
     * http://stackoverflow.com/questions/2754340/inet-aton-and-inet-ntoa-in-php
     *
     * http://code.tutsplus.com/tutorials/top-20-mysql-best-practices--net-7855
     */
    public static function inet_aton($ip)
    {

        $a = ip2long($ip);
        $b = unpack("N", pack("L", $a));
        return $b[1];
    }

    /**
     * @static
     * @return string
     */
    public static function genRandomColor()
    {
        $randomColor = '#' . strtoupper(dechex(rand(0, 10000000)));
        if (strlen($randomColor) != 7) {
            $randomColor = str_pad($randomColor, 10, '0', STR_PAD_RIGHT);
            $randomColor = substr($randomColor, 0, 7);
        }
        return $randomColor;
    }

    /**
     * @static
     * @param $color
     * @return array|bool
     * // convert HEX color to RGB values
     * @see  https://github.com/X2Engine/X2Engine
     */
    public static function hex2rgb($color)
    {
        if ($color[0] == '#')
            $color = substr($color, 1);

        if (strlen($color) == 6)
            list($r, $g, $b) = array($color[0] . $color[1],
                $color[2] . $color[3],
                $color[4] . $color[5]);
        else if (strlen($color) == 3)
            list($r, $g, $b) = array($color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]);
        else
            return false;

        $r = hexdec($r);
        $g = hexdec($g);
        $b = hexdec($b);

        return array($r, $g, $b);
    }

    /**
     * @static
     * @param $uid
     * @param bool $ensureExists
     * @return mixed
     * 根据用户id返回用户的缓存依赖文件夹路径
     *
     * 在AR变更后需要使用它来返回变更通知的路径
     * 只需要变个时间戳即可 表示某个AR的最后变更时间变化了
     */
    public static function getUserInfoCacheDependencyDir($uid, $ensureExists = true)
    {
        $cacheDependencyDir = Yii::getPathOfAlias('application.runtime.cache.dependency.dc_' . $uid . '.ars');
        if (file_exists($cacheDependencyDir)) {
            return $cacheDependencyDir;
        } elseif ($ensureExists && !is_dir($cacheDependencyDir)) {
            mkdir($cacheDependencyDir, 0777, true);
        }
        return $cacheDependencyDir;
    }

    /**
     * @static
     * @param $uid
     * @return array
     * 获取用户的信息 这里做了缓存的 可以使用各种其他策略来提升性能
     * 由于跟AR相关所以 ..
     */
    public static function getUserInfo($uid)
    {
        $cacheDependencyDir = Yii::getPathOfAlias('application.runtime.cache.dependency.dc_' . $uid . '.ars');
        if (!is_dir($cacheDependencyDir)) {
            mkdir($cacheDependencyDir, 0777, true);
        }
        $cacheDependencyFile = $cacheDependencyDir . DIRECTORY_SEPARATOR . 'User';
        if (!is_file($cacheDependencyFile)) {
            file_put_contents($cacheDependencyFile, time());
        }

        $dependency = new CFileCacheDependency($cacheDependencyFile);

        $user = User::model()->cache(86400, $dependency)->findByPk($uid);
        return $user->attributes;
    }

    /**
     * 关键字过滤
     * @param $content
     * @return mixed
     */
    public static function keyWordFilter($content)
    {
        $audit = array('open' => Yii::app()->settings->get('audit', 'keywordsStatus'),
            'keywords' => Yii::app()->settings->get('audit', 'keywordsFilter'),
            'replace' => Yii::app()->settings->get('audit', 'keywordsReplace'));

        if ($audit['open'] && $audit['keywords']) {
            $replace = $audit['replace'] ? $audit['replace'] : '[和*谐]';
            $arr_keyword = explode('|', $audit['keywords']);
            foreach ($arr_keyword as $v) {
                $content = str_replace($v, $replace, $content);
            }
            return $content;
        } else {
            return $content;
        }
    }

    /**
     * 检测内容是否含有关键字
     * @param $content
     * @return bool
     */
    public static function checkKeyWord($content)
    {
        $audit = array('open' => Yii::app()->settings->get('audit', 'keywordsStatus'),
            'keywords' => Yii::app()->settings->get('audit', 'keywordsFilter'),
            'replace' => Yii::app()->settings->get('audit', 'keywordsReplace'));

        if ($audit['open'] && $audit['keywords']) {
            $arr_keyword = explode('|', $audit['keywords']);
            $num = 0;
            foreach ($arr_keyword as $v) {
                if (stristr($content, $v))
                    $num++;
            }
            return $num ? true : false;
        } else {
            return false;
        }
    }


    /**
     * @static
     * @param $path
     * @param bool $hashByName
     * @param $level
     * @param bool $forceCopy
     * @return mixed
     */
    public static function  assetsUrl($path, $hashByName = false, $level = -1, $forceCopy = YII_DEBUG)
    {
        return Yii::app()->getAssetManager()->publish($path, $hashByName, $level, $forceCopy);
    }

    /**
     * @static
     * ---------------------------------
     * https://github.com/mquan/lavish
     * -----------------------------------------
     *  根据图片自动生成配色
     */
    public static function registerRandomBootstrapTheme()
    {
        $themesDir = PublicAssets::instance()->getBasePath();
        $themesDir .= DIRECTORY_SEPARATOR . 'bootstrap';
        $di = new DirectoryIterator($themesDir);
        $themes = array();
        foreach ($di as $dir) {
            if (!$dir->isDot()) {
                $themes[] = $dir->getBaseName();
                // echo $dir->getBaseName();
            }
        }
        /*
         $randomTheme =  array_rand($themes);
         echo 'dd'.$randomTheme.'dd';
        */
        shuffle($themes);
        $randomTheme = current($themes);
        //  echo $randomTheme;
        PublicAssets::registerCssFile("bootstrap/{$randomTheme}/style.css");
    }


    public static function bootSwatch($theme = '')
    {
        $themesDir = PublicAssets::instance()->getBasePath();
        $themesDir .= DIRECTORY_SEPARATOR . 'bootswatch2-0-4';
        $di = new DirectoryIterator($themesDir);
        $themes = array();
        foreach ($di as $dir) {
            if (!$dir->isDot()) {
                $themes[] = $dir->getBaseName();
                // echo $dir->getBaseName();
            }
        }
        /*
         $randomTheme =  array_rand($themes);
         echo 'dd'.$randomTheme.'dd';
        */
        shuffle($themes);
        $randomTheme = current($themes);
        //  echo $randomTheme;
        PublicAssets::registerCssFile("bootswatch2-0-4/{$randomTheme}/bootstrap.min.css");
    }

    /**
     * @static
     * @param string $jquerySerializedStr
     * @return array
     * 解析用jquery 的serialize  [serializeArray 啥东东？]方法编码的表单数据
     * 或者input数据（ $("input:checkbox").serialize();）
     *
     *  echo urldecode('name%40website.com'); //restores "name@website.com"
     *  $("#addShowFormSubmit")
     *. click(function() {
     * var perfTimes = $("#addShowForm").serialize();
     *      $.post("includes/add_show.php",
     *      $.param({name: $("#showTitle").val()}) + "&" + perfTimes,
     *       function(data) {...});
     * ----------------------------------------------------
     *});
     * --------------------------------------------------
     * try using serializeArray() instead of serialize().
     * serialize() will produce an url-encoded query string,
     * whereas serializeArray() produces a JSON data structure.
     * ---------------------------------------------------------
     * //Prevent Form Submission when Pressing Enter
     *    $("form").bind("keypress", function(e) {
     *   if (e.keyCode == 13)
     *   return false;
     *     });
     */
    public static function parseStr($jquerySerializedStr = '')
    {
        $parsed = null;
        parse_str($jquerySerializedStr, $parsed);
        return $parsed;
    }

    /**
     * @static
     * @param null $requestUri
     * @return array|null
     */
    public static function getQueryParams($requestUri = null)
    {
        $uri = ($requestUri == null) ? request()->getRequestUri() : $requestUri;
        if (strpos($uri, '?') !== false) {
            $urlParts = parse_url($uri);
            $queryParts = array();
            parse_str($urlParts['query'], $queryParts);
            /*
            if(!empty($queryParts) && is_array($queryParts)){
                foreach($queryParts as $key=>$val){
                    $_GET[$key] = $val ;
                }
            }*/
            return empty($queryParts) ? array() : $queryParts;
        } else {
            return array();
        }
    }

    /**
     * @static
     * @return bool
     */
    public static function isWindowsOS()
    {
        return strncmp(PHP_OS, 'WIN', 3) === 0;
    }

    /**
     * @static
     * @param $tm
     * @param int $rcs
     * @return string
     */
    public static function _timeAgo($tm, $rcs = 0)
    {
        $cur_tm = time();
        $dif = $cur_tm - $tm;
        $pds = array('second', 'minute', 'hour', 'day', 'week', 'month', 'year', 'decade');
        $lngh = array(1, 60, 3600, 86400, 604800, 2630880, 31570560, 315705600);
        for ($v = sizeof($lngh) - 1; ($v >= 0) && (($no = $dif / $lngh[$v]) <= 1); $v--) ;
        if ($v < 0) $v = 0;
        $_tm = $cur_tm - ($dif % $lngh[$v]);

        $no = floor($no);
        if ($no <> 1) $pds[$v] .= 's';
        $x = sprintf("%d %s ", $no, $pds[$v]);
        if (($rcs == 1) && ($v >= 1) && (($cur_tm - $_tm) > 0)) $x .= self::_timeAgo($_tm);
        return $x;
    }

    /**
     * @static
     * @param $time
     * @return string
     */
    public static function timeAgo($time)
    {
        $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
        $lengths = array("60", "60", "24", "7", "4.35", "12", "10");

        $now = time();

        $difference = $now - $time;
        $tense = "ago";

        for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j++) {
            $difference /= $lengths[$j];
        }

        $difference = round($difference);

        if ($difference != 1) {
            $periods[$j] .= "s";
        }

        return "$difference $periods[$j] 'ago' ";
    }

    static public function timeAgo2($i)
    {
        $m = time() - $i;
        $o = 'just now';
        $t = array('year' => 31556926, 'month' => 2629744, 'week' => 604800,
            'day' => 86400, 'hour' => 3600, 'minute' => 60, 'second' => 1);
        foreach ($t as $u => $s) {
            if ($s <= $m) {
                $v = floor($m / $s);
                $o = "$v $u" . ($v == 1 ? '' : 's') . ' ago';
                break;
            }
        }
        return $o;
    }

    public static function build_calendar($month, $year, $dateArray)
    {

        // Create array containing abbreviations of days of week.
        $daysOfWeek = array('S', 'M', 'T', 'W', 'T', 'F', 'S');

        // What is the first day of the month in question?
        $firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);

        // How many days does this month contain?
        $numberDays = date('t', $firstDayOfMonth);

        // Retrieve some information about the first day of the
        // month in question.
        $dateComponents = getdate($firstDayOfMonth);

        // What is the name of the month in question?
        $monthName = $dateComponents['month'];

        // What is the index value (0-6) of the first day of the
        // month in question.
        $dayOfWeek = $dateComponents['wday'];

        // Create the table tag opener and day headers

        $calendar = "<table class='calendar'>";
        $calendar .= "<caption>$monthName $year</caption>";
        $calendar .= "<tr>";

        // Create the calendar headers

        foreach ($daysOfWeek as $day) {
            $calendar .= "<th class='header'>$day</th>";
        }

        // Create the rest of the calendar

        // Initiate the day counter, starting with the 1st.

        $currentDay = 1;

        $calendar .= "</tr><tr>";

        // The variable $dayOfWeek is used to
        // ensure that the calendar
        // display consists of exactly 7 columns.

        if ($dayOfWeek > 0) {
            $calendar .= "<td colspan='$dayOfWeek'>&nbsp;</td>";
        }

        $month = str_pad($month, 2, "0", STR_PAD_LEFT);

        while ($currentDay <= $numberDays) {

            // Seventh column (Saturday) reached. Start a new row.

            if ($dayOfWeek == 7) {

                $dayOfWeek = 0;
                $calendar .= "</tr><tr>";

            }

            $currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);

            $date = "$year-$month-$currentDayRel";

            $calendar .= "<td class='day' rel='$date'>$currentDay</td>";

            // Increment counters

            $currentDay++;
            $dayOfWeek++;

        }

        // Complete the row of the last week in month, if necessary

        if ($dayOfWeek != 7) {

            $remainingDays = 7 - $dayOfWeek;
            $calendar .= "<td colspan='$remainingDays'>&nbsp;</td>";

        }

        $calendar .= "</tr>";

        $calendar .= "</table>";

        return $calendar;

    }

    //=================================================================================

    /**
     * @param $realPath
     * @return string
     * @throws InvalidArgumentException
     * --------------------------
     * relative to the request base path (Yii::app()->request->getBaseUrl($absolute))
     * --------------------------
     */
    static public function getRelativeUrl($realPath)
    {
        if (($pos = strpos($realPath, 'http')) === 0) {
            return $realPath;
        }
        if (empty($realPath)) throw new InvalidArgumentException('must give a not null param !');

        $relativePath = self::getRelativePath($realPath, dirname(Yii::app()->request->getScriptFile()));
        if ($relativePath == false) {
            return $realPath; // can't handle it ！
        }

        $accessibleUrl = /*Yii::app()->request->getBaseUrl($absolute) .*/
            str_replace(DIRECTORY_SEPARATOR, '/', ltrim($relativePath, '/'));
        return $accessibleUrl;

    }

    /**
     * @static
     * @param $relUrl
     * @return string
     */
    static public function getRealPath4relativeUrl($relUrl)
    {
        $baseDir = dirname(Yii::app()->request->getScriptFile());
        return $baseDir . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, ltrim($relUrl, '/'));
    }

    /**
     * @param $path
     * @param null $relativeTo default relative to app base path
     * @return bool|string
     * 获取路径的相对路径 如果没有给出那么就是基于yii基路经
     * 如果不存在 则认为不是上传上去的文件
     */
    static public function getRelativePath($path, $relativeTo = null)
    {

        $path = strtr($path, array('/' => DIRECTORY_SEPARATOR, '\\' => DIRECTORY_SEPARATOR));
        $relPath = (($relativeTo == null) ? Yii::app()->getBasePath() : $relativeTo);
        if (strpos($path, $relPath) === 0) {
            // echo substr($path, strlen($relPath) + 1) , die;
            return substr($path, strlen($relPath) + 1);
        } else
            return false;
    }

//======================================================================================================

    /**
     * 纠正ajax提交不验证的问题！
     * 主要ajax 提交中 文件上传始终是个问题 可以借助flash或者iframe
     */
    static public function fixAjaxSubmitValidate()
    {

        $jsFixCode = <<<EOD
;$.yii.fix = {
    ajaxSubmit : {
        beforeSend : function(form) {
            return function(xhr,opt) {
                form = $(form);
                //jQuery.data( elem, name, data, true )
                //$._data(form[0], "events").submit[0].handler();
               form.get(0).trigger('onsubmit');
                var he = form.data('hasError');
                form.removeData('hasError');
                return he===false;
            }
        },

        afterValidate : function(form, data, hasError) {
            $(form).data('hasError', hasError);
            return true;
        }
    }
};
EOD;
         Yii::app()->clientScript->registerCoreScript('yii')
            ->registerScript(__METHOD__, $jsFixCode, CClientScript::POS_HEAD);
    }
}


//----------------------依赖的类在底下----------------------------------------------------------------------
/**
 * 颜色生成类：
 * @see http://stackoverflow.com/questions/7263140/random-color-generation-using-php
 */
class ColorGenerator
{

    protected $rangeLower, $rangeHeight;
    private $range = 100;

    public function __constructor($range_lower = 80, $range_higher = 160)
    {
        // range of rgb values
        $this->rangeLower = $range_lower;
        $this->rangeHeight = $range_higher - $range_lower;
    }

    public function randomColor()
    {
        // generate random color in range
        return $this->generateColor(rand(0, 100));
    }

    public function generateColor($value)
    {
        // generate color based on value between 0 and 100
        // closer the number, more similar the colors. 0 is red. 50 is green. 100 is blue.
        $color_range = $this->range / 3;
        $color = new stdClass();
        $color->red = $this->rangeLower;
        $color->green = $this->rangeLower;
        $color->blue = $this->rangeLower;
        if ($value < $color_range * 1) {
            $color->red += $color_range - $value;
            $color->green += $value;
        } else if ($value < $color_range * 2) {
            $color->green += $color_range - $value;
            $color->blue += $value;
        } else if ($value < $color_range * 3) {
            $color->blue += $color_range - $value;
            $color->red += $value;
        }
        // returns color object with properties red green and blue.
        return $color;
    }
}