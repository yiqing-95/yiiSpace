<?php
$this->breadcrumbs=array(
	'Relationships',
);

$this->menu=array(
	array('label'=>'Create Relationship','url'=>array('create')),
    array('label'=>'Manage Relationship(advance mode) ','url'=>array('adminAdv')),
);
?>

<h1>Relationships</h1>

<?php $this->widget('zii.widgets.CListView',array(
     'id'=>'relationship-list',
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
