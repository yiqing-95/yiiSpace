		


		/* deleting a portlet */
		$(".delete_portlet").live('click',function(){
		var id = $(this).attr('name');

		bootbox.confirm("Sure you want to delete? (There is NO undo!)", "Cancel", "Yes, I'm sure!",
		 function(result) {
   				 if(result){
	   				 $.ajax({
					  type: "POST",
					  url: "/sdashboard/dashboard/delete",
					  cache:false,
					  data: {id:id}
					}).done(function( data ) {
							$('#'+id).remove();
							reloadPortlets();
							$().toastmessage('showSuccessToast', "Successfull!");
					});
   				 }
 			});
		
		});
		
		/* fetch the model to update*/
		$(".update_portlet").live('click',function(event){
		event.preventDefault();
		var id = $(this).attr('href');
		$.ajax({'url':'/sdashboard/dashboard/update?id='+id,'cache':false,'success':function(html){
		jQuery("#form-wrapper").html(html);
		$("#formModalBody").removeClass('moveOut');
		$("#formModal").modal('show');

		}});			
		});
	

		
			/*deactivate a visible portlet (hide link)*/
		$(".hideportlet").live('click',function(){
		var id = $(this).parent().parent("li").attr('id');
		$.ajax({
			  type: "POST",
			  url: "/sdashboard/dashboard/deactivateportlet",
			  data: {id:id},
			}).done(function( data ) {
					$("#form-wrapper").fadeOut();
					reloadPortlets();
				});
		});
		/* Submitting the  create form*/
		$("#dashboard-form").live('submit',function(event){
		event.preventDefault();
		addPortlet($(this).serialize());
		$("#dashboard-form").empty();
		return false;			
	});

		$("#submitform").live('click',function(){
			$("#dashboard-form").submit();
		});

	/* Submitting the active-portlets-form form*/
		$("#active-portlets-form").live('submit',function(event){
		event.preventDefault();
			$.ajax({
			  type: "POST",
			  url: "/sdashboard/dashboard/active",
			  data: $(this).serialize(),
			  cache:false,
			  dataType: 'json',
			}).done(function( data ) {
			if(data.status == 'success'){
				$('#activeFormModal').modal('hide');
				$(".modal-backdrop").hide();
				reloadPortlets();
				$().toastmessage('showSuccessToast', "Successfull!");
			}else{
				$().toastmessage('showErrorToast', "Something went wrong!");
				}
			});
	});

		/*some init */

		
		/* toggle content view*/
		$(".toggle").live('click',function(){
			$(this).siblings(".portletcontent").slideToggle(200);
			$(this).parent().removeClass('maximized');
			$(this).parent().attr('style','');
		});
		/* toggle maximizing class */
		$(".maximize").live('click',function(){

		var w=window,d=document,e=d.documentElement,g=d.getElementsByTagName('body')[0],width=w.innerWidth||e.clientWidth||g.clientWidth,height=w.innerHeight||e.clientHeight||g.clientHeight, fixedWidth = width-65, fixedHeight = height-100,style = 'min-width:'+fixedWidth+'px; min-height:'+fixedHeight+'px;';
			$(this).siblings(".portletcontent").fadeIn();
			if($(this).parent().hasClass('maximized')){
				$(this).parent().removeClass('maximized');
				$(this).parent().attr('style','');
				}else{
				$(this).parent().attr('style',style);
				$(this).parent().addClass('maximized');
			}
		});

		
		$("#show-form").live('click',function(){
			$("#Dashboard_title").val('');
			$("#Dashboard_content").val('');
			$("#Dashboard_ajaxrequest").val('');
			$("#Dashboard_newrecord").val('create');
			$("#maintitle").html('Create Portlet');
			$("#formModalBody").removeClass('moveOut');
		});
	/* js functions  */
		function make_resizeable(elements){
			elements.resizable({
				minWidth: 110,
				stop: function(event, ui) { 
				var id = $(this).parent().parent("li").attr('id');
				updatePortletsSize(id,ui.size);
				}
			});
		
		}
		function make_draggable(elements)
		{
			/* Elements is a jquery object: */
		
			elements.draggable({
				containment:'window',
				stop:function(e,ui){
				var pos = ui.position.top+","+ui.position.left;
				var id = $(this).attr('id');
				updatePortletsPos(id,pos);
				}
			});
		}
		function updatePortletsSize(id,size) {

			var url = '/sdashboard/dashboard/updatePortletsSize';
			jQuery.getJSON(url, {id:id,width:size.width,height:size.height}, function(data) {
				if (data.status == 'success') {
				}
			});
			return false;
		}
		function updatePortletsPos(id,pos) {
			var url = '/sdashboard/dashboard/updatePortlets';
			jQuery.getJSON(url, {id:id,pos: pos}, function(data) {
				if (data.status == 'success') {
				}
			});
			return false;
		}
		function reloadPortlets(){
			$.ajax({'url':'/sdashboard/dashboard/index','cache':false,'success':function(html){jQuery("#dashboard-index").html(html)}});		
		}
		function addPortlet(data){

			$.ajax({
			  type: "POST",
			  url: "/sdashboard/dashboard/create",
			  data: data,
			  cache:false,
  			  dataType: 'json',
			}).done(function( data ) {
			if(data.status == 'success'){
				$('#formModal').modal('hide')
				$(".modal-backdrop").hide();
				reloadPortlets();
				$().toastmessage('showSuccessToast', "Successfull!");
			return false;
			}else{
				$().toastmessage('showErrorToast', "Validation failed!");
				}
			});
			return false;
		}