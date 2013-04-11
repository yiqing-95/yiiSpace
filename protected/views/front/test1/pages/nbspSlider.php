

<?php $this->widget('my.widgets.nbspSlider.NbspSlider', array(
    'selector'=>'#flashbox',
    'options'=>array(
        'widths'=>'400px',
        'heights'=>'200px',
    )
));

$this->widget('my.widgets.nbspSlider.NbspSlider', array(
    'selector'=>'#flashbox2',
    'options'=>array(
        'widths'=>'400px',
        'heights'=>'200px',
        'effect'=>'vertical',
    )
));

$this->widget('my.widgets.nbspSlider.NbspSlider', array(
    'selector'=>'#flashbox3',
    'options'=> new CJavaScriptExpression('
    {
			widths:           "800px",
			heights:          "300px",
			effect:	         "horizontal",
			autoplay:        1,
			speeds:          500,
			delays:          5000,
			altOpa:          0.5,            // ALT区块透明度
			altBgColor:      "#ccc",  		// ALT区块背景颜色
			altHeight:       "40px",  		// ALT区块高度
			altShow:         1,				// ALT区块是否显示(1为是0为否)
			altFontColor:    "blue",        // ALT区块内的字体颜色
			btnFontColor:    "red",        // 数字按钮的数字颜色
			btnBorderColor:  "#ccc",        // 数字按钮边框颜色
			btnBgColor:      "yellow",		// 数字按钮背景颜色
			btnActBgColor:   "green"		// 数字按钮选中的背景
		}
    ')
));

$bu = "http://lib.kindcent.com/smallslider/rs/";

?>

<h2>here your go!</h2>
<div class="row">

    <div id="flashbox" class="smallslider span3" >
        <ul>
            <li><a href="#"><img src="<?php echo $bu; ?>images/001.jpg" title="" alt="图片标题1" /></a></li>
            <li><a href="#"><img src="<?php echo $bu; ?>images/002.jpg" title="" alt="图片标题2" /></a></li>
            <li><a href="#"><img src="<?php echo $bu; ?>images/003.jpg" title="" alt="图片标题3" /></a></li>
            <li><a href="#"><img src="<?php echo $bu; ?>images/004.jpg" title="" alt="图片标题4" /></a></li>
            <li><a href="#"><img src="<?php echo $bu; ?>images/005.jpg" title="" alt="图片标题5" /></a></li>
        </ul>
    </div>

    <div id="flashbox2" class="smallslider span3" >
        <ul>
            <li><a href="#"><img src="<?php echo $bu; ?>images/001.jpg" title="" alt="图片标题1" /></a></li>
            <li><a href="#"><img src="<?php echo $bu; ?>images/002.jpg" title="" alt="图片标题2" /></a></li>
            <li><a href="#"><img src="<?php echo $bu; ?>images/003.jpg" title="" alt="图片标题3" /></a></li>
            <li><a href="#"><img src="<?php echo $bu; ?>images/004.jpg" title="" alt="图片标题4" /></a></li>
            <li><a href="#"><img src="<?php echo $bu; ?>images/005.jpg" title="" alt="图片标题5" /></a></li>
        </ul>
    </div>

    <div id="flashbox3" class="smallslider span3" >
        <ul>
            <li><a href="#"><img src="<?php echo $bu; ?>images/001.jpg" title="" alt="图片标题1" /></a></li>
            <li><a href="#"><img src="<?php echo $bu; ?>images/002.jpg" title="" alt="图片标题2" /></a></li>
            <li><a href="#"><img src="<?php echo $bu; ?>images/003.jpg" title="" alt="图片标题3" /></a></li>
            <li><a href="#"><img src="<?php echo $bu; ?>images/004.jpg" title="" alt="图片标题4" /></a></li>
            <li><a href="#"><img src="<?php echo $bu; ?>images/005.jpg" title="" alt="图片标题5" /></a></li>
        </ul>
    </div>
</div>