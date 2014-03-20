<?php
/**
 * Ipaddress class file.
 *
 * @author WindsDeng <windsdeng@hotmail.com>
 * @link http://www.dlf5.com/
 * @copyright Copyright &copy; 2008-2011 DLF5.NET
 */ 
include_once 'Snoopy/Snoopy.class.php';
class Ipaddress extends CInputWidget 
{
	
	/******* widget private vars *******/
	private $sinaApi			= "http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip="; //新浪IP接口
	
	private $youdaoApi  		= "http://www.youdao.com/smartresult-xml/search.s?type=ip&q=";  //有道ip接口 
	
	public $ip = null;			//要查询的IP	
	
	
	/**
	* Initialize the widget
	*/
	public function init()
	{
		$this->getIpaddress($this->ip);
		parent::init();
	}
	
	/**
	 * @param integer $ip 
	 * @return IP 真实地址
	 */
	
	public function getIpaddress($ip)
	{
		if($ip)
		{
			if(!function_exists('fsockopen'))
			{
				$sinadata = file_get_contents($this->sinaApi.trim($ip));
				$data = json_decode($sinadata);
				if($sinadata)
				{
					$data = file_get_contents($this->youdaoApi.trim($ip));
				}
			}else{
				$snoopy = new Snoopy();                    // 实例化一个Snoopy对象 
				$snoopy->fetch($this->sinaApi.trim($ip));
				$data = json_decode($snoopy->results,true);
				
				if(!$data)
				{
					$snoopy->fetch($this->youdaoApi.trim($ip));
					$data = $snoopy->results;
				}
			}
			
			$newData=iconv("GB2312","UTF-8//IGNORE",$data);
			echo $newData;
		}else{
			echo '匿名';
		}
		
	}
	
	
	/**
	* Run the widget
	*/
	public function run()
	{
			
		parent::run();
	}
}
