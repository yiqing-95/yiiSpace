<script type="text/javascript">
$().ready(function(){

		make_draggable($('.portlet-li'));
		make_resizeable($('.portletcontent'));	
 		
});
</script>
<div id="dashboard-index">
		
	<div id="portlets_wrapper" style="padding:20px;min-height:600px;">			
	
		<div id="portletsUpdated"></div>
		<?php $this->widget('bootstrap.widgets.BootThumbnails', array(
				'dataProvider'=>$dataProvider,
				'viewData'=>array(),
				'template'=>"{items}",
				'itemView'=>'_modifyportlets',
		)); ?>
			
	</div>
		

</div> <!-- dashboard-index-->
