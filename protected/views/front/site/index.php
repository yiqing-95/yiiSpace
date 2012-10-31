<?php $this->pageTitle = Yii::app()->name; ?>
<?php
$banners = array();
for ($i = 1; $i <= 5; $i++) {
    $banners[] = array(
        'image' => bu("public/images/banner{$i}.jpg"),
        'label' => 'First Thumbnail label',
        'caption' => 'Cras justo odio, dapibus ac facilisis in, egestas eget quam. ' .
            'Donec id elit non mi porta gravida at eget metus. ' .
            'Nullam id dolor id nibh ultricies vehicula ut id elit.');
}

?>
<div class="row-fluid">
    <div class="span8">
        <?php
        $this->widget('bootstrap.widgets.TbCarousel', array(
                'items' => $banners,
            )
        ); ?>
    </div>
    <div class="span4">
        <?php
        $this->widget('bootstrap.widgets.TbBox', array(
        'title' => '最近加入',
        'headerIcon' => 'icon-user',
        'content' => 'So this box has actions, isn\'t that cool?',
        'headerButtonActionsLabel' => 'My actions',
        'headerActions' => array(
        array('label'=>'first action', 'url'=>'#', 'icon'=>'icon-music'),
        array('label'=>'second action', 'url'=>'#', 'icon'=>'icon-headphones'),
        '---',
        array('label'=>'third action', 'url'=>'#', 'icon'=>'icon-facetime-video')
        )
        )); ?>

    </div>
</div>

    <div class="row">
        <?php $this->widget('ext.metabox.EMetabox', array(
        'id' => 'mymetabox',
        'url' => array('/user/home/block'),
        'refreshOnInit'=>true,
        'options'=>array(
            'afterRefresh'=>'js:function(data){
                  // alert("youyou");
                   }'
        ),
        )); ?>
    </div>
    <div class="row">
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
    </div>

<?php $box = $this->beginWidget('bootstrap.widgets.TbBox', array(
    'title' => 'Advanced Box',
    'headerIcon' => 'icon-th-list',
    // when displaying a table, if we include bootstra-widget-table class
    // the table will be 0-padding to the box
    'htmlOptions' => array('class' => 'bootstrap-widget-table')
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
        <td>1</td>
        <td>Mark</td>
        <td>Otto</td>
        <td>CSS</td>
        <td>10</td>
    </tr>
    <tr class="even">
        <td>2</td>
        <td>Jacob</td>
        <td>Thornton</td>
        <td>JavaScript</td>
        <td>20</td>
    </tr>
    <tr class="odd">
        <td>3</td>
        <td>Stu</td>
        <td>Dent</td>
        <td>HTML</td>
        <td>15</td>
    </tr>
    </tbody>
</table>
<?php $this->endWidget(); ?>
