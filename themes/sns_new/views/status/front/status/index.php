<?php
$this->breadcrumbs=array(
	'Statuses',
);

$this->menu=array(
	array('label'=>'Create Status','url'=>array('create')),
    array('label'=>'Manage Status(advance mode) ','url'=>array('adminAdv')),
);
?>

<h1>Statusessf </h1>

<?php $this->widget('zii.widgets.CListView',array(
     'id'=>'status-list',
    'template'=>'{pager}{items}{pager}',
	'dataProvider'=>$dataProvider,
	'itemView'=>'_statusView',
    /*
    'pager' => array(
        'class' => 'ext.infiniteScroll.IasPager',
        'rowSelector'=>'.view',
        'listViewId' => 'status-list',
        'header' => '',
        'loaderText'=>'Loading...',
        'options' => array('history' => false, 'triggerPageTreshold' => 2, 'trigger'=>'Load more'),
    ) */
)); ?>
