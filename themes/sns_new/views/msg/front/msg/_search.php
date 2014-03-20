<?php
/* @var $this MsgController */
/* @var $model Msg */
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
                                    <?php echo $form->textField($model,'id',array('size'=>20,'maxlength'=>20)); ?>
                                </div>
                            </div>

                                                    
                            <div class="col">
                                <div class="col width-1of4">
                                    <?php echo $form->label($model,'type'); ?>
                                </div>
                                <div class="col width-fill">
                                    <?php echo $form->textField($model,'type',array('size'=>50,'maxlength'=>50)); ?>
                                </div>
                            </div>

                                                    
                            <div class="col">
                                <div class="col width-1of4">
                                    <?php echo $form->label($model,'uid'); ?>
                                </div>
                                <div class="col width-fill">
                                    <?php echo $form->textField($model,'uid'); ?>
                                </div>
                            </div>

                                                    
                            <div class="col">
                                <div class="col width-1of4">
                                    <?php echo $form->label($model,'data'); ?>
                                </div>
                                <div class="col width-fill">
                                    <?php echo $form->textArea($model,'data',array('rows'=>6, 'cols'=>50)); ?>
                                </div>
                            </div>

                                                    
                            <div class="col">
                                <div class="col width-1of4">
                                    <?php echo $form->label($model,'snd_type'); ?>
                                </div>
                                <div class="col width-fill">
                                    <?php echo $form->textField($model,'snd_type'); ?>
                                </div>
                            </div>

                                                    
                            <div class="col">
                                <div class="col width-1of4">
                                    <?php echo $form->label($model,'snd_status'); ?>
                                </div>
                                <div class="col width-fill">
                                    <?php echo $form->textField($model,'snd_status'); ?>
                                </div>
                            </div>

                                                    
                            <div class="col">
                                <div class="col width-1of4">
                                    <?php echo $form->label($model,'priority'); ?>
                                </div>
                                <div class="col width-fill">
                                    <?php echo $form->textField($model,'priority'); ?>
                                </div>
                            </div>

                                                    
                            <div class="col">
                                <div class="col width-1of4">
                                    <?php echo $form->label($model,'to_id'); ?>
                                </div>
                                <div class="col width-fill">
                                    <?php echo $form->textField($model,'to_id'); ?>
                                </div>
                            </div>

                                                    
                            <div class="col">
                                <div class="col width-1of4">
                                    <?php echo $form->label($model,'msg_pid'); ?>
                                </div>
                                <div class="col width-fill">
                                    <?php echo $form->textField($model,'msg_pid',array('size'=>20,'maxlength'=>20)); ?>
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
                                    <?php echo $form->label($model,'sent_time'); ?>
                                </div>
                                <div class="col width-fill">
                                    <?php echo $form->textField($model,'sent_time'); ?>
                                </div>
                            </div>

                                                    
                            <div class="col">
                                <div class="col width-1of4">
                                    <?php echo $form->label($model,'delete_time'); ?>
                                </div>
                                <div class="col width-fill">
                                    <?php echo $form->textField($model,'delete_time'); ?>
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

