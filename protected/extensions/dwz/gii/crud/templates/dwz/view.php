<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<div class="page">
	<div class="pageHeader">
		<?php
			echo "<?php\n";
			$nameColumn=$this->guessNameColumn($this->tableSchema->columns);
			$label=$this->pluralize($this->class2name($this->modelClass));
			echo "\$this->breadcrumbs=array(
				'$label'=>array('index'),
				\$model->{$nameColumn},
			);\n";
		?>
		?>
		<h1>查看<?php echo $this->modelClass."表的 #<?php echo \$model->{$this->tableSchema->primaryKey}; ?>"; ?> 记录</h1>
	</div>
	<div class="pageContent" layoutH="28">
		<?php echo "<?php"; ?> $this->widget('zii.widgets.CDetailView', array(
			'data'=>$model,
			'cssFile'=>'<?php echo Yii::app()->baseUrl; ?>/css/detailview/styles.css',
			'attributes'=>array(
		<?php
		foreach($this->tableSchema->columns as $column)
			echo "\t\t'".$column->name."',\n";
		?>
			),
		)); ?>
	</div>
</div>