<?php $this->widget('my.widgets.jvaMenu.JVaMenu', array(
    'selector' => '#dc_jqaccordion_widget-8-item',
    'skin' => 'black',
    'options' => array(
        'debug' => true,

    )
));



?>

<h2>here your go!</h2>
<div class="dcjq-accordion" id="dc_jqaccordion_widget-8-item">

    <ul id="menu-demo-2" class="menu">
        <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-48 dcjq-parent-li"><a href="#"
                                                                                                           class="dcjq-parent">Category
            A<span class="dcjq-icon"></span></a>
            <ul class="sub-menu" style="display: none;">
                <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-52 dcjq-parent-li"><a
                    href="#" class="dcjq-parent">Range 1<span class="dcjq-icon"></span></a>

                    <ul class="sub-menu" style="display: none;">
                        <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-57"><a href="#">Product
                            A1</a></li>
                    </ul>
                </li>
                <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-53 dcjq-parent-li"><a
                    href="#" class="dcjq-parent">Range 2<span class="dcjq-icon"></span></a>
                    <ul class="sub-menu" style="display: none;">
                        <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-74"><a href="#">Product
                            A2</a></li>
                    </ul>

                </li>
            </ul>
        </li>
        <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-49 dcjq-parent-li"><a href="#"
                                                                                                           class="dcjq-parent">Category
            B<span class="dcjq-icon"></span></a>
            <ul class="sub-menu" style="display: none;">
                <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-54"><a href="#">Range 3</a>
                </li>
            </ul>
        </li>
        <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-50 dcjq-parent-li"><a href="#"
                                                                                                           class="dcjq-parent">Category
            C<span class="dcjq-icon"></span></a>
            <ul class="sub-menu" style="display: none;">
                <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-55"><a href="#">Range 4</a>
                </li>

            </ul>
        </li>
        <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-51 dcjq-parent-li"><a href="#"
                                                                                                           class="dcjq-parent">Category
            D<span class="dcjq-icon"></span></a>
            <ul class="sub-menu" style="display: none;">
                <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-56"><a href="#">Range 5</a>
                </li>
            </ul>
        </li>
    </ul>
</div>
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
  <script type="text/javascript">
      $(function(){

      });
  </script>