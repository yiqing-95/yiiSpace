<?php
/* @var $this UserGleanController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
    'User Gleans',
);

$this->menu=array(
    array('label'=>'Create UserGlean', 'url'=>array('create')),
    array('label'=>'Manage UserGlean', 'url'=>array('admin')),
);
?>

<h1>User Gleans</h1>

<?php
$dataProvider = $model->search();

Yii::app()->controller->beginClip('currentGleanList');

$this->widget('zii.widgets.CListView', array(
    'id'=>'glean-list-view',
    'dataProvider'=>$dataProvider,
    'itemView'=>'_view',
    'itemsCssClass'=>'items',
));

Yii::app()->controller->endClip();

$this->widget('my.widgets.CascadeFr.CascadeTabView',array(
    'activeTab'=>'tab1',
    'tabs'=>array(
        'myStatus'=>array(
            'title'=>'收藏的日志',
            'content'=>  $this->clips['currentGleanList'],
            'active'=>true,
        ),
        /*
        'statusForFriends'=>array(
            'title'=>'收藏的照片',
            'ajax'=>true,
            'url'=>$this->createUrl('',array('objectType'=>'blog')),
        ),
        */

    ),
    'htmlOptions'=>array(

    )
));
?>
<script type="text/javascript">
    // 全局对象用来存储当前点击的收藏链接按钮
    var gleanDeleteLink = null;

    function  reloadGleanList(){
        // 找到listView的id
         var listId = $(gleanDeleteLink).closest('.list-view').attr('id');
        // 之所以没有绑死是因为 有不同的listView 可能共用这个操作
        $.fn.yiiListView.update(listId, {

        });
    }
</script>


