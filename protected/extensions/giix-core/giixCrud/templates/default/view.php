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
	Yii::t('controller', '$label') => array('index'),
	GxHtml::valueEx(\$model),
);\n";
?>

$this->menu=array(
	array('label'=>Yii::t('app', 'List') . ' <?php echo $this->modelClass; ?>', 'url'=>array('index')),
	array('label'=>Yii::t('app', 'Create') . ' <?php echo $this->modelClass; ?>', 'url'=>array('create')),
	array('label'=>Yii::t('app', 'Update') . ' <?php echo $this->modelClass; ?>', 'url'=>array('update', 'id' => $model-><?php echo $this->tableSchema->primaryKey; ?>)),
	array('label'=>Yii::t('app', 'Delete') . ' <?php echo $this->modelClass; ?>', 'url'=>'#', 'linkOptions' => array('submit' => array('delete', 'id' => $model-><?php echo $this->tableSchema->primaryKey; ?>), 'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>Yii::t('app', 'Manage') . ' <?php echo $this->modelClass; ?>', 'url'=>array('admin')),
);
?>

<h1><?php echo '<?php'; ?> echo Yii::t('app', 'View'); <?php echo '?>'; ?> <?php echo $this->modelClass . " #<?php echo GxHtml::encode(GxHtml::valueEx(\$model)); ?>"; ?></h1>

<?php echo '<?php'; ?> $this->widget('zii.widgets.CDetailView', array(
	'data' => $model,
	'attributes' => array(
<?php
foreach ($this->tableSchema->columns as $column)
		echo $this->generateDetailViewAttribute($this->modelClass, $column) . ",\n";
?>
	),
)); ?>

<?php foreach (GxActiveRecord::model($this->modelClass)->relations() as $relationName => $relation): ?>
<?php if ($relation[0] == GxActiveRecord::HAS_MANY || $relation[0] == GxActiveRecord::MANY_MANY): ?>
<h2><?php echo $this->pluralize($this->class2name($relation[1])); ?></h2>
<?php echo "<?php\n"; ?>
	echo GxHtml::openTag('ul');
	foreach($model-><?php echo $relationName; ?> as $relatedModel) {
		echo GxHtml::openTag('li');
		echo GxHtml::link(GxHtml::encode(GxHtml::valueEx($relatedModel)), array('<?php echo strtolower($relation[1][0]) . substr($relation[1], 1); ?>/view', 'id' => GxActiveRecord::extractPkValue($relatedModel, true)));
		echo GxHtml::closeTag('li');
	}
	echo GxHtml::closeTag('ul');
<?php echo '?>'; ?>
<?php endif; ?>
<?php endforeach; ?>