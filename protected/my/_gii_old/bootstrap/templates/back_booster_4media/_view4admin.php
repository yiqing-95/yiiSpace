<?php
/**
 * The following variables are available in this template:
 * - $this: the BootCrudCode object
 */
?>

<div class="media view">
    <a class="pull-left" href="#">
        <img class="media-object" data-src="http://twitter.github.com/bootstrap/holder.js/64x64"
             style="width: 64px; height: 64px;"
            src="<?php echo "<?php"; ?> echo bu('public/images/yii.png');?>">
        <?php  echo "\t<?php echo CHtml::checkBox('items[]',false,array('class'=>'batch-op-item','value'=>\$data->{$this->tableSchema->primaryKey})); ?>\t\t ";?>
    </a>
    <div class="media-body">
        <h4 class="media-heading">Media heading
            <?php  $nameColumn=$this->guessNameColumn($this->tableSchema->columns); ?>
            <?php echo "\t<b><?php echo CHtml::encode(\$data->{$nameColumn}); ?>:</b>\n"; ?>
        </h4>
        ...

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

        <br/>
        <?php
        echo "\t<?php echo CHtml::link(CHtml::encode('查看'),array('view','id'=>\$data->{$this->tableSchema->primaryKey})); ?>\t\t ";
        echo "\t<?php echo CHtml::link(CHtml::encode('创建'),array('create')); ?>\t\t ";
        echo "\t<?php echo CHtml::link(CHtml::encode('编辑'),array('update','id'=>\$data->{$this->tableSchema->primaryKey})); ?>\t\t ";
        echo "\t<?php echo CHtml::link(CHtml::encode('删除'),array('delete','id'=>\$data->{$this->tableSchema->primaryKey}),array('class'=>'delete')); ?>\t\t ";
        ?>
    </div>
</div>