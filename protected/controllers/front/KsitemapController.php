<?php
class KsitemapController extends Controller
{
	public $protectedControllers = array('ksitemap');   //controller array will not added in sitemap.xml
	public $protectedActions =array('site/error','site/logout');      //controller/action array will not added in sitemap.xml
	public $priority = '0.5';                           //default priority for all url
	public $changefreq = 'daily';                       //default frequency for all url
	public $lastmod = '1985-10-07';				        //default last modified date
			
	public function actionIndex()
	{
		Yii::import('application.controllers.*');
		//Yii::import('application.controllers.back.*'); //if you are using front/back type of directory structure, just define controller folder back/front
		$urls = array();
		$directory = Yii::getPathOfAlias('application.controllers');
		//$directory = Yii::getPathOfAlias('application.controllers.back'); //if you are using front/back type of directory structure, just define controller folder back/front
		$iterator = new DirectoryIterator($directory);
		
		foreach ($iterator as $fileinfo)
		{
			if ($fileinfo->isFile() and $fileinfo->getExtension() == 'php')
			{
				$className = substr($fileinfo->getFilename(), 0, -4); //strip extension
				$class = new ReflectionClass($className);
						
				foreach ($class->getMethods(ReflectionMethod::IS_PUBLIC) as $method)
				{										
					$methodName = $method->getName();
					if (strpos($methodName, 'action') === 0) //only methods starts with action included
					{	
						$controller = lcfirst(substr($className, 0, strrpos($className, 'Controller')));
						$action = lcfirst(substr($methodName, 6));
						if (!$this->isProtected($controller, $action)) //refer isProtected function
							$this->addUrl($urls, "$controller/$action"); //refer addUrl function
					}
				}
			}
		}
		
		//below array $url converted into required(sitemap.xml) structure		
		$xmldata = '<?xml version="1.0" encoding="utf-8"?>'; 
		$xmldata .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
		foreach ($urls as $url => $data)
		{
			$xmldata .= '<url>';
			$xmldata .= '<loc>'.$url.'</loc>';
			$xmldata .= '<lastmod>'.$data['lastmod'].'</lastmod>';
			$xmldata .= '<changefreq>'.$data['changefreq'].'</changefreq>';
			$xmldata .= '<priority>'.$data['priority'].'</priority>';
			$xmldata .= '</url>';
		}	
		$xmldata .= '</urlset>'; 
		
		if(file_put_contents('sitemap.xml',$xmldata))
		{
			echo "sitemap.xml file created on project root folder..";	
		}
	
	}
		
	protected function addUrl(&$urls, $action, $args = array()) //generate url and globaly default passed parameters as array
	{
		$url = Yii::app()->createAbsoluteUrl($action, $args);
				
		$prefs = array();		
		$defPrefs = array(
			'lastmod' => $this->lastmod ? $this->lastmod : date('Y-m-d'),
			'changefreq' => $this->changefreq ? $this->changefreq : 'daily',
			'priority' => $this->priority ? $this->priority : 0.5,
		);		
		$prefs = array_merge($defPrefs, $prefs);		
		$urls[$url] = $prefs;
	}
		
	protected function isProtected($controller, $action) //check protected controller/action present in publicly defined global array 
	{
		return in_array($controller, $this->protectedControllers) or 
			in_array("$controller/$action", $this->protectedActions);
	}
}