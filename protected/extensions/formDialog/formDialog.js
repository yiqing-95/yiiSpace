(function ($) {
	$.fn.formDialog=function(options){
		var link=$(this);

		link.click(function(e){
			e.preventDefault();
			$.ajax({
				'url': link.attr('href'),
				'dataType': 'json',
				'success': function(data){
					var dialog=$('<div style="display:none;"><div class="forView"></div></div>');			
					$('body').append(dialog);
					dialog.dialog();
					dialog.find('.forView').html(data.view || data.form);
					
					dialog.delegate('form', 'submit', function(e){
						e.preventDefault();
						$.ajax({
							'url': link.attr('href'),
							'type': 'post',
							'data': $(this).serialize(),
							'dataType': 'json',
							'success': function(data){
								if (data.status=='failure')
									dialog.find('.forView').html(data.view || data.form);
								else if (data.status=='success'){
									dialog.dialog('close').detach();
									options['onSuccess'](data, e);
								}
							}
						});

					});
				}
			});
		});
	}
})(jQuery);	