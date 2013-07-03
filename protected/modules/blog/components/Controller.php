<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column1';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
	
	/**
	 * site settings 
	 */
	public $settings = array();
	
	public function init()
	{
		$settins_json = Options::model()->find('option_name=:option_name', array(':option_name'=>'settings'));
		$this->settings = json_decode($settins_json->attributes['option_value']);
		
		//初始化网站名称 
		Yii::app()->name = $this->settings->site_name;
		
		
		//初始化网站主题
		Yii::app()->theme = $this->settings->theme;
		
	}

}