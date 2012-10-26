<?php
$this->breadcrumbs=array(
	'Msgs'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Msg','url'=>array('index')),
    array('label'=>'Manage Msg(advance mode) ','url'=>array('adminAdv')),
);
?>

<h1>Create Msg</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>