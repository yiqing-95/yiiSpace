
<h2>here your go!</h2>

<hr/>
<?php
$this->widget('my.widgets.jvaMenu.JVaMenu', array(
    'selector' => '#demo2',
    'skin' => 'blue', //black/blue/clean/demo/graphite/grey
    'options' => array(
        'debug' => true,

    )
));


?>

<div class=" dcjq-accordion" style="padding: 8px 0;" id="demo2">

    <ul id="yw18" class="nav nav-list">
        <li class="nav-header">LIST HEADER</li>
        <li class="active">
            <a href="#"><i class="icon-home"></i> Home</a>
            <ul>
                <li>
                    <a href="#"><i class="icon-book"></i> Library</a>
                    <ul>
                        <li><a href="#"><i class="icon-book"></i> Library</a>
                        </li>
                        <li>
                            <a href="#"><i class="icon-pencil"></i> Application</a>
                        </li>
                        <li class="nav-header">ANOTHER LIST HEADER</li>

                    </ul>
                </li>
                <li>
                    <a href="#"><i class="icon-pencil"></i> Application</a>
                </li>
                <li class="nav-header">ANOTHER LIST HEADER</li>

            </ul>
        </li>
        <li>
            <a href="#"><i class="icon-book"></i> Library</a>
            <ul>
                <li><a href="#"><i class="icon-book"></i> Library</a>
                </li>
                <li>
                    <a href="#"><i class="icon-pencil"></i> Application</a>
                </li>
                <li class="nav-header">ANOTHER LIST HEADER</li>

            </ul>
        </li>
        <li>
            <a href="#"><i class="icon-pencil"></i> Application</a>
            <ul>
                <li><a href="#"><i class="icon-book"></i> Library</a>
                </li>
                <li>
                    <a href="#"><i class="icon-pencil"></i> Application</a>
                </li>
                <li class="nav-header">ANOTHER LIST HEADER</li>

            </ul>
        </li>
        <li class="nav-header">ANOTHER LIST HEADER</li>
        <li><a href="#">
            <i class="icon-user"></i> Profile</a></li>
        <li><a href="#"><i class="icon-cog">

        </i> Settings</a></li>
        <li><a href="#"><i class="icon-flag"></i> Help</a></li>
    </ul>
</div>
 <hr/>
<!--<iframe src="" name="contentFrame" id="contentFrame" frameborder="false"-->
<!--        width="100%"-->
<!--        height="900px"/>-->