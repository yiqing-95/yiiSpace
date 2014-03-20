<?php
/**
 * The following variables are available in this template:
 * - $this: the BootCrudCode object
 */
?>
<?php
echo "<?php\n";
$label=$this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs=array(
	'$label',
);\n";
?>

$this->menu=array(
	array('label'=>'Create <?php echo $this->modelClass; ?>','url'=>array('create')),
    array('label'=>'Manage <?php echo $this->modelClass; ?>(advance mode) ','url'=>array('adminAdv')),
);
?>

<h1><?php echo $label; ?></h1>

<?php echo "<?php"; ?> $this->widget('zii.widgets.CListView',array(
     'id'=>'<?php echo $this->class2id($this->modelClass); ?>-list',
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
