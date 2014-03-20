<?php
/**
 * The following variables are available in this template:
 * - $this: the BootCrudCode object
 */
?>
<?php echo "<?php \$form=\$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'type'=>'inline',
	'action'=>Yii::app()->createUrl(\$this->route),
	'method'=>'get',
)); ?>\n"; ?>
    <ul class="inline">
    <?php foreach($this->tableSchema->columns as $column): ?>
    <?php
        $field=$this->generateInputField($this->modelClass,$column);
        if(strpos($field,'password')!==false)
            continue;
    ?><li>
        <?php // echo "<?php echo ".$this->generateLabel($this->modelClass,$column)."; ?>\n"; ?>
        <?php echo "<?php echo ".$this->generateActiveRow($this->modelClass,$column)."; ?>\n"; ?>
        </li>
    <?php endforeach; ?>
    </ul>
    <?php echo "<?php \$this->widget('bootstrap.widgets.TbButton', array(
	    'buttonType' => 'submit',
	    'type'=>'primary',
	    'label' => Yii::t('backend', 'Search'),
	    'icon' => 'fa fa-search'
    )); ?>\n"; ?>

<?php echo "<?php \$this->endWidget(); ?>\n"; ?>