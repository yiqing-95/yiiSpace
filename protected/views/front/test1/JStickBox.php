<?php $this->widget('my.widgets.jstickybox.JStickyBox', array(
    'selector' => '.stickybox',
    'debug'=>true ,
    'options' => array(

    )
));

?>


<div id="left">
    <h2>Left column</h2>
    <ol id="navbox" class="stickybox">
        <li><a href="#">Home</a></li>
        <li><a href="#">About</a></li>
        <li><a href="#">Contact</a></li>
    </ol>
</div>
<div id="middle"></div>
<div id="right">
    <h2>Right column</h2>
    <div id="basket" class="stickybox">
        <h3>Your basket</h3>
        <p>Total: <strong>&pound;455.00</strong></p>
        <span id="items">4</span>
    </div>
</div>