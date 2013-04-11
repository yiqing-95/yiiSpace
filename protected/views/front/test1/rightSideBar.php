<?php
$this->beginWidget('application.extensions.rightsidebar.RightSidebar', array('title' => 'Menu', 'collapsed' => true));

$this->widget('zii.widgets.CMenu', array(
    'items' => array(
        array('label' => 'Home', 'url' => array('site/index')),
        array('label' => 'Products', 'url' => array('product/index'), 'items' => array(
            array('label' => 'New Arrivals', 'url' => array('product/new')),
            array('label' => 'Most Popular', 'url' => array('product/popular')),
        )),
    ),
));

$this->endWidget();
?>