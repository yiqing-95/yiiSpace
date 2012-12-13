<?php
/**
 * The following variables are available in this template:
 * - $this: the BootCrudCode object
 */
?>
<div class="form well">
    <?php echo "<?php \$form=\$this->beginWidget('foundation.widgets.FounActiveForm',array(
	'id'=>'".$this->class2id($this->modelClass)."-form',
	'enableAjaxValidation'=>false,
        'method'=>'post',
	'htmlOptions'=>array(
		'enctype'=>'multipart/form-data'
	)
)); ?>\n"; ?>
    <fieldset>
        <legend>
            <p class="note">Fields with <span class="required">*</span> are required.</p>
        </legend>

        <?php echo "<?php echo \$form->errorSummary(\$model,'Opps!!!', null,array('class'=>'alert alert-error row')); ?>\n"; ?>

        <div class="control-group">
            <div class="span4">

                <?php
                foreach($this->tableSchema->columns as $column)
                {

                    if($column->autoIncrement)
                        continue;
                    ?>
                    <?php echo "<?php echo ".$this->generateActiveRow($this->modelClass,$column)."; ?>\n"; ?>

                    <?php
                }
                ?>
            </div>
        </div>

        <div class="form-actions">
            <?php echo "<?php echo CHtml::submitButton(\$model->isNewRecord ? 'Create' : 'Save'); ?>\n"; ?>
        </div>
    </fieldset>

    <?php echo "<?php \$this->endWidget(); ?>\n"; ?>

</div>
