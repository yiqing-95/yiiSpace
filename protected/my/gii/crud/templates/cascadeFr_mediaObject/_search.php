<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $model <?php echo $this->getModelClass(); ?> */
/* @var $form CActiveForm */
?>
<div class="col">
    <div class="cell panel">
        <div class="body">
            <div class="cell">
                <div class="col" >
                    <div class="wide form cell">

<?php echo "<?php \$form=\$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl(\$this->route),
	'method'=>'get',
	'htmlOptions'=>array(
	'class'=>'',
	),
)); ?>\n"; ?>

                        <?php foreach($this->tableSchema->columns as $column): ?>
                            <?php
                            $field=$this->generateInputField($this->modelClass,$column);
                            if(strpos($field,'password')!==false)
                                continue;
                            ?>

                            <div class="col">
                                <div class="col size1of4">
                                    <?php echo "<?php echo \$form->label(\$model,'{$column->name}'); ?>\n"; ?>
                                </div>
                                <div class="col sizefill">
                                    <?php echo "<?php echo ".$this->generateActiveField($this->modelClass,$column)."; ?>\n"; ?>
                                </div>
                            </div>

                        <?php endforeach; ?>

                        <div class="col ">
                            <div class="col size1of4"></div>
                            <div class="col sizefill">
                                <div class="cell">
                                    <?php echo "<?php echo CHtml::submitButton('Search',array(
                                       'class'=>'button',
                                    )); ?>\n"; ?>
                                </div>
                            </div>

                        </div>

                        <?php echo "<?php \$this->endWidget(); ?>\n"; ?>

                    </div><!-- search-form -->
                </div>
            </div>
        </div>
    </div>
</div>

