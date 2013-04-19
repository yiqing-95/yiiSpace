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
                <div class="col">
                    <div class="cell">

<?php echo "<?php \$form=\$this->beginWidget('CActiveForm', array(
	'id'=>'".$this->class2id($this->modelClass)."-form',
	'enableAjaxValidation'=>false,
)); ?>\n"; ?>

                        <div class="col">
                            <p class="note">Fields with <span class="required">*</span> are required.</p>

                            <?php echo "<?php echo \$form->errorSummary(\$model); ?>\n"; ?>
                        </div>

                        <?php
                        foreach($this->tableSchema->columns as $column)
                        {
                        if($column->autoIncrement)
                            continue;
                        ?>

                            <div class="col">
                                <div class="col size1of4">
                                    <div class="cell">
                                        <?php echo "<?php echo ".$this->generateActiveLabel($this->modelClass,$column)."; ?>\n"; ?>
                                    </div>
                                </div>
                                <div class="col size2of4">
                                    <div class="cell">
                                        <?php echo "<?php echo ".$this->generateActiveField($this->modelClass,$column)."; ?>\n"; ?>
                                    </div>
                                </div>

                                <div class="col sizefill">
                                    <div class="cell">
                                        <?php echo "<?php echo \$form->error(\$model,'{$column->name}'); ?>\n"; ?>
                                    </div>
                                </div>

                            </div>
                        <?php
                        }
                        ?>
                        <div class="col">
                            <div class="col size1of4">
                            </div>
                            <div class="col sizefill">
                                <div class="cell">
                                    <?php echo "<?php echo CHtml::submitButton(\$model->isNewRecord ? 'Create' : 'Save',array('class'=>'button')); ?>\n"; ?>
                                </div>
                            </div>
                        </div>
                            <?php echo "<?php \$this->endWidget(); ?>\n"; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

