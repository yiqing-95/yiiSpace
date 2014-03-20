<?php
/* @var $this GroupTopicController */
/* @var $model GroupTopic */
/* @var $form CActiveForm */
?>

<?php YsPageBox::beginPanel(); ?>

<div class="col cell">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'group-topic-form',
	'enableAjaxValidation'=>false,
	'enableClientValidation'=>true,
    //'focus'=>array($model,''),
	 'htmlOptions' => array(
                    'class'=>'cell',
                    ),
     'clientOptions' => array(
                    'validateOnSubmit'=>true,
                    ),
)); ?>

                        <div class="col">
                            <p class="note">Fields with <span class="required">*</span> are required.</p>

                            <?php echo $form->errorSummary($model); ?>
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
                                        <?php echo $form->labelEx($model,'creator_id'); ?>
                                    </div>
                                </div>
                                <div class="col width-2of4">
                                    <div class="cell">
                                        <?php echo $form->textField($model,'creator_id'); ?>
                                    </div>
                                </div>

                                <div class="col width-fill">
                                    <div class="cell">
                                        <?php echo $form->error($model,'creator_id'); ?>
                                    </div>
                                </div>

                            </div>
                        
                            <div class="col">
                                <div class="col width-1of4">
                                    <div class="cell">
                                        <?php echo $form->labelEx($model,'created'); ?>
                                    </div>
                                </div>
                                <div class="col width-2of4">
                                    <div class="cell">
                                        <?php echo $form->textField($model,'created'); ?>
                                    </div>
                                </div>

                                <div class="col width-fill">
                                    <div class="cell">
                                        <?php echo $form->error($model,'created'); ?>
                                    </div>
                                </div>

                            </div>
                        
                            <div class="col">
                                <div class="col width-1of4">
                                    <div class="cell">
                                        <?php echo $form->labelEx($model,'active'); ?>
                                    </div>
                                </div>
                                <div class="col width-2of4">
                                    <div class="cell">
                                        <?php echo $form->textField($model,'active'); ?>
                                    </div>
                                </div>

                                <div class="col width-fill">
                                    <div class="cell">
                                        <?php echo $form->error($model,'active'); ?>
                                    </div>
                                </div>

                            </div>
                        
                            <div class="col">
                                <div class="col width-1of4">
                                    <div class="cell">
                                        <?php echo $form->labelEx($model,'group_id'); ?>
                                    </div>
                                </div>
                                <div class="col width-2of4">
                                    <div class="cell">
                                        <?php echo $form->textField($model,'group_id'); ?>
                                    </div>
                                </div>

                                <div class="col width-fill">
                                    <div class="cell">
                                        <?php echo $form->error($model,'group_id'); ?>
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

