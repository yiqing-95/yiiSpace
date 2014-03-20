<?php
/* @var $this GroupTopicController */
/* @var $model GroupTopic */
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
                                    <?php echo $form->textField($model,'id'); ?>
                                </div>
                            </div>

                                                    
                            <div class="col">
                                <div class="col width-1of4">
                                    <?php echo $form->label($model,'name'); ?>
                                </div>
                                <div class="col width-fill">
                                    <?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255)); ?>
                                </div>
                            </div>

                                                    
                            <div class="col">
                                <div class="col width-1of4">
                                    <?php echo $form->label($model,'creator_id'); ?>
                                </div>
                                <div class="col width-fill">
                                    <?php echo $form->textField($model,'creator_id'); ?>
                                </div>
                            </div>

                                                    
                            <div class="col">
                                <div class="col width-1of4">
                                    <?php echo $form->label($model,'created'); ?>
                                </div>
                                <div class="col width-fill">
                                    <?php echo $form->textField($model,'created'); ?>
                                </div>
                            </div>

                                                    
                            <div class="col">
                                <div class="col width-1of4">
                                    <?php echo $form->label($model,'active'); ?>
                                </div>
                                <div class="col width-fill">
                                    <?php echo $form->textField($model,'active'); ?>
                                </div>
                            </div>

                                                    
                            <div class="col">
                                <div class="col width-1of4">
                                    <?php echo $form->label($model,'group_id'); ?>
                                </div>
                                <div class="col width-fill">
                                    <?php echo $form->textField($model,'group_id'); ?>
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

