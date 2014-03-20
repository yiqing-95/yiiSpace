<?php $this->widget('my.widgets.stickyfloat.JStickyFloat', array(
    'selector' => '#floatedbox',
    'debug'=>true ,
    'options' => 'js:{duration: 400}'
));

?>


<div id="realtest">
    <div id="innerposi">
        <div id="floatedbox" class="stickybox">Some randomly positioned box</div>
    </div>
</div>
<div id="wrap">
    <header>
        <h1>jQuery Sticky Sidebar</h1>
        <div id="left">
            <h2>Left column</h2>
            <ol id="navbox" class="stickybox">
                <li><a href="#">Home</a></li>
                <li><a href="#">About</a></li>
                <li><a href="#">Contact</a></li>
            </ol>
        </div>
        <div id="middle">
            <ul id="demoLinks">
                <li>
                    <a href="#" class="remove" rel="navbox">Remove nav box</a>
                </li>
                <li>
                    <a href="#" class="remove" rel="basket">Remove basket</a>
                </li>
                <li>
                    <a href="#" class="remove" rel="floatedbox">Remove box</a>
                </li>
                <li>
                    <a href="#" id="destroyAll">Destroy all</a>
                </li>
            </ul>
        </div>
        <div id="right">
            <h2>Right column</h2>
            <div id="basket" class="stickybox">
                <h3>Your basket</h3>
                <p>Total: <strong>&pound;455.00</strong></p>
                <span id="items">4</span>
            </div>
        </div>
</div>
<?php  echo str_repeat('<br/>',30);?>
<footer>
</footer>

</body>
