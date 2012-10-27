<?php $this->pageTitle = Yii::app()->name; ?>
<?php
$banners = array();
for($i=1; $i<=5 ; $i++){
    $banners[] =  array(
        'image' => bu("public/images/banner{$i}.jpg"),
        'label' => 'First Thumbnail label',
        'caption' => 'Cras justo odio, dapibus ac facilisis in, egestas eget quam. ' .
            'Donec id elit non mi porta gravida at eget metus. ' .
            'Nullam id dolor id nibh ultricies vehicula ut id elit.');
}

$this->widget('bootstrap.widgets.TbCarousel', array(
    'items' => $banners,
    )
);
