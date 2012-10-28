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
?>

 <?php $box = $this->beginWidget('bootstrap.widgets.TbBox', array(
    'title' => 'Advanced Box',
    'headerIcon' => 'icon-th-list',
    // when displaying a table, if we include bootstra-widget-table class
    // the table will be 0-padding to the box
    'htmlOptions' => array('class'=>'bootstrap-widget-table')
));?>
<table class="table">
    <thead>
    <tr>
        <th>#</th>
        <th>First name</th>
        <th>Last name</th>
        <th>Language</th>
        <th>Hours worked</th>
    </tr>
    </thead>
    <tbody>
    <tr class="odd">
        <td>1</td><td>Mark</td><td>Otto</td><td>CSS</td><td>10</td>
    </tr>
    <tr class="even">
        <td>2</td><td>Jacob</td><td>Thornton</td><td>JavaScript</td><td>20</td>
    </tr>
    <tr class="odd">
        <td>3</td><td>Stu</td><td>Dent</td><td>HTML</td><td>15</td>
    </tr>
    </tbody>
</table>
<?php $this->endWidget();?>
