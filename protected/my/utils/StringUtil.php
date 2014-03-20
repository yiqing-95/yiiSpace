<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-9-29
 * Time: 下午11:12
 * To change this template use File | Settings | File Templates.
 */
class StringUtil
{

    /**
     * @param $search
     * @param $replace
     * @param $string
     * @param int $limit
     * @return mixed
     * @see http://stackoverflow.com/questions/1252693/php-str-replace-that-only-acts-on-the-first-match
     * -----------
     * 用正则式 据说是最方便解决之法
     *  preg_replace()
     */
    function strReplaceLimit($search, $replace, $string, $limit = 1) {
        if (is_bool($pos = (strpos($string, $search))))
            return $string;

        $search_len = strlen($search);

        for ($i = 0; $i < $limit; $i++) {
            $string = substr_replace($string, $replace, $pos, $search_len);

            if (is_bool($pos = (strpos($string, $search))))
                break;
        }
        return $string;
    }
 //------------------------------------------------------------------------
//    下面几个方法来自flyErp 特此感谢 作者
    /**
     *  URL编码
     * @param string $input
     * @return string
     */
    public static function urlEncode($input) {
        return strtr(base64_encode($input), '=', '_');
    }

    /**
     * URL解码
     * @param string $input
     * @return string
     */
    public static function urlDecode($input) {
        return base64_decode(strtr($input, '_', '='));
    }

    /**
     * 随即字符串
     * @param type $length
     * @param type $numeric
     * @return string
     */
    public static function random($length, $numeric = 0) {
        PHP_VERSION < '4.2.0' && mt_srand((double) microtime() * 1000000);
        if ($numeric) {
            $hash = sprintf('%0' . $length . 'd', mt_rand(0, pow(10, $length) - 1));
        } else {
            $hash = '';
            $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
            $max = strlen($chars) - 1;
            for ($i = 0; $i < $length; $i++) {
                $hash .= $chars[mt_rand(0, $max)];
            }
        }
        return $hash;
        exit;
    }

    /**
     * 	生成UUID
     * @return string
     */
    public static function uuid() {
        mt_srand((double) microtime() * 10000); //optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $uuid =
            substr($charid, 0, 8)
                . substr($charid, 8, 4)
                . substr($charid, 12, 4)
                . substr($charid, 16, 4)
                . substr($charid, 20, 12);

        return strtolower($uuid);
    }


    /**
     * 处理邮箱
     * @param $email
     * @return string
     */
    public static function disposeEmail($email) {
        $tmp = '';
        $isEm = false;
        $length = strlen($email);
        for ($i=0; $i<$length; $i++) {
            if($email[$i] == '@')
                $isEm = true;
            if($i<2 || ($i+1<$length && $email[$i+1] == '@') || $isEm == true) {
                $tmp .= $email[$i];
            } else {
                $tmp .= '*';
            }
        }
        return $tmp;
    }
//------------------------------------------------------------------------

    /**
     * @static
     * @param $string
     * @param $length
     * @param string $etc
     * @param bool $keep_first_style
     * @return string
     *---------------------------------------------
     * mysql implements:
     * IF(LENGTH(description) <= 30, description,
     * CONCAT(LEFT(description, 30), '...')) AS description
     * --------------------------------------------
     */
    public static  function cnTruncate($string,$length,$etc='...',$keep_first_style = true)
    {
        $result   =   '';
        $string   =   html_entity_decode(trim(strip_tags($string)),   ENT_QUOTES,   'UTF-8');
        //$string   =   trim($string);
        $strlen   =   strlen($string);
        for($i   =   0;   (($i   <   $strlen)   &&   ($length   >   0));   $i++)
        {
            if($number   =   strpos(str_pad(decbin(ord(substr($string,   $i,   1))),   8,   '0',   STR_PAD_LEFT),   '0'))
            {
                if($length   <   1.0)
                {
                    break;
                }
                $result   .=   substr($string,   $i,   $number);
                $length   -=   1.0;
                $i   +=   $number   -   1;
            }
            else
            {
                $result   .=   substr($string,   $i,   1);

                $length   -=   0.5;
            }
        }
        $result   =   htmlspecialchars($result,   ENT_QUOTES,   'UTF-8');
        if($i   <   $strlen)
        {
            $result   .=   $etc;
        }
        return   $result;
    }
}
