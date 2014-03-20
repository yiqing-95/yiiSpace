<?php
$this->breadcrumbs=array(
	'Statuses'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Status','url'=>array('index')),
    array('label'=>'Manage Status(advance mode) ','url'=>array('adminAdv')),
);
?>

<style type="text/css">
    .status-item {padding: 10px 0 10px 0;}
    .divider {margin-top: 20px; border-bottom: 1px solid
    #ccc;}
    .status-item .span1 a {color:red;}
</style>
<div class="cell">

    <h1>Create Status ddd</h1>

    <?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
    <p class="divider"></p>

        <?php
        Yii::app()->controller->beginClip('myStatus');
        Yii::app()->runController('/status/status/listRecentStatus');
        Yii::app()->controller->endClip();
        ?>
<?php

    $this->widget('my.widgets.CascadeFr.CascadeTabView',array(
        'activeTab'=>'tab1',
        'tabs'=>array(
            'myStatus'=>array(
                'title'=>'我的动态',
                'content'=>  $this->clips['myStatus'],
                'active'=>true,
            ),

            'statusForFriends'=>array(
                'title'=>'好友动态',
                'ajax'=>true,
                'url'=>$this->createUrl('statusForFriends'),
            ),
            'statusForAll'=>array(
                'title'=>'大家的动态',
                'url'=>$this->createUrl('statusForAll'),
                'ajax'=>true ,
            ),
        ),
        'htmlOptions'=>array(

        )
    ));
    ?>


</div>