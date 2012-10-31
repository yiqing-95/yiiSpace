<?php
$this->widget('bootstrap.widgets.TbBox', array(
    'title' => 'Basic Box',
    'headerIcon' => 'icon-home',
    'content' => 'So this box has actions, isn\'t that cool?',
    'headerButtonActionsLabel' => 'My actions',
    'headerActions' => array(
        array('label'=>'first action', 'url'=>'#', 'icon'=>'icon-music'),
        array('label'=>'second action', 'url'=>'#', 'icon'=>'icon-headphones'),
        '---',
        array('label'=>'third action', 'url'=>'#', 'icon'=>'icon-facetime-video')
    )
));


?>