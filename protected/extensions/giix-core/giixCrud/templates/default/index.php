<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php
echo "<?php\n";
$label = $this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs = array(
	Yii::t('controller', '$label'),
	Yii::t('app', 'Index'),
);\n";
?>

$this->menu = array(
	array('label'=>Yii::t('app', 'Create') . ' <?php echo $this->modelClass; ?>', 'url' => array('create')),
	array('label'=>Yii::t('app', 'Manage') . ' <?php echo $this->modelClass; ?>', 'url' => array('admin')),
);
?>

<h1><?php echo $label; ?></h1>

<?php echo "<?php"; ?> $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); <?php '?>'; ?>