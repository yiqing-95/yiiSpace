<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php
echo "<?php\n
\$this->breadcrumbs = array(
	{$this->modelClass}::label(2),
	Yii::t('app', 'Index'),
);\n";
?>

$this->menu = array(
	array('label'=>Yii::t('app', 'Create') . ' ' . <?php echo $this->modelClass; ?>::label(), 'url' => array('create')),
	array('label'=>Yii::t('app', 'Manage') . ' ' . <?php echo $this->modelClass; ?>::label(2), 'url' => array('admin')),
);
?>

<h1><?php echo '<?php'; ?> echo GxHtml::encode(<?php echo $this->modelClass; ?>::label(2)); ?></h1>

<?php echo "<?php"; ?> $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); <?php '?>'; ?>