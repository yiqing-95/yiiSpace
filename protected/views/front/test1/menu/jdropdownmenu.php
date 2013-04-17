
<h2>here your go!</h2>

<hr/>
<?php
$availableStyles = array(
  'style1', 'style2',
);
$style =  $availableStyles[array_rand($availableStyles)] ;
$this->widget('my.widgets.jdropdownmenu.JDropDownMenu', array(
    'selector' => '.menu',
    'style' => $style , // style1 | style2
    'options' => array(
        'debug' => true,

    )
));
echo 'current style :' , $style ;

?>

<div class="menu">
    <ul>
        <li>
            <a href="#">More Examples<span class="arrow"></span></a>

            <ul>
                <li><a href="http://www.ajaxshake.com/en/JS/1111/jquery.html">Plugins and jQuery Examples</a></li>
                <li><a href="http://www.ajaxshake.com/en/JS/1121/prototype.html">Prototype Examples</a></li>
                <li><a href="http://www.ajaxshake.com/en/JS/12111/mootools.html">Mootools Examples</a></li>
                <li><a href="http://www.ajaxshake.com/en/JS/12421/pure-javascript.html">Javascript Examples</a></li>
            </ul>
        </li>
        <li>
            <a href="#">Plugins<span class="arrow"></span></a>
            <ul>
                <li><a href="http://www.ajaxshake.com/en/JS/12131/galleries.html">Galleries</a></li>
                <li><a href="http://www.ajaxshake.com/en/JS/1191/menus.html">DropDown Menus</a></li>
                <li><a href="http://www.ajaxshake.com/en/JS/12581/image-slider.html">Content Slider</a></li>
                <li><a href="http://www.ajaxshake.com/en/JS/12261/lightbox.html">LightBox</a></li>
            </ul>
        </li>
        <li>
            <a href="#">Friend Sites<span class="arrow"></span></a>
            <ul>
                <li><a href="http://www.ajaxshake.com">www.ajaxshake.com</a></li>
                <li><a href="http://www.solvingequations.net">www.solvingequations.net</a></li>
                <li><a href="http://www.tutorialjquery.com">www.tutorialjQuery.com</a></li>
                <li><a href="http://www.jqueryload.com">www.jqueryload.com</a></li>
            </ul>
        </li>
        <li>
            <a href="#">Contact<span class="arrow"></span></a>
            <ul>
                <li><a href="http://www.twitter.com/ajaxshake">Follow us on Twitter</a></li>
                <li><a href="http://www.facebook.com/ajaxshake">Facebook</a></li>
                <li><a href="http://feeds.feedburner.com/ajaxshake">Rss</a></li>
                <li><a href="mailto:info@ajaxshake.com">e-mail</a></li>
            </ul>
        </li>
    </ul>
</div>

