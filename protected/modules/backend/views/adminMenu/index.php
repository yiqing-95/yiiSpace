<?php
$this->breadcrumbs=array(
	'Admin Menus',
);

$this->menu=array(
	array('label'=>'Create AdminMenu','url'=>array('create')),
    array('label'=>'Manage AdminMenu(advance mode) ','url'=>array('adminAdv')),
);
?>

<h1>Admin Menus</h1>

<?php $this->widget('zii.widgets.CListView',array(
     'id'=>'admin-menu-list',
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
