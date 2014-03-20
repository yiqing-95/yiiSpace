<?php
$this->breadcrumbs=array(
	'Groups',
);

$this->menu=array(
	array('label'=>'Create Group','url'=>array('create')),
    array('label'=>'Manage Group(advance mode) ','url'=>array('adminAdv')),
);
?>

<h1>Groups</h1>

<?php $this->widget('zii.widgets.CListView',array(
     'id'=>'group-list',
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
