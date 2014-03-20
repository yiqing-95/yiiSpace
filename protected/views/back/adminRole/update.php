<?php
$this->breadcrumbs=array(
	'Admin Roles'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List AdminRole','url'=>array('index')),
	array('label'=>'Create AdminRole','url'=>array('create'),'linkOptions'=>array('class'=>'ajax_create')),
	array('label'=>'View AdminRole','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage AdminRole','url'=>array('admin')),
);
?>

<h1>Update AdminRole <?php echo $model->id; ?></h1>

<?php
// 引入ztree 插件所需的东西
$this->widget('my.widgets.ztree.ZTree', array()); ?>
<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>