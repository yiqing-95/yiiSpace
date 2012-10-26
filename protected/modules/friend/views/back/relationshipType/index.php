<?php
$this->breadcrumbs=array(
	'Relationship Types',
);

$this->menu=array(
	array('label'=>'Create RelationshipType','url'=>array('create')),
    array('label'=>'Manage RelationshipType(advance mode) ','url'=>array('adminAdv')),
);
?>

<h1>Relationship Types</h1>

<?php $this->widget('zii.widgets.CListView',array(
     'id'=>'relationship-type-list',
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
