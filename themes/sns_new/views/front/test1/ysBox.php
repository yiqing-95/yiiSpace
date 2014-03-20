<?php
  $this->beginWidget('my.widgets.ysportlet.YsPortlet',array(
      'title'=>''
  ));
?>

<?php
$this->beginWidget('my.widgets.YsBox',array(
    'title'=>'yes',
    'freeHeaderActions'=>array('hello','yeyyey')
));
?>
hi  this is the content in the CPortlet

<?php $this->endWidget(); ?>



<?php $this->endWidget(); ?>

<?php $box = $this->beginWidget('my.widgets.YsBox', array(
    'title' => 'Advanced Box',
    'headerIcon' => 'icon-th-list',
    // when displaying a table, if we include bootstra-widget-table class
    // the table will be 0-padding to the box
    'htmlOptions' => array('class' => 'bootstrap-widget-table'),
    'headerActions' => array(
        array('label'=>'first action', 'url'=>'#', 'icon'=>'icon-music'),
        array('label'=>'second action', 'url'=>'#', 'icon'=>'icon-headphones'),
        '---',
        array('label'=>'third action', 'url'=>'#', 'icon'=>'icon-facetime-video')
    )

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


