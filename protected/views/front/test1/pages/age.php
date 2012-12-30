<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-12-25
 * Time: 下午2:50
 * To change this template use File | Settings | File Templates.
 * @see http://bbs.phpchina.com/thread-103769-1-1.html
 */

/**
 * 判断干支、生肖和星座
 *
 * @param string $type 返回类型: array.
 * @param date   $birth =  时间戳,其它时间写法
 *
 * @author bottle hhyisw@163.com
 */
//示例
$arr = birthext('474768000'); //时间戳
print_r($arr);
$arr = birthext('1985-01-17');
print_r($arr);
$arr = birthext('19850117');
print_r($arr);
$arr = birthext('19820318');
print_r($arr);
function birthext($birth)
{
    if (strstr($birth, '-') === false && strlen($birth) !== 8)
        $birth = date("Y-m-d", $birth);
    if (strlen($birth) === 8) {
        if (preg_match('@([0-9]{4})([0-9]{2})([0-9]{2})@', $birth, $bir))
            $birth = "{$bir[1]}-{$bir[2]}-{$bir[3]}";
    }

    if (strlen($birth) < 8)
        return false;

    $tmpstr = explode('-', $birth);

    if (count($tmpstr) !== 3)
        return false;

    $y = (int)$tmpstr[0];
    $m = (int)$tmpstr[1];
    $d = (int)$tmpstr[2];
    $result = array();
    $xzdict = array('摩羯', '宝瓶', '双鱼', '白羊', '金牛', '双子', '巨蟹', '狮子', '处女', '天秤', '天蝎', '射手');
    $zone = array(1222, 122, 222, 321, 421, 522, 622, 722, 822, 922, 1022, 1122, 1222);
    if ((100 * $m + $d) >= $zone[0] || (100 * $m + $d) < $zone[1]) {
        $i = 0;
    } else {
        for ($i = 1; $i < 12; $i++) {
            if ((100 * $m + $d) >= $zone[$i] && (100 * $m + $d) < $zone[$i + 1]) break;
        }
    }
    $result['xz'] = $xzdict[$i] . '座';

    $gzdict = array(array('甲', '乙', '丙', '丁', '戊', '己', '庚', '辛', '壬', '癸'), array('子', '丑', '寅', '卯', '辰', '巳', '午', '未', '申', '酉', '戌', '亥'));
    $i = $y - 1900 + 36;
    $result['gz'] = $gzdict[0][($i % 10)] . $gzdict[1][($i % 12)];

    $sxdict = array('鼠', '牛', '虎', '兔', '龙', '蛇', '马', '羊', '猴', '鸡', '狗', '猪');
    $result['sx'] = $sxdict[(($y - 4) % 12)];
    return $result;
}

?>