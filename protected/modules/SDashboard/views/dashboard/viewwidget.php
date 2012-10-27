<!-- this is just a demo showing how to use the widget -->
<div style="min-height:700px;">
	
	<?php $this->widget('viewPortlets',array('userid'=>0));?>
</div>

<!-- You can erase whats below here, just info for you since this is a demo page -->
<!-- And you can move the above widget call in whatever view you like -->
<?php
	
		$assets =Yii::app()->basePath.'/modules/sdashboard/assets';
		$baseUrl = Yii::app() -> assetManager -> publish($assets);
		//the css to use
		Yii::app() -> clientScript -> registerCssFile($baseUrl . '/css/jquery.toastmessage.css');
		// the js to use
		Yii::app() -> clientScript -> registerScriptFile($baseUrl . "/js/jquery.toastmessage.js", CClientScript::POS_BEGIN);
?>
<div id="demotext" style="display:none;">
	This is a demo of the viewWidget. <br/>
	Show users portlets without modifying rights<br/>
	Set userid to 0 to fetch the defaultPortlets <br/>
	But you can set any user id, maybe an admin wants to see users dashboard setup, dunno<br/>
	<br/>
</div>
<script type="text/javascript">

	$().toastmessage('showToast',{
	text     :  $("#demotext").html(),
    sticky   : true,
    position : 'top-right',
    type	 : 'notice'
	});

</script>
