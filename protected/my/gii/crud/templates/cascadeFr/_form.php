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

<?php echo "<?php"; ?> YsPageBox::beginPanel(); ?>

<div class="col cell">

<?php echo "<?php \$form=\$this->beginWidget('CActiveForm', array(
	'id'=>'".$this->class2id($this->modelClass)."-form',
	'enableAjaxValidation'=>false,
	'enableClientValidation'=>true,
    //'focus'=>array(\$model,''),
	 'htmlOptions' => array(
                    'class'=>'cell',
                    ),
     'clientOptions' => array(
                    'validateOnSubmit'=>true,
                    ),
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
                                <div class="col width-1of4">
                                    <div class="cell">
                                        <?php echo "<?php echo ".$this->generateActiveLabel($this->modelClass,$column)."; ?>\n"; ?>
                                    </div>
                                </div>
                                <div class="col width-2of4">
                                    <div class="cell">
                                        <?php echo "<?php echo ".$this->generateActiveField($this->modelClass,$column)."; ?>\n"; ?>
                                    </div>
                                </div>

                                <div class="col width-fill">
                                    <div class="cell">
                                        <?php echo "<?php echo \$form->error(\$model,'{$column->name}'); ?>\n"; ?>
                                    </div>
                                </div>

                            </div>
                        <?php
                        }
                        ?>
                        <div class="col">
                            <div class="col width-1of4">
                            </div>
                            <div class="col width-fill">
                                <div class="cell">
                                    <?php echo "<?php echo CHtml::submitButton(\$model->isNewRecord ? 'Create' : 'Save',array('class'=>'button')); ?>\n"; ?>
                                </div>
                            </div>
                        </div>
                            <?php echo "<?php \$this->endWidget(); ?>\n"; ?>

</div>

<?php echo "<?php"; ?>  YsPageBox::endPanel(); ?>

