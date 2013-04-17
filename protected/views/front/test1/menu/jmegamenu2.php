
<h2>here your go!</h2>

<hr/>
<?php
$this->widget('my.widgets.jmegamenu2.JMegaMenu2', array(
    'selector' => '.megamenu',
    'skin' => 'blue', //black/blue/clean/demo/graphite/grey
    'options' => array(
        'debug' => true,

    )
));
?>

<ul class="megamenu">
    <li>
        <a href="javascript: void(0)">First Menu</a>
        <div style="width: 350px;">Contents of the first mega menu</div>
    </li>
    <li>
        <a href="javascript: void(0)">Second Menu</a>
        <div style="width: 350px;">Contents of the second mega menu</div>
    </li>
</ul>
