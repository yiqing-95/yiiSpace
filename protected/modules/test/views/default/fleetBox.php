
<?php

$this->widget("test.extensions.FleetBox.FleetBoxWidget",array(
        'header' => array(
            'title' => 'Header',
            'addon' => '',         //additional string with data near header
            'actions' =>array(
                CHtml::link('add', '#', array('class' => 'btn btn-mini')),
                CHtml::link('delete', '#'),
            )
        ),
        'size' => 'small',         //'','large','small'
        'body' => 'FleetBox size:small',
    )
);
$this->widget("test.extensions.FleetBox.FleetBoxWidget",array(
        'header' => array(
            'title' => 'Header2',
            'addon' => '',         //additional string with data near header
            'actions' =>array(
                CHtml::link('add', '#', array('class' => 'btn btn-mini')),
                CHtml::link('delete', '#'),
            )
        ),
        'size' => 'large',         //'','large','small'
        'body' => 'FleetBox size:large',
    )
);