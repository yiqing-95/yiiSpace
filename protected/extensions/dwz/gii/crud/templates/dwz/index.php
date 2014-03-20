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
			$label=$this->pluralize($this->class2name($this->modelClass));
			echo "\$this->breadcrumbs=array(
				'$label',
			);\n";
		?>
		?>
		<h1><?php echo $label; ?></h1>
	</div>
	<div class="pageContent" layoutH="28">
		<?php echo "<?php"; ?> $this->widget('zii.widgets.CListView', array(
			'dataProvider'=>$dataProvider,
			'itemView'=>'_view',
		)); ?>
</div>
</div>
