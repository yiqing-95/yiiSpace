<?php
$this->breadcrumbs = array(
    'Sys Modules',
);

$this->menu = array(
    array('label' => 'Create SysModule', 'url' => array('create')),
    array('label' => 'Manage SysModule(advance mode) ', 'url' => array('adminAdv')),
);
?>

<h1>Sys Modules</h1>

<?php
/*$this->widget('zii.widgets.CListView',array(
     'id'=>'sys-module-list',
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
));*/
?>
<p>
<h4>uninstalled modules</h4>
</p>
<?php foreach ($uninstalled as $m): ?>
<label> module name: </label>
<span>
     <?php  echo $m; ?>
    </span>
<span>
             <?php echo CHtml::link('install', array('install', 'm' => $m));         ?>
         </span>
<br/>
<?php endforeach; ?>
<hr/>
<p>
<h4>installed modules</h4>
</p>
<?php foreach ($installed as $m): ?>
<label> module name: </label>
<span>
     <?php  echo $m; ?>
    </span>
<span>
             <?php echo CHtml::link('uninstall', array('uninstall', 'm' => $m));         ?>
         </span>
<br/>
<?php endforeach; ?>