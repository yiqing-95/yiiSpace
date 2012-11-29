<?php
$this->breadcrumbs=array(
	'Photos'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Photo','url'=>array('index')),
	array('label'=>'Manage Photo','url'=>array('admin')),
);
?>

<?php
$this->widget('zii.widgets.jui.CJuiTabs', array(
'tabs'=>array(
'StaticTab 1'=> $this->renderPartial('_form', array('model'=>$model),true),
'StaticTab 2'=>array('content'=>'Content for tab 2', 'id'=>'tab2'),
// panel 3 contains the content rendered by a partial view
// 'AjaxTab'=>array('ajax'=>$ajaxUrl),
),
// additional javascript options for the tabs plugin
'options'=>array(
'collapsible'=>true,
),
));
?>

