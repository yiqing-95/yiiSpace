<?php
/* @var $this RelationshipCategoryController */
/* @var $model RelationshipCategory */
/* @var $form CActiveForm */
?>
<div class="col">
    <div class="cell panel">
        <div class="body">
            <div class="cell">
                <div class="col" >
                    <div class="wide form cell">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
	'htmlOptions'=>array(
	'class'=>'',
	),
)); ?>

                                                    
                            <div class="col">
                                <div class="col width-1of4">
                                    <?php echo $form->label($model,'id'); ?>
                                </div>
                                <div class="col width-fill">
                                    <?php echo $form->textField($model,'id',array('size'=>11,'maxlength'=>11)); ?>
                                </div>
                            </div>

                                                    
                            <div class="col">
                                <div class="col width-1of4">
                                    <?php echo $form->label($model,'user_id'); ?>
                                </div>
                                <div class="col width-fill">
                                    <?php echo $form->textField($model,'user_id'); ?>
                                </div>
                            </div>

                                                    
                            <div class="col">
                                <div class="col width-1of4">
                                    <?php echo $form->label($model,'name'); ?>
                                </div>
                                <div class="col width-fill">
                                    <?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>64)); ?>
                                </div>
                            </div>

                                                    
                            <div class="col">
                                <div class="col width-1of4">
                                    <?php echo $form->label($model,'display_order'); ?>
                                </div>
                                <div class="col width-fill">
                                    <?php echo $form->textField($model,'display_order'); ?>
                                </div>
                            </div>

                                                    
                            <div class="col">
                                <div class="col width-1of4">
                                    <?php echo $form->label($model,'mbr_count'); ?>
                                </div>
                                <div class="col width-fill">
                                    <?php echo $form->textField($model,'mbr_count'); ?>
                                </div>
                            </div>

                        
                        <div class="col ">
                            <div class="col width-1of4"></div>
                            <div class="col width-fill">
                                <div class="cell">
                                    <?php echo CHtml::submitButton('Search',array(
                                       'class'=>'button',
                                    )); ?>
                                </div>
                            </div>

                        </div>

                        <?php $this->endWidget(); ?>

                    </div><!-- search-form -->
                </div>
            </div>
        </div>
    </div>
</div>

