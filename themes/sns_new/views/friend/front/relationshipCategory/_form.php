<?php
/* @var $this RelationshipCategoryController */
/* @var $model RelationshipCategory */
/* @var $form CActiveForm */
?>

<?php YsPageBox::beginPanel(); ?>

<div class="col cell">

    <div class="col">
        <div class="col width-1of4">
            <div class="cell">
               选择分组:
            </div>
        </div>
        <div class="col width-fill">
            <div class="cell">
                <?php echo CHtml::dropDownList('friend_category_id','',
                    RelationshipCategory::CategoryList(user()->getId())
                    , array('class'=>'input')
                ); ?>
                <?php echo CHtml::hiddenField('friend_object_user_id',$userId); ?>
            </div>
            <div class="cell">
                <button type="button" id="confirm_friend_category_selection">
                    确定
                </button>
                <a onclick=" $('#relationship-category-form').css('display','block'); ">创建分组</a>
            </div>
        </div>
    </div>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'relationship-category-form',
	'enableAjaxValidation'=>false,
	 'htmlOptions'=>array(
                                'class'=>'cell',
                                'style'=> ($model->hasErrors())?'': 'display:none',
                            ),
)); ?>

                        <div class="col">
                            <p class="note">Fields with <span class="required">*</span> are required.</p>

                            <?php echo $form->errorSummary($model); ?>
                        </div>

                        
                            <div class="col">
                                <div class="col width-1of4">
                                    <div class="cell">
                                        <?php echo $form->labelEx($model,'user_id'); ?>
                                    </div>
                                </div>
                                <div class="col width-2of4">
                                    <div class="cell">
                                        <?php echo $form->textField($model,'user_id'); ?>
                                    </div>
                                </div>

                                <div class="col width-fill">
                                    <div class="cell">
                                        <?php echo $form->error($model,'user_id'); ?>
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
                                        <?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>64)); ?>
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
                                        <?php echo $form->labelEx($model,'display_order'); ?>
                                    </div>
                                </div>
                                <div class="col width-2of4">
                                    <div class="cell">
                                        <?php echo $form->textField($model,'display_order'); ?>
                                    </div>
                                </div>

                                <div class="col width-fill">
                                    <div class="cell">
                                        <?php echo $form->error($model,'display_order'); ?>
                                    </div>
                                </div>

                            </div>
                        
                            <div class="col" class="hidden">
                                <div class="col width-1of4">
                                    <div class="cell">
                                        <?php echo $form->labelEx($model,'mbr_count'); ?>
                                    </div>
                                </div>
                                <div class="col width-2of4">
                                    <div class="cell">
                                        <?php echo $form->textField($model,'mbr_count'); ?>
                                    </div>
                                </div>

                                <div class="col width-fill">
                                    <div class="cell">
                                        <?php echo $form->error($model,'mbr_count'); ?>
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

