<?php
$this->breadcrumbs=array(
	'Action Feeds'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List ActionFeed','url'=>array('index')),
    array('label'=>'Manage ActionFeed(advance mode) ','url'=>array('adminAdv')),
);
?>

<h1>Create ActionFeed</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>