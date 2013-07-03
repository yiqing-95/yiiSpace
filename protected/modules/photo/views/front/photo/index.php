<?php
$this->breadcrumbs=array(
	'Photos',
);

$this->menu=array(
	array('label'=>'Create Photo','url'=>array('create')),
	array('label'=>'Manage Photo','url'=>array('admin')),
);
?>

<h1>Photos</h1>

<?php
$this->widget('bootstrap.widgets.TbThumbnails', array(
    'dataProvider'=>$dataProvider,
    'template'=>"{items}\n{pager}",
    'itemView'=>'_thumb',
    'htmlOptions'=>array(
        'style'=>'margin:5px;'
    )
));
/*
$this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); */?>
