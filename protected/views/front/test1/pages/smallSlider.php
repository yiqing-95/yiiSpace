

<?php $this->widget('my.widgets.smallslider.JSmallSlider', array(
    'selector'=>'#flashbox',
));

$bu = "http://lib.kindcent.com/smallslider/rs/";

?>

<h2>here your go!</h2>
<div class="row">

    <div id="flashbox" class="smallslider span3" style="height: 500px">
        <ul>
            <li><a href="#"><img src="<?php echo $bu; ?>images/001.jpg" title="" alt="图片标题1" /></a></li>
            <li><a href="#"><img src="<?php echo $bu; ?>images/002.jpg" title="" alt="图片标题2" /></a></li>
            <li><a href="#"><img src="<?php echo $bu; ?>images/003.jpg" title="" alt="图片标题3" /></a></li>
            <li><a href="#"><img src="<?php echo $bu; ?>images/004.jpg" title="" alt="图片标题4" /></a></li>
            <li><a href="#"><img src="<?php echo $bu; ?>images/005.jpg" title="" alt="图片标题5" /></a></li>
        </ul>
    </div>
    <div class="span5" style="height: 400px">


    </div>

</div>