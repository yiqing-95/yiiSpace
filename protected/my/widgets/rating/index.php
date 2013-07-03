<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Pluggin jRating : Ajax rating system with jQuery</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="jquery/jRating.jquery.css" type="text/css" />
	
	<style type="text/css">
		body {margin:15px;font-family:Arial;font-size:13px}
		a img{border:0}
		.datasSent, .serverResponse{margin-top:20px;width:470px;height:73px;border:1px solid #F0F0F0;background-color:#F8F8F8;padding:10px;float:left;margin-right:10px}
		.datasSent{width:200px;position:fixed;left:680px;top:0}
		.serverResponse{position:fixed;left:680px;top:100px}
		.datasSent p, .serverResponse p {font-style:italic;font-size:12px}
		.exemple{margin-top:15px;}
		.clr{clear:both}
		pre {margin:0;padding:0}
		.notice {background-color:#F4F4F4;color:#666;border:1px solid #CECECE;padding:10px;font-weight:bold;width:600px;font-size:12px;margin-top:10px}
	</style>
</head>
<body>
<ul>
	<li>Full documentation on <a href="http://www.myjqueryplugins.com/jRating">MyjQueryPlugins.com/jRating</a></li>
	<li>Live demonstration on <a href="http://www.myjqueryplugins.com/jRating/demo">jRating demonstration page</a></li>
</ul>
<h1>jRating plugin</h1>


<!-- EXEMPLE 1 : BASIC -->
<div class="exemple">
	<em>Exemple 1 (<strong>Basic exemple without options</strong>) :</em>
	<div class="basic" id="12_1"></div>
</div>
<div class="notice">
<pre>
<?php
echo htmlentities('<!-- JS to add -->
<script type="text/javascript">
  $(document).ready(function(){
    $(".bacic").jRating();
  });
</script>
');
?>
</pre>
</div>

<!-- EXEMPLE 2 -->
<div class="exemple">
	<em>Exemple 2 (type : <strong>small</strong> - average <strong>10</strong> - id <strong>2</strong> - <strong>40</strong> stars) :</em>
	<div class="exemple2" id="10_2"></div>
</div>
<div class="notice">
<pre>
<?php
echo htmlentities('<!-- JS to add -->
<script type="text/javascript">
  $(document).ready(function(){
    $(".exemple2").jRating({
	  type:\'small\', // type of the rate.. can be set to \'small\' or \'big\'
	  length : 40, // nb of stars
	  decimalLength : 1 // number of decimal in the rate
    });
  });
</script>
');
?>
</pre>
</div>
	
<!-- EXEMPLE 3 -->
<div class="exemple">
	<em>Exemple 3 (step : <strong>true</strong> - average <strong>18</strong> - id <strong>3</strong> - <strong>15</strong> stars) :</em>
	<div class="exemple3" id="18_3"></div>
</div>
<div class="notice">
<pre>
<?php
echo htmlentities('<!-- JS to add -->
<script type="text/javascript">
  $(document).ready(function(){
    $(".exemple3").jRating({
	  step:true, // no half-star when mousemove
	  length : 20, // nb of stars
	  decimalLength:0 // number of decimal in the rate
    });
  });
</script>
');
?>
</pre>
</div>

<!-- EXEMPLE 4 -->
<div class="exemple">
	<em>Exemple 4 (<strong>Rate is disabled</strong>) :</em>
	<div class="exemple4" id="10_4"></div>
</div>
<div class="notice">
<pre>
<?php
echo htmlentities('<!-- JS to add -->
<script type="text/javascript">
  $(document).ready(function(){
    $(".exemple4").jRating({
	  isDisabled : true
	});
  });
</script>
');
?>
</pre>
</div>

<!-- EXEMPLE 5 -->
<div class="exemple">
	<em>Exemple 5 (<strong>With onSuccess &amp; onError methods</strong>) :</em>
	<div class="exemple5" id="10_5"></div>
</div>
<div class="notice">
<pre>
<?php
echo htmlentities('<!-- JS to add -->
<script type="text/javascript">
  $(document).ready(function(){
    $(".exemple5").jRating({
	  length:10,
	  decimalLength:1,
	  onSuccess : function(){
	    alert(\'Success : your rate has been saved :)\');
	  },
	  onError : function(){
	    alert(\'Error : please retry\');
	  }
	});
  });
</script>
');
?>
</pre>
</div>

<!-- EXEMPLE 5 -->
<div class="exemple">
	<em>Exemple 6 (<strong>Disabled rate info</strong>) :</em>
	<div class="exemple6" id="10_5"></div>
</div>
<div class="notice">
<pre>
<?php
echo htmlentities('<!-- JS to add -->
<script type="text/javascript">
  $(document).ready(function(){
    $(".exemple6").jRating({
	  length:10,
	  decimalLength:1,
	  showRateInfo:false
	});
  });
</script>
');
?>
</pre>
</div>



<div class="datasSent">
	Datas sent to the server :
	<p></p>
</div>
<div class="serverResponse">
	Server response :
	<p></p>
</div>

	<script type="text/javascript" src="jquery/jquery.js"></script>
	<script type="text/javascript" src="jquery/jRating.jquery.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$('.basic').jRating();
			
			$('.exemple2').jRating({
				type:'small',
				length : 40,
				decimalLength : 1
			});
			
			$('.exemple3').jRating({
				step:true,
				length : 20
			});
			
			$('.exemple4').jRating({
				isDisabled : true
			});
			
			$('.exemple5').jRating({
				length:10,
				decimalLength:1,
				onSuccess : function(){
					alert('Success : your rate has been saved :)');
				},
				onError : function(){
					alert('Error : please retry');
				}
			});
			
			$(".exemple6").jRating({
			  length:10,
			  decimalLength:1,
			  showRateInfo:false
			});
		});
	</script>
</body>
</html>
