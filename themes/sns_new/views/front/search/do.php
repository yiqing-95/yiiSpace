<?php
/* @var $this SearchController */

$this->breadcrumbs=array(
	'Search'=>array('/search'),
	'Do',
);
?>
<h1><?php echo $this->id . '/' . $this->action->id; ?></h1>

<?php

$this->menu=array(
    array('label'=>'List Post', 'url'=>array('index')),
    array('label'=>'Create Post', 'url'=>array('create')),
);
// 一定要有url  不然会跟分页后的url产生干扰 如：
// http://localhost/sns_my/rcyxw2013/search/do/q//type/blog/yt0/Search/page/2?ajax=search-result-list&q=dfgdsfgsdg&type=blog
Yii::app()->clientScript->registerScript('search', "

$('.search-form form').submit(function(){

	$.fn.yiiListView.update('search-result-list', {
	    url:$(this).attr('action'),
		data: $(this).serialize()
	});

	return false;
});
");
?>
<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form">
    <?php $this->renderPartial('_search',array(

    )); ?>
</div><!-- search-form -->


<h1>Search  results </h1>
<?php if(empty($dataProvider)): ?>


    <?php

     // 虚构一个dataProvider
    // 便于加载 listView 所需的html markup结构以及必要的css js 等
    $dataProvider = new CArrayDataProvider(array(),array());

    $this->widget('TypeSearchListView',array(
        'id'=>'search-result-list',
        'template'=>'{summary}{pager}{items}{pager}{summary}',
        'dataProvider'=>$dataProvider,
        'itemRender'=>'null',
    )); ?>

<?php else :?>
<?php $this->widget('TypeSearchListView',array(
        'id'=>'search-result-list',
        'template'=>'{summary}{pager}{items}{pager}{summary}',
    'dataProvider'=>$dataProvider,
    'itemRender'=>array($typeSearchHandler,'renderItem'),
)); ?>

<?php endif; ?>