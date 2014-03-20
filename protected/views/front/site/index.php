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
<style type="text/css">
  .sidebar-nav{
      background: #fafad2;
      min-height: 150px;
      height: auto;
  }
    .sidebar-content{
        background: #cccc77;
        min-height: 150px;
        height: auto;
    }
</style>
<div class="row ">
    <div class = "grid_4 sidebar-nav stretchedToMargin">
        this is left
    </div>
    <div class="grid_8 sidebar-content ">
        this is right
    </div>

</div>