<?php
// +----------------------------------------------------------------------
// | Buddy Framework 
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://buddy.woshimaijia.com/ All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: xinqiyang <xinqiyang@gmail.com>
// +----------------------------------------------------------------------

/**
 *  IP location Query class 
 *  <code>
 *  $ip = Iplocation::instance();
 *	$r = $ip->getlocation('123.120.1.160');
 *	</code>
 *	
 */
class IpLocation2
{
    /**
     * QQWry.Dat file point
     *
     * @var resource
     */
    private $fp;

    /**
     * first ip 
     *
     * @var int
     */
    private $firstip;

    /**
     * last address
     *
     * @var int
     */
    private $lastip;

    /**
     * total ip count
     *
     * @var int
     */
    private $totalip;

    /**
     * construct
     *
     * @param $filePath
     * @internal param string $filename
     * @return \IpLocation2
     */
    public function __construct($filePath) {
        $this->fp = 0;
        if (($this->fp = fopen($filePath, 'rb')) !== false) {
            $this->firstip = $this->getlong();
            $this->lastip = $this->getlong();
            $this->totalip = ($this->lastip - $this->firstip) / 7;
        }
    }
    

    /**
     * getlong
     *
     * @access private
     * @return int
     */
    private function getlong() {
        $result = unpack('Vlong', fread($this->fp, 4));
        return $result['long'];
    }

    /**
     * return 3 bit
     * @access private
     * @return int
     */
    private function getlong3() {
        $result = unpack('Vlong', fread($this->fp, 3).chr(0));
        return $result['long'];
    }

    /**
     * return packip address
     *
     * @access private
     * @param string $ip
     * @return string
     */
    private function packip($ip) {
        return pack('N', intval(ip2long($ip)));
    }

    /**
     * return read string
     *
     * @access private
     * @param string $data
     * @return string
     */
    private function getstring($data = "") {
        $char = fread($this->fp, 1);
        while (ord($char) > 0) {        // string store use c formate use \0 end 
            $data .= $char;             // link the string
            $char = fread($this->fp, 1);
        }
        return $data;
    }

    /**
     * return area info
     *
     * @access private
     * @return string
     */
    private function getarea() {
        $byte = fread($this->fp, 1);    
        switch (ord($byte)) {
            case 0:                    
                $area = "";
                break;
            case 1:
            case 2:                     
                fseek($this->fp, $this->getlong3());
                $area = $this->getstring();
                break;
            default:                 
                $area = $this->getstring($byte);
                break;
        }
        return $area;
    }

    /**
     * return ip location info 
     * $r['contry']  $r['area']
     * @access public
     * @param string $ip
     * @return array
     */
    public function getlocation($ip='') {
        if (!$this->fp) return null;          
		if(empty($ip)) $ip = getip();
        $location['ip'] = gethostbyname($ip);  
        $ip = $this->packip($location['ip']); 
        $l = 0;                         
        $u = $this->totalip;           
        $findip = $this->lastip;      
        while ($l <= $u) {
            $i = floor(($l + $u) / 2);
            fseek($this->fp, $this->firstip + $i * 7);
            $beginip = strrev(fread($this->fp, 4));
         
            if ($ip < $beginip) {
                $u = $i - 1;
            }
            else {
                fseek($this->fp, $this->getlong3());
                $endip = strrev(fread($this->fp, 4));
                if ($ip > $endip) {
                    $l = $i + 1;
                }
                else {
                    $findip = $this->firstip + $i * 7;
                    break;
                }
            }
        }

        fseek($this->fp, $findip);
        $location['beginip'] = long2ip($this->getlong());
        $offset = $this->getlong3();
        fseek($this->fp, $offset);
        $location['endip'] = long2ip($this->getlong());
        $byte = fread($this->fp, 1);
        switch (ord($byte)) {
            case 1:
                $countryOffset = $this->getlong3();
                fseek($this->fp, $countryOffset);
                $byte = fread($this->fp, 1);
                switch (ord($byte)) {
                    case 2:
                        fseek($this->fp, $this->getlong3());
                        $location['country'] = $this->getstring();
                        fseek($this->fp, $countryOffset + 4);
                        $location['area'] = $this->getarea();
                        break;
                    default:
                        $location['country'] = $this->getstring($byte);
                        $location['area'] = $this->getarea();
                        break;
                }
                break;
            case 2:
                fseek($this->fp, $this->getlong3());
                $location['country'] = $this->getstring();
                fseek($this->fp, $offset + 8);
                $location['area'] = $this->getarea();
                break;
            default:
                $location['country'] = $this->getstring($byte);
                $location['area'] = $this->getarea();
                break;
        }
        if ($location['country'] == " CZ88.NET") { 
            $location['country'] = "Unknow";
        }
        if ($location['area'] == " CZ88.NET") {
            $location['area'] = "";
        }
        //change gbk to utf8
        $location['country'] = mb_convert_encoding($location['country'], 'UTF8','GBK');
		$location['area'] = mb_convert_encoding($location['area'], 'UTF8','GBK');
		
		return $location;
    }
    
   
    

    /**
     * excute complete then close file handler
     *
     */
    public function __destruct() {
        if ($this->fp) {
            fclose($this->fp);
        }
        $this->fp = 0;
    }

	
}
/*
var_dump(getAddress('202.101.98.54','福州'));

function getAddress($ip=null,$address='') {
    $url = "http://open.baidu.com/ipsearch/s?wd={$ip}&tn=baiduip";
    $res = mb_convert_encoding(HttpRequest($url), 'UTF-8', 'GBK');
    //var_dump($res);
    if ( preg_match('#来自：<b>(.+)</b>#Ui', $res, $m) ) {
        if (is_int(strpos($m[1], $address))) {
            return $m[1];
        }
    }
}


function HttpRequest($url, $data=array()) {
    $ch = curl_init();
    if (is_array($data) && $data) {
        $formdata = http_build_query($data);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $formdata);
    }
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
    curl_setopt($ch, CURLOPT_TIMEOUT, 2);
    return curl_exec($ch);
}*/