<?php
/**
 * The following variables are available in this template:
 * - $this: the BootCrudCode object
 */
?>
<div class="form">
    <?php $beginForm = "<?php echo CHtml::beginForm(\$this->createUrl('batchUpdate')); ?>"; ?>
    <?php echo $beginForm; ?>

    <table>
        <tr>
            <?php foreach ($this->tableSchema->columns as $column): ?>
            <?php if (!$column->autoIncrement): ?>
                <?php $header = "<?php echo CHtml::activeLabel(\$items[0], '{$column->name}') ; ?>\n"; ?>
                <th><?php echo $column->name ; //$header; ?> </th>
                <?php endif; ?>
            <?php endforeach; ?>
        </tr>

        <?php $beginForeach = "<?php foreach(\$items as \$i=>\$item): ?>"; ?>
        <?php echo $beginForeach; ?>
        <tr>
            <?php foreach ($this->tableSchema->columns as $column): ?>
            <?php if ($column->isPrimaryKey): ?>
                <?php $inputPhp = "<?php echo CHtml::hiddenField('ids[]',\$item->getPrimaryKey()); ?>"; /*$column->name*/ ?>
                <?php echo $inputPhp ?>
                <?php endif; ?>

            <?php if (!$column->autoIncrement): ?>
                <td>
                    <?php  $inputPhp = "<?php echo CHtml::activeTextField(\$item,\"{$column->name}\"); ?>";
                    $inputPhp = str_replace("'{$column->name}'", '"[$i]' . $column->name . '"', $inputPhp); ?>
                    <?php echo $inputPhp ?>
                </td>
                <?php endif; ?>
            <?php endforeach; ?>
        </tr>

        <?php $endForeach = "<?php endforeach; ?>"; ?>
        <?php echo $endForeach; ?>
    </table>

    <?php $submitButton = "<?php echo CHtml::submitButton('Save'); ?>";?>
    <?php echo $submitButton;?>
    <?php $endForm = "<?php echo CHtml::endForm(); ?>";?>
    <?php echo $endForm; ?>
</div><!-- form -->