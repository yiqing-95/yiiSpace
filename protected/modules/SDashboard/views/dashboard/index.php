<script type="text/javascript">
$().ready(function(){

		make_draggable($('.portlet-li'));
		make_resizeable($('.portletcontent'));	
 		
});
</script>

<div id="dashboard-index">
		
	<div id="portlets_wrapper" style="padding:20px;min-height:600px;">
		<a href="#foo" id="show-active" data-toggle="modal"><i class="icon-wrench"></i></a>

		<a href="#formModal" id="show-form" data-toggle="modal"><i class="icon-plus"></i></a>

		
		<div id="update-form-wrapper"></div>
	
		<div id="active-form-wrapper" >
		<?php $this->renderPartial('sdashboard.views.dashboard._activeform' ,array() );?>
		</div>
		<div id="form-wrapper">
			<?php  $this->renderPartial('sdashboard.views.dashboard._form',array('model'=>$model));?>
		</div>
		<div id="portletsUpdated"></div>
		<?php $this->widget('bootstrap.widgets.BootThumbnails', array(
				'dataProvider'=>$dataProvider,
				'viewData'=>array(),
				'template'=>"{items}",
				'itemView'=>'_portlet',
		)); ?>
			
	</div>
		

</div> <!-- dashboard-index-->
