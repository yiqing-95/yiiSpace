<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Plugin jNotify v2.0</title>
	<meta http-equiv="description" content="" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="jquery/jNotify.jquery.css" type="text/css" />
</head>
<body>
<ul>
	<li>Full documentation on <a href="http://www.myjqueryplugins.com/jNotify">MyjQueryPlugins.com/jNotify</a></li>
	<li>Live demonstration on <a href="http://www.myjqueryplugins.com/jNotify/demo">jNotify demonstration page</a></li>
</ul>
<h1>jNotify plugin</h1>
<p>
<a href="#" class="success">It's a success !</a><br />
<a href="#" class="notice">It's a notice !</a><br />
<a href="#" class="error">It's an error !</a><br /><br />
<a href="#" class="three">Success, Notify and Error !!</a><br />

</p>
<script type="text/javascript" src="jquery/jquery.js"></script>
<script type="text/javascript" src="jquery/jNotify.jquery.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		/** success **/
		$("p a").click(function(e){
			e.preventDefault();
			switch ($(this).attr('class'))
			{
				case 'success' : jSuccess('Congratulations, a success box !!'); break;
				case 'notice' : jNotify('Notification : <strong>Bold</strong>, <u>Underline</u> and <i>Italic</i> !'); break;
				case 'error' : jError('ERROR : please retry !'); break;
				case 'three' : 
					jSuccess('Success : a notify box comes after',
					{
						TimeShown : 2500,
						onClosed:function(){
							jNotify('Notify : an error to finish. Please click to show the Error box',
								{
									VerticalPosition : 'top',
									autoHide : false,
									onClosed:function(){
										jError('ERROR : now it\'s finished !<br />Please click on the overlay background to close jNotify ! :)',{
											clickOverlay : true,
											autoHide : false,
											HorizontalPosition : 'left'
										});
									}
								});
							}
					}); 
					break;
			}
			
		});
	});
</script>
</body>
</html>
