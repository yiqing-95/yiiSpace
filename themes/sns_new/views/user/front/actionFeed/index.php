<?php
$this->breadcrumbs=array(
	'Action Feeds',
);

$this->menu=array(
	array('label'=>'Create ActionFeed','url'=>array('create')),
    array('label'=>'Manage ActionFeed(advance mode) ','url'=>array('adminAdv')),
);
?>

<h1>Action Feeds</h1>

<?php $this->widget('zii.widgets.CListView',array(
     'id'=>'action-feed-list',
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
