<?php
$this->breadcrumbs=array(
	'Group Topics'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List GroupTopic','url'=>array('index')),
    array('label'=>'Manage GroupTopic(advance mode) ','url'=>array('adminAdv')),
);
?>

<h1>Create GroupTopic</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>