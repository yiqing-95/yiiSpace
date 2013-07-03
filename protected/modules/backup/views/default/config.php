<?php
$this->breadcrumbs=array(
	'Install'=>array('/install'),
	'Create',
);?>
<h1><?php echo $this->action->id; ?></h1>

<?php
$this->renderPartial('_config', array(
		'model' => $model,
		'buttons' => 'create'));
?>
