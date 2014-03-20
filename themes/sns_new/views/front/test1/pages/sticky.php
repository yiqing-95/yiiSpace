<?php $this->widget('my.widgets.JSticky', array(
    'selector' => '#sticker',
    'options' => 'js:{ topSpacing: 10, className: "sticky", scrollLength: 0 }'
));

?>


<style>
    body {
        height: 10000px;
        padding: 0;
        margin: 0;
    }

    #sticker {
        background: #bada55;
        color: white;
        width: 300px;
        font-family: Droid Sans;
        font-size: 40px;
        line-height: 1.6em;
        font-weight: bold;
        text-align: center;
        padding: 20px;
        margin: 0;
        text-shadow: 0 1px 1px rgba(0,0,0,.2);
        border-radius: 50px;
    }

    #sticker.sticky {
        background: orange;
    }
</style>


<p>This is test this is text this is text.</p>
<div id="sticker">
    <p>This is the sticky thingy that is really cool.</p>
</div>
<p>This is test this is text this is text.</p>