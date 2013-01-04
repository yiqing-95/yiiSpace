<?php
$this->breadcrumbs=array(
	'Manage'=>array('index'),
);?>
<h1><?php $this->action->id; ?></h1>

<p> Manage database backup files</p>
<?php $this->renderPartial('_list', array(
		'dataProvider'=>$dataProvider,
));
?>
