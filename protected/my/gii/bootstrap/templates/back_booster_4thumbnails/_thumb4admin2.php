<?php
/**
 * The following variables are available in this template:
 * - $this: the BootCrudCode object
 *-----------------------------------------------------------------------
 * <li class="span3">
 *  <a href="#" class="thumbnail" rel="tooltip" data-title="Tooltip">
 *  <img src="http://placehold.it/280x180" alt="">
 *  </a>
 *  </li>
 *-----------------------------------------------------------------------
 */
?>
<li class="span3">

<?php
echo "\t<b><?php echo CHtml::encode(\$data->getAttributeLabel('{$this->tableSchema->primaryKey}')); ?>:</b>\n";
echo "\t<?php echo CHtml::link(CHtml::encode(\$data->{$this->tableSchema->primaryKey}),array('view','id'=>\$data->{$this->tableSchema->primaryKey})); ?>\n\t<br />\n\n";
$count=0;
foreach($this->tableSchema->columns as $column)
{
	if($column->isPrimaryKey)
		continue;
	if(++$count==7)
		echo "\t<?php /*\n";
	echo "\t<b><?php echo CHtml::encode(\$data->getAttributeLabel('{$column->name}')); ?>:</b>\n";
	echo "\t<?php echo CHtml::encode(\$data->{$column->name}); ?>\n\t<br />\n\n";
}
if($count>=7)
	echo "\t*/ ?>\n";
?>

<?php
    echo "\t<?php echo CHtml::checkBox('ids[]',false,array('class'=>'batch-op-item','value'=>\$data->{$this->tableSchema->primaryKey})); ?>\t\t ";
    echo "\t<?php echo CHtml::link(CHtml::encode('查看'),array('view','id'=>\$data->{$this->tableSchema->primaryKey})); ?>\t\t ";
    echo "\t<?php echo CHtml::link(CHtml::encode('创建'),array('create')); ?>\t\t ";
    echo "\t<?php echo CHtml::link(CHtml::encode('编辑'),array('update','id'=>\$data->{$this->tableSchema->primaryKey})); ?>\t\t ";
    echo "\t<?php echo CHtml::link(CHtml::encode('删除'),array('delete','id'=>\$data->{$this->tableSchema->primaryKey}),array('class'=>'delete')); ?>\t\t ";
    ?>

</li>