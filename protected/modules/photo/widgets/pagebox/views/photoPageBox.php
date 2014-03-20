
<?php $this->beginClip('latestPhotos') ;
$dataProvider = Photo::listRecentPhotos();
$dataProvider->getPagination()->setPageSize(8);
// $this->render('_latest',array('dataProvider'=>$dataProvider));
echo '<ul class="thumbnails">';
foreach ($dataProvider->getData() as $data) {
    $data = Photo::model()->populateRecord($data);
    $this->render('_thumb', array('data' => $data));
    //print_r($data);
}
echo "</ul>";
?>


<?php $this->endClip();?>

<?php

$this->widget('my.widgets.CascadeFr.CascadeTabView',array(
    'activeTab'=>'tab1',
    'tabs'=>array(
        'tab1'=>array(
            'title'=>'最新上传',
            'content'=>  $this->getController()->clips['latestPhotos'],
            'active'=>true,
        ),
        'tab2'=>array(
            'title'=>'Render tab',
            'content'=>'Content for tab 2'
        ),
        'tab3'=>array(
            'title'=>'Url tab',
            'content'=>'Content for tab 3'
        ),
          'tab4'=>array(
            'title'=>'tab 2 title',
            'url'=>'http://www.yiiframework.com/',
       ),
        'tab4'=>array(
            'title'=>'tab 2 title',
            'url'=>'',
            'ajax'=>true ,
        ),
    ),
    'htmlOptions'=>array(

    )
));
?>

<?php
/*
$this->widget('zii.widgets.jui.CJuiTabs', array(
    'tabs' => array(
        'StaticTab 1' => 'Content for tab 1',
        'StaticTab 2' => array('content' => 'Content for tab 2', 'id' => 'tab2'),
        // panel 3 contains the content rendered by a partial view
        'AjaxTab' => array('ajax' => Yii::app()->createUrl('')),
    ),

    // additional javascript options for the tabs plugin
    'options' => array(
        'collapsible' => true,
    ),
));
*/

?>

