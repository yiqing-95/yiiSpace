<?php
$this->breadcrumbs=array(
	'Admin Roles'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List AdminRole','url'=>array('index')),
	array('label'=>'Manage AdminRole','url'=>array('admin')),
);
?>

<h1>Create AdminRole</h1>
<?php
// 引入ztree 插件所需的东西
$this->widget('my.widgets.ztree.ZTree', array()); ?>
<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>