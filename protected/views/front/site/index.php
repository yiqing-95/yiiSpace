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
        $this->widget('user.widgets.pageblock.UserHomeBlock', array(
            'title' => '最新加入',
            'tbBoxOptions' => array(
                'headerActions' => false,
            )
        )); ?>

    </div>
</div>

<div class="row-fluid">
    <div class="span8">
        <?php $this->widget('ext.metabox.EMetabox', array(
        'id' => 'mymetabox',
        'url' => array('/status/home/block'),
        'refreshOnInit' => true,
        'options' => array(
            'afterRefresh' => 'js:function(data){
                  // alert("youyou");
                   }'
        ),
    )); ?>
    </div>
    <div class="span4">
        <div>
            <?php
            $this->widget("ext.FleetBox.FleetBoxWidget", array(
                    'header' => array(
                        'title' => '网站统计',
                        'addon' => '', //additional string with data near header
                        'actions' => array(
                            //  CHtml::link('add', '#', array('class' => 'btn btn-mini')),
                            //  CHtml::link('delete', '#'),
                        )
                    ),
                    'size' => 'large', //'','large','small'
                    'body' => 'FleetBox size:small',
                )
            );
            ?>
        </div>

        <div>
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
                </tbody>
            </table>
            <?php $this->endWidget(); ?>

        </div>

    </div>
</div>
