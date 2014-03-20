<?php
/* @var $this CategoryController */
/* @var $model Category */
/* @var $form CActiveForm */
?>

<?php YsPageBox::beginPanel(); ?>

<div class="col cell">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'category-form',
	'enableAjaxValidation'=>false,
	 'htmlOptions'=>array(
                                'class'=>'cell'
                            ),
)); ?>

                        <div class="col">
                            <p class="note">Fields with <span class="required">*</span> are required.</p>

                            <?php echo $form->errorSummary($model); ?>
                        </div>

                        
                            <div class="col">
                                <div class="col width-1of4">
                                    <div class="cell">
                                        <?php echo $form->labelEx($model,'uid'); ?>
                                    </div>
                                </div>
                                <div class="col width-2of4">
                                    <div class="cell">
                                        <?php echo $form->textField($model,'uid'); ?>
                                    </div>
                                </div>

                                <div class="col width-fill">
                                    <div class="cell">
                                        <?php echo $form->error($model,'uid'); ?>
                                    </div>
                                </div>

                            </div>
                        
                            <div class="col">
                                <div class="col width-1of4">
                                    <div class="cell">
                                        <?php echo $form->labelEx($model,'pid'); ?>
                                    </div>
                                </div>
                                <div class="col width-2of4">
                                    <div class="cell">
                                        <?php echo $form->textField($model,'pid',array('size'=>11,'maxlength'=>11)); ?>
                                    </div>
                                </div>

                                <div class="col width-fill">
                                    <div class="cell">
                                        <?php echo $form->error($model,'pid'); ?>
                                    </div>
                                </div>

                            </div>
                        
                            <div class="col">
                                <div class="col width-1of4">
                                    <div class="cell">
                                        <?php echo $form->labelEx($model,'name'); ?>
                                    </div>
                                </div>
                                <div class="col width-2of4">
                                    <div class="cell">
                                        <?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255)); ?>
                                    </div>
                                </div>

                                <div class="col width-fill">
                                    <div class="cell">
                                        <?php echo $form->error($model,'name'); ?>
                                    </div>
                                </div>

                            </div>
                        
                            <div class="col">
                                <div class="col width-1of4">
                                    <div class="cell">
                                        <?php echo $form->labelEx($model,'alias'); ?>
                                    </div>
                                </div>
                                <div class="col width-2of4">
                                    <div class="cell">
                                        <?php echo $form->textField($model,'alias',array('size'=>60,'maxlength'=>255)); ?>
                                    </div>
                                </div>

                                <div class="col width-fill">
                                    <div class="cell">
                                        <?php echo $form->error($model,'alias'); ?>
                                    </div>
                                </div>

                            </div>
                        
                            <div class="col">
                                <div class="col width-1of4">
                                    <div class="cell">
                                        <?php echo $form->labelEx($model,'position'); ?>
                                    </div>
                                </div>
                                <div class="col width-2of4">
                                    <div class="cell">
                                        <?php echo $form->textField($model,'position',array('size'=>11,'maxlength'=>11)); ?>
                                    </div>
                                </div>

                                <div class="col width-fill">
                                    <div class="cell">
                                        <?php echo $form->error($model,'position'); ?>
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

<?php  YsPageBox::endPanel(); ?>

