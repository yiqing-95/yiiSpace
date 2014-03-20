<?php
/**
 *
 * User: yiqing
 * Date: 13-3-14
 * Time: 下午8:21
 * To change this template use File | Settings | File Templates.
 * -------------------------------------------------------
 * -------------------------------------------------------
 */
class Amazium extends CWidget
{
    private $baseUrl;


    public function init()
    {


       $bu =  $this->baseUrl = Yii::app()->getAssetManager()->publish(dirname(__FILE__) . '/assets');

         // 懒得搞了：
        $init = <<<EOD
<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<!-- Mobile Specific Metas -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

	<script type="text/javascript">
		var browser			= navigator.userAgent;
		var browserRegex	= /(Android|BlackBerry|IEMobile|Nokia|iP(ad|hone|od)|Opera M(obi|ini))/;
		var isMobile		= false;
		if(browser.match(browserRegex)) {
			isMobile			= true;
			addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false);
			function hideURLbar(){
				window.scrollTo(0,1);
			}
		}
	</script>

	<!-- CSS -->
	<link rel="stylesheet" href="{$bu}/includes/base.css">
	<link rel="stylesheet" href="{$bu}/includes/amazium.css">
	<link rel="stylesheet" href="{$bu}/includes/layout.css">

	<!-- Favicons -->
	<link rel="shortcut icon" href="{$bu}/images/favicon.ico">
	<link rel="apple-touch-icon-precomposed" href="{$bu}/images/apple-touch-icon.png">
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="{$bu}/images/apple-touch-icon-72x72.png" />
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="{$bu}/images/apple-touch-icon-114x114.png" />

	<!-- To Top scripts -->
	<script src="{$bu}/includes/smoothscroll.js"type="text/javascript" ></script>
	<script src="{$bu}/includes/jquery.easing.1.3.js" type="text/javascript"></script>
	<script src="{$bu}/includes/jquery.ui.totop.js" type="text/javascript"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$().UItoTop({ easingType: 'easeOutQuart' });
		});
	</script>
EOD;
     cs()->registerCoreScript('jquery');
      echo $init ;

    }


}