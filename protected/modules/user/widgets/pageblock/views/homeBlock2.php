<?php
 $this->widget("ext.FleetBox.FleetBoxWidget", array(
            'header' => array(
                'title' => 'Header',
                'addon' => '',         //additional string with data near header
                'actions' => array(
                    CHtml::link('add', '#', array('class' => 'btn btn-mini')),
                    CHtml::link('delete', '#'),
                    )
                ),

            'size' => 'small',         //'','large','small'
            'body' => 'FleetBox size:small',              
            )
        );
?>