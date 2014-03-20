<?php
/**
 * Description of MVote
 *
 * @author martin
 * @version 1.0
 * @link http://www.yiibase.com
 * @copyright 2013 yiibase.com
 * @license YiiBase http://www.yiibase.com/license.html
 */
class MVote extends CWidget{
    
	/**
	 * 设置主题
	 * @var string 主题名称
	 */
    public $theme = 'blue';
	
	/**
	 * 设置提交URL
	 * @var string 布尔值
	 */
	public $requestUrl = '/request/vote';
		
	/**
	 * 文章主键
	 * @var int
	 */
	private $_id;
	
	/**
	 * 顶文章总数
	 * @var int
	 */
    private $_number= 0;
	
	/**
	 * 顶数据库
	 * @var array
	 */
	private $_data;
	
	/**
	 * 用户是否已项
	 * @var boolean
	 */
	private $_ready;
	
	/**
	 * 是否需要创建新数据
	 * @var int
	 */
	 private $_create = 0;
	 
	 /**
	  * 设置主题CSS样式
	  * @var array
	  */
	 private $_style;
	 
    /**
     * Initializes the widget.
     * This method will initialize required property values
     */

    public function init()
    {
		(int)$this->_id = Yii::app()->request->getParam('id');		
		$this->_setStyle();
		$this->_number = $this->_getData();
		$this->_ready = $this->_isReady();
        $this->registerScript();
    }
    
	/**
	 * Get Count Data
	 */
	private function _getData()
	{
		$this->_data = Yii::app()->db->createCommand("select number,post_id from `cstar_rating` where `post_id` = :pid")->bindValue(':pid',$this->_id)->queryRow();
		if(!empty($this->_data) && 0 < $this->_data['number']){
			$this->_create = '1';
			return $this->_data['number'];
		}else{
			return 0;
		}
	}
	
	
	/**
	 * Check User Status
	 */
	private function _isReady()
	{
		$cookie = Yii::app()->request->getCookies();
		//unset($cookie['user_vote']);
		if(isset($cookie["user_vote_{$this->_id}"]->value) && 0 < $cookie["user_vote_{$this->_id}"]->value)
			return false;
		else
			return true;
	}
	
	/**
	 * Set Theme Style
	 */
	private function _setStyle()
	{
		$this->_style = array(
			'blue'=>array('bdlikebutton'=>'0 -175px','number'=>'-253px -24px','color'=>'#2979D3'),
			'yellow'=>array('bdlikebutton'=>'0 -120px','number'=>'-253px 6px','color'=>'#feac1c'),
			'pink'=>array('bdlikebutton'=>'0 -285px','number'=>'-253px -84px','color'=>'#fc886e'),
			'green'=>array('bdlikebutton'=>'0 -230px','number'=>'-253px -54px','color'=>'#40ae25'),
		);
	}
    /**
     * Registers necessary client scripts.
     */
    protected function registerScript()
    {
		$id = $this->getId();		
		(int)$uid = Yii::app()->user->id;
        $assets = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets';
        $baseUrl = Yii::app()->getAssetManager()->publish($assets);
        $cs = Yii::app()->clientScript;
		$seting = $cs->registerScript('#'.$id,"
			$.fn.vote_seting = {
				'ready': '{$this->_ready}',
				'url':'{$this->requestUrl}',
				'pid':'{$this->_id}',
				'create':'{$this->_create}'
			}
		");
        $cs->registerScriptFile($baseUrl.'/vote.js',CClientScript::POS_HEAD,$seting);
        $cs->registerCss($id,"
			.bdlikebutton,.bdlikebutton span.bdlikebutton_num{background: url('{$baseUrl}/like.png') no-repeat;}
			.bdlikebutton{text-align: center;height: 50px;width:105px!important;width: 100px;position: relative;z-index: 50;margin: 10px auto;cursor: pointer;background-position: {$this->_style[$this->theme]['bdlikebutton']};}
			.bdlikebutton span.bdlikebutton_count{display: none;position: absolute;z-index: 100;top:0px;left:0;font-weight: bold;font-size: 16px;}
			.bdlikebutton span.bdlikebutton_num{text-align: center;background-position: {$this->_style[$this->theme]['number']};height: 20px;float: left;padding: 5px 5px 0 5px;line-height: 22px;font-size: 14px;}
			.bdlikebutton span.bdlikebutton_text{text-align: center;font-weight: bold;font-size: 14px;float:left;}
			.bdlikebutton span.bdlikebutton_count,.bdlikebutton span.bdlikebutton_num,.bdlikebutton span.bdlikebutton_text{width: 100%;color: {$this->_style[$this->theme]['color']};} 
		");
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        echo "<div class='bdlikebutton'><span class='bdlikebutton_count'>+1</span><span class='bdlikebutton_num'>{$this->_number}</span><span class='bdlikebutton_text'>顶</span></div>";
    }
    
}
