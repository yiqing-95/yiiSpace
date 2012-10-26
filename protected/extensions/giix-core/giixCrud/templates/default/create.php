<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php
echo "<?php\n";
$label=$this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs = array(
	Yii::t('controller', '$label') => array('index'),
	Yii::t('app', 'Create'),
);\n";
?>

$this->menu = array(
	array('label'=>Yii::t('app', 'List') . ' <?php echo $this->modelClass; ?>', 'url' => array('index')),
	array('label'=>Yii::t('app', 'Manage') . ' <?php echo $this->modelClass; ?>', 'url' => array('admin')),
);
?>

<h1><?php echo '<?php'; ?> echo Yii::t('app', 'Create'); <?php echo '?>'; ?> <?php echo $this->modelClass; ?></h1>

<?php echo "<?php\n"; ?>
$this->renderPartial('_form', array(
		'model' => $model,
		'buttons' => 'create'));
<?php echo '?>'; ?>