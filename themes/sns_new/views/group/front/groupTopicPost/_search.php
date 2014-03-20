<?php
/* @var $this GroupTopicPostController */
/* @var $model GroupTopicPost */
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
                                    <?php echo $form->label($model,'group_id'); ?>
                                </div>
                                <div class="col width-fill">
                                    <?php echo $form->textField($model,'group_id',array('size'=>11,'maxlength'=>11)); ?>
                                </div>
                            </div>

                                                    
                            <div class="col">
                                <div class="col width-1of4">
                                    <?php echo $form->label($model,'topic_id'); ?>
                                </div>
                                <div class="col width-fill">
                                    <?php echo $form->textField($model,'topic_id',array('size'=>11,'maxlength'=>11)); ?>
                                </div>
                            </div>

                                                    
                            <div class="col">
                                <div class="col width-1of4">
                                    <?php echo $form->label($model,'user_id'); ?>
                                </div>
                                <div class="col width-fill">
                                    <?php echo $form->textField($model,'user_id',array('size'=>11,'maxlength'=>11)); ?>
                                </div>
                            </div>

                                                    
                            <div class="col">
                                <div class="col width-1of4">
                                    <?php echo $form->label($model,'content'); ?>
                                </div>
                                <div class="col width-fill">
                                    <?php echo $form->textArea($model,'content',array('rows'=>6, 'cols'=>50)); ?>
                                </div>
                            </div>

                                                    
                            <div class="col">
                                <div class="col width-1of4">
                                    <?php echo $form->label($model,'ip'); ?>
                                </div>
                                <div class="col width-fill">
                                    <?php echo $form->textField($model,'ip',array('size'=>16,'maxlength'=>16)); ?>
                                </div>
                            </div>

                                                    
                            <div class="col">
                                <div class="col width-1of4">
                                    <?php echo $form->label($model,'create_time'); ?>
                                </div>
                                <div class="col width-fill">
                                    <?php echo $form->textField($model,'create_time'); ?>
                                </div>
                            </div>

                                                    
                            <div class="col">
                                <div class="col width-1of4">
                                    <?php echo $form->label($model,'status'); ?>
                                </div>
                                <div class="col width-fill">
                                    <?php echo $form->textField($model,'status'); ?>
                                </div>
                            </div>

                                                    
                            <div class="col">
                                <div class="col width-1of4">
                                    <?php echo $form->label($model,'is_del'); ?>
                                </div>
                                <div class="col width-fill">
                                    <?php echo $form->textField($model,'is_del'); ?>
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

