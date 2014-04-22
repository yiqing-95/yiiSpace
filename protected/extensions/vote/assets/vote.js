/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function(){

	$.fn.vote_seting = {
	}
	
    $('.bdlikebutton').click(function(){
		var reset = function(){
			$('.bdlikebutton_text').css("font-size","14px");
			$('.bdlikebutton_text').html('顶');
		}	
		var error = function(){
			$('.bdlikebutton_text').css("font-size","12px");
			$('.bdlikebutton_text').html('您今天已顶过了');
		}
		if($.fn.vote_seting.ready && 0 < $.fn.vote_seting.url.length && 0 < $.fn.vote_seting.pid.length){
			var target = $(".bdlikebutton span.bdlikebutton_count");
			var param = $('.bdlikebutton span.bdlikebutton_num');
			var text = parseInt(param.text());
			$.ajax({
			   type: "GET",
			   url: $.fn.vote_seting.url,
			   dataType:'json',
			   data: 'pid='+$.fn.vote_seting.pid+'&c='+$.fn.vote_seting.create,
			   success: function(msg){
				 if(msg.code == '1'){
					target.animate({top: '-20px', opacity:'show'}, "slow",'linear',target.css('top','0')); 
					target.animate({opacity:'hide'}, "slow",'linear',param.html(++text)); 
				 }else{
					error();
					setTimeout(reset,2000);
				 }
			   }
			});					
		}else{		
			error();
			setTimeout(reset,2000);
		}
    });
});