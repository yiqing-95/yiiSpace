<?php
/* @var $this NewsEntryController */
/* @var $model NewsEntry */
/* @var $form CActiveForm */
?>


<div class="col">
    <div class="cell panel">
        <div class="body">
            <div class="cell">
                <div class="col">
                    <div class="cell">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'news-entry-form',
	'enableAjaxValidation'=>false,
)); ?>

                        <div class="col">
                            <p class="note">Fields with <span class="required">*</span> are required.</p>

                            <?php echo $form->errorSummary($model); ?>
                        </div>

                        
                            <div class="col">
                                <div class="col width-1of4">
                                    <div class="cell">
                                        <?php echo $form->labelEx($model,'creator'); ?>
                                    </div>
                                </div>
                                <div class="col width-2of4">
                                    <div class="cell">
                                        <?php echo $form->textField($model,'creator'); ?>
                                    </div>
                                </div>

                                <div class="col width-fill">
                                    <div class="cell">
                                        <?php echo $form->error($model,'creator'); ?>
                                    </div>
                                </div>

                            </div>
                        
                            <div class="col">
                                <div class="col width-1of4">
                                    <div class="cell">
                                        <?php echo $form->labelEx($model,'cate_id'); ?>
                                    </div>
                                </div>
                                <div class="col width-2of4">
                                    <div class="cell">
                                        <?php echo $form->textField($model,'cate_id'); ?>
                                    </div>
                                </div>

                                <div class="col width-fill">
                                    <div class="cell">
                                        <?php echo $form->error($model,'cate_id'); ?>
                                    </div>
                                </div>

                            </div>
                        
                            <div class="col">
                                <div class="col width-1of4">
                                    <div class="cell">
                                        <?php echo $form->labelEx($model,'title'); ?>
                                    </div>
                                </div>
                                <div class="col width-2of4">
                                    <div class="cell">
                                        <?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255)); ?>
                                    </div>
                                </div>

                                <div class="col width-fill">
                                    <div class="cell">
                                        <?php echo $form->error($model,'title'); ?>
                                    </div>
                                </div>

                            </div>
                        
                            <div class="col">
                                <div class="col width-1of4">
                                    <div class="cell">
                                        <?php echo $form->labelEx($model,'order'); ?>
                                    </div>
                                </div>
                                <div class="col width-2of4">
                                    <div class="cell">
                                        <?php echo $form->textField($model,'order'); ?>
                                    </div>
                                </div>

                                <div class="col width-fill">
                                    <div class="cell">
                                        <?php echo $form->error($model,'order'); ?>
                                    </div>
                                </div>

                            </div>
                        
                            <div class="col">
                                <div class="col width-1of4">
                                    <div class="cell">
                                        <?php echo $form->labelEx($model,'deleted'); ?>
                                    </div>
                                </div>
                                <div class="col width-2of4">
                                    <div class="cell">
                                        <?php echo $form->textField($model,'deleted'); ?>
                                    </div>
                                </div>

                                <div class="col width-fill">
                                    <div class="cell">
                                        <?php echo $form->error($model,'deleted'); ?>
                                    </div>
                                </div>

                            </div>
                        
                            <div class="col">
                                <div class="col width-1of4">
                                    <div class="cell">
                                        <?php echo $form->labelEx($model,'create_time'); ?>
                                    </div>
                                </div>
                                <div class="col width-2of4">
                                    <div class="cell">
                                        <?php echo $form->textField($model,'create_time'); ?>
                                    </div>
                                </div>

                                <div class="col width-fill">
                                    <div class="cell">
                                        <?php echo $form->error($model,'create_time'); ?>
                                    </div>
                                </div>

                            </div>
                                                <div class="col">
                            <div class="col width-1of4">
                            </div>
                            <div class="col width-fill">
                                <div class="cell">
                                    <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save',array('class'=>'button')); ?>
                                </div>
                            </div>
                        </div>
                            <?php $this->endWidget(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

